<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MpesaService
{
    private string $base;

    public function __construct()
    {
        $this->base = config('mpesa.env') === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    public function getToken(): string
    {
        return Cache::remember('mpesa_token', 3500, function () {
            $response = Http::withBasicAuth(
                config('mpesa.consumer_key'),
                config('mpesa.consumer_secret')
            )->get("{$this->base}/oauth/v1/generate?grant_type=client_credentials");

            return $response->json('access_token');
        });
    }

    private function formatPhone(string $phone): string
    {
        $phone = trim(str_replace([' ', '-', '+'], '', $phone));

        if (str_starts_with($phone, '254') && strlen($phone) === 12) {
            return $phone;
        }
        if (str_starts_with($phone, '0') && strlen($phone) === 10) {
            return '254' . substr($phone, 1);
        }
        if (strlen($phone) === 9) {
            return '254' . $phone;
        }

        return $phone;
    }

    public function stkPush(string $phone, float $amount, int $bookingId): array
    {
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode(
            config('mpesa.shortcode') .
            config('mpesa.passkey') .
            $timestamp
        );

        $phone = $this->formatPhone($phone);

        return Http::withToken($this->getToken())
            ->post("{$this->base}/mpesa/stkpush/v1/processrequest", [
                'BusinessShortCode' => config('mpesa.shortcode'),
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'TransactionType'   => 'CustomerPayBillOnline',
                'Amount'            => (int) $amount,
                'PartyA'            => $phone,
                'PartyB'            => config('mpesa.shortcode'),
                'PhoneNumber'       => $phone,
                'CallBackURL'       => config('mpesa.callback_url'),
                'AccountReference'  => 'BUSTRAK-' . $bookingId,
                'TransactionDesc'   => 'BusTrak ticket payment',
            ])->json();
    }
}