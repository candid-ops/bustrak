<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Models\Bus;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month'); // week, month, year
        $startDate = $this->getStartDate($period);
        
        // Dashboard Statistics
        $stats = [
            'total_bookings' => Booking::where('created_at', '>=', $startDate)->count(),
            'total_revenue' => Booking::where('status', 'confirmed')
                ->where('created_at', '>=', $startDate)
                ->sum('total_amount'),
            'active_buses' => Bus::where('status', 'active')->count(),
            'total_customers' => User::role('customer')->count(),
            'avg_booking_value' => Booking::where('status', 'confirmed')
                ->where('created_at', '>=', $startDate)
                ->avg('total_amount') ?? 0,
            'cancellation_rate' => $this->getCancellationRate($startDate),
            'occupancy_rate' => $this->getOccupancyRate($startDate),
        ];
        
        // Revenue Chart Data
        $revenueData = $this->getRevenueData($period);
        
        // Popular Routes
        $popularRoutes = $this->getPopularRoutes($startDate);
        
        // Peak Booking Times (Hourly)
        $peakHours = $this->getPeakHours($startDate);
        
        // Daily Bookings (Last 7/30 days)
        $dailyBookings = $this->getDailyBookings($period);
        
        // Payment Methods Distribution
        $paymentDistribution = $this->getPaymentDistribution($startDate);
        
        // Top Customers
        $topCustomers = $this->getTopCustomers($startDate);
        
        // Route Performance
        $routePerformance = $this->getRoutePerformance($startDate);
        
        // Monthly Comparison (Previous period vs current)
        $comparison = $this->getPeriodComparison($period);
        
        return view('admin.analytics.index', compact(
            'stats', 'revenueData', 'popularRoutes', 'peakHours', 
            'dailyBookings', 'paymentDistribution', 'topCustomers', 
            'routePerformance', 'comparison', 'period'
        ));
    }
    
    private function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->subDays(7);
            case 'month':
                return now()->subMonth();
            case 'year':
                return now()->subYear();
            default:
                return now()->subMonth();
        }
    }
    
    private function getCancellationRate($startDate)
    {
        $total = Booking::where('created_at', '>=', $startDate)->count();
        $cancelled = Booking::where('status', 'cancelled')
            ->where('created_at', '>=', $startDate)
            ->count();
        
        return $total > 0 ? round(($cancelled / $total) * 100, 1) : 0;
    }
    
    private function getOccupancyRate($startDate)
    {
        $totalCapacity = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('buses', 'schedules.bus_id', '=', 'buses.id')
            ->where('bookings.created_at', '>=', $startDate)
            ->sum('buses.capacity');
        
        $totalBooked = Booking::where('status', 'confirmed')
            ->where('created_at', '>=', $startDate)
            ->sum('seats_count');
        
        return $totalCapacity > 0 ? round(($totalBooked / $totalCapacity) * 100, 1) : 0;
    }
    
    private function getRevenueData($period)
    {
        $data = [];
        
        if ($period == 'week') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $revenue = Booking::where('status', 'confirmed')
                    ->whereDate('created_at', $date)
                    ->sum('total_amount');
                $data['labels'][] = now()->subDays($i)->format('D');
                $data['revenue'][] = (float) $revenue;
                $data['bookings'][] = Booking::whereDate('created_at', $date)->count();
            }
        } elseif ($period == 'month') {
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $revenue = Booking::where('status', 'confirmed')
                    ->whereDate('created_at', $date)
                    ->sum('total_amount');
                $data['labels'][] = now()->subDays($i)->format('d M');
                $data['revenue'][] = (float) $revenue;
                $data['bookings'][] = Booking::whereDate('created_at', $date)->count();
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $revenue = Booking::where('status', 'confirmed')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_amount');
                $data['labels'][] = $date->format('M Y');
                $data['revenue'][] = (float) $revenue;
                $data['bookings'][] = Booking::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
        }
        
        return $data;
    }
    
    private function getPopularRoutes($startDate)
    {
        return DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->where('bookings.status', 'confirmed')
            ->where('bookings.created_at', '>=', $startDate)
            ->select('routes.origin', 'routes.destination', DB::raw('COUNT(*) as total_bookings'), DB::raw('SUM(bookings.total_amount) as total_revenue'))
            ->groupBy('routes.id', 'routes.origin', 'routes.destination')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();
    }
    
    private function getPeakHours($startDate)
    {
        $hours = [];
        for ($i = 0; $i < 24; $i++) {
            $hours[$i] = 0;
        }
        
        $bookings = Booking::where('status', 'confirmed')
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('HOUR(created_at) as hour'))
            ->get();
        
        foreach ($bookings as $booking) {
            $hours[(int)$booking->hour]++;
        }
        
        return [
            'labels' => array_map(function($h) {
                return sprintf('%02d:00', $h);
            }, array_keys($hours)),
            'values' => array_values($hours)
        ];
    }
    
    private function getDailyBookings($period)
    {
        $days = $period == 'week' ? 7 : 30;
        $data = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data['labels'][] = now()->subDays($i)->format('d M');
            $data['bookings'][] = Booking::whereDate('created_at', $date)->count();
        }
        
        return $data;
    }
    
    private function getPaymentDistribution($startDate)
    {
        $mpesa = Booking::where('status', 'confirmed')
            ->where('created_at', '>=', $startDate)
            ->whereHas('payment', function($q) {
                $q->where('mpesa_receipt', '!=', null);
            })
            ->count();
        
        $cash = Booking::where('status', 'confirmed')
            ->where('created_at', '>=', $startDate)
            ->whereDoesntHave('payment')
            ->count();
        
        $total = $mpesa + $cash;
        
        return [
            'labels' => ['M-Pesa', 'Cash'],
            'values' => [
                $total > 0 ? round(($mpesa / $total) * 100, 1) : 0,
                $total > 0 ? round(($cash / $total) * 100, 1) : 0
            ]
        ];
    }
    
    private function getTopCustomers($startDate)
    {
        return DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.status', 'confirmed')
            ->where('bookings.created_at', '>=', $startDate)
            ->select('users.name', 'users.email', DB::raw('COUNT(*) as total_bookings'), DB::raw('SUM(bookings.total_amount) as total_spent'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();
    }
    
    private function getRoutePerformance($startDate)
    {
        return DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->where('bookings.status', 'confirmed')
            ->where('bookings.created_at', '>=', $startDate)
            ->select(
                DB::raw("CONCAT(routes.origin, ' → ', routes.destination) as route_name"),
                DB::raw('COUNT(*) as bookings'),
                DB::raw('SUM(bookings.total_amount) as revenue'),
                DB::raw('AVG(bookings.total_amount) as avg_ticket')
            )
            ->groupBy('routes.id', 'routes.origin', 'routes.destination')
            ->orderByDesc('revenue')
            ->get();
    }
    
    private function getPeriodComparison($period)
    {
        $currentStart = $this->getStartDate($period);
        $previousStart = clone $currentStart;
        
        if ($period == 'week') {
            $previousStart = $previousStart->subDays(7);
            $currentEnd = now();
            $previousEnd = $currentStart;
        } elseif ($period == 'month') {
            $previousStart = $previousStart->subMonth();
            $currentEnd = now();
            $previousEnd = $currentStart;
        } else {
            $previousStart = $previousStart->subYear();
            $currentEnd = now();
            $previousEnd = $currentStart;
        }
        
        $currentRevenue = Booking::where('status', 'confirmed')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->sum('total_amount');
        
        $previousRevenue = Booking::where('status', 'confirmed')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->sum('total_amount');
        
        $currentBookings = Booking::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $previousBookings = Booking::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        
        $revenueChange = $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 100;
        $bookingsChange = $previousBookings > 0 ? (($currentBookings - $previousBookings) / $previousBookings) * 100 : 100;
        
        return [
            'revenue' => [
                'current' => $currentRevenue,
                'previous' => $previousRevenue,
                'change' => round($revenueChange, 1)
            ],
            'bookings' => [
                'current' => $currentBookings,
                'previous' => $previousBookings,
                'change' => round($bookingsChange, 1)
            ]
        ];
    }
}