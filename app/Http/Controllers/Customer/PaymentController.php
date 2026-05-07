<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\MpesaService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) abort(403);
        
        $booking->load(['schedule.route', 'user']);
        
        // FIX: Calculate total amount from seat count and fare
        $seatCount = $booking->seats_count ?? count(explode(',', $booking->seat_numbers ?? ''));
        $farePerSeat = $booking->schedule->route->fare ?? 500; // Default fallback
        $totalAmount = $seatCount * $farePerSeat;
        
        // If total_amount is 0 in database, use calculated amount
        if ($booking->total_amount == 0 || !$booking->total_amount) {
            $booking->total_amount = $totalAmount;
            $booking->save();
        }
        
        return view('customer.payment', compact('booking', 'totalAmount', 'farePerSeat', 'seatCount'));
    }

    public function status(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) abort(403);
        
        $booking->load(['schedule.route', 'user', 'payment']);
        
        // Calculate amounts for display
        $seatCount = $booking->seats_count ?? count(explode(',', $booking->seat_numbers ?? ''));
        $farePerSeat = $booking->schedule->route->fare ?? 500;
        $totalAmount = $seatCount * $farePerSeat;
        
        return view('customer.payment-status', compact('booking', 'totalAmount', 'farePerSeat', 'seatCount'));
    }

    public function check(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) abort(403);
        
        $booking->load(['payment']);

        return response()->json([
            'status'         => $booking->status,
            'payment_status' => $booking->payment?->status ?? 'pending',
            'receipt'        => $booking->payment?->mpesa_receipt,
            'total_amount'   => $booking->total_amount,
        ]);
    }

    public function initiate(Request $request, Booking $booking)
    {
        $request->validate([
            'phone' => 'required|string|min:9|max:13',
        ]);

        if ($booking->user_id !== auth()->id()) abort(403);

        $booking->load(['schedule.route']);

        // FIX: Calculate amount properly
        $seatCount = $booking->seats_count ?? count(explode(',', $booking->seat_numbers ?? ''));
        $farePerSeat = $booking->schedule->route->fare ?? 
                       $booking->schedule->fare ?? 
                       500; // Default fallback
        
        $amount = $farePerSeat * $seatCount;

        // Log the calculation for debugging
        \Log::info('Payment initiation:', [
            'booking_id' => $booking->id,
            'seat_count' => $seatCount,
            'fare_per_seat' => $farePerSeat,
            'amount' => $amount
        ]);

        if (!$amount || $amount <= 0) {
            return back()->with('error',
                'Could not determine fare amount. Please contact support. ' .
                'Seats: ' . $seatCount . ', Fare: ' . $farePerSeat
            );
        }

        $mpesa    = app(MpesaService::class);
        $response = $mpesa->stkPush($request->phone, (float) $amount, $booking->id);

        if (isset($response['CheckoutRequestID'])) {
            
            // Update booking with correct total amount
            if ($booking->total_amount == 0 || !$booking->total_amount) {
                $booking->update(['total_amount' => $amount]);
            }
            
            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'phone'             => $request->phone,
                    'amount'            => $amount,
                    'mpesa_checkout_id' => $response['CheckoutRequestID'],
                    'status'            => 'pending',
                ]
            );

            return redirect()->route('customer.payment.status', $booking->id);
        }

        return back()->with('error',
            'Payment failed: ' . (
                $response['errorMessage'] ??
                $response['ResponseDescription'] ??
                'Unknown error from Daraja API'
            )
        );
    }

    public function callback(Request $request)
    {
        $data = $request->input('Body.stkCallback');

        if (!$data) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $payment = Payment::where('mpesa_checkout_id', $data['CheckoutRequestID'])->first();

        if (!$payment) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        if ($data['ResultCode'] === 0) {
            $items   = collect($data['CallbackMetadata']['Item']);
            $receipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
            $amount  = $items->firstWhere('Name', 'Amount')['Value'] ?? $payment->amount;

            $payment->update([
                'status'        => 'completed',
                'mpesa_receipt' => $receipt,
                'amount'        => $amount,
                'paid_at'       => now(),
            ]);

            $payment->booking->update([
                'status' => 'confirmed',
                'total_amount' => $amount
            ]);

            try {
                \Mail::to($payment->booking->user->email)
                    ->send(new \App\Mail\BookingConfirmed($payment->booking));

                \Mail::to($payment->booking->user->email)
                    ->send(new \App\Mail\PaymentReceived($payment));
            } catch (\Exception $e) {
                \Log::error('Email failed: ' . $e->getMessage());
            }

        } else {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}