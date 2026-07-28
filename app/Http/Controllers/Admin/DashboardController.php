<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $currentYear = date('Y');
        $period = $request->get('period', 'this_year');

        // Scope berdasarkan Role (Admin vs Organizer)
        $eventIds = $isAdmin 
            ? null 
            : Event::where('user_id', $user->id)->pluck('id');

        $baseTransactionQuery = Transaction::query()
            ->when(!$isAdmin, function ($q) use ($eventIds) {
                $q->whereIn('event_id', $eventIds);
            });

        // Closure Filter Periode (STEP 27)
        $applyPeriodFilter = function ($query) use ($period) {
            return match ($period) {
                'today'      => $query->whereDate('created_at', now()->today()),
                'this_week'  => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                'this_month' => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
                'this_year'  => $query->whereYear('created_at', now()->year),
                default      => $query->whereYear('created_at', now()->year),
            };
        };

        // 1. Metrics (Filtered)
        $totalRevenue = $applyPeriodFilter(clone $baseTransactionQuery)
            ->whereIn('status', ['settlement', 'success'])
            ->sum('total_price');

        $ticketsSold = $applyPeriodFilter(clone $baseTransactionQuery)
            ->whereIn('status', ['settlement', 'success'])
            ->count();

        $activeEvents = Event::query()
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->where('date', '>=', now())
            ->count();

        $pendingOrders = $applyPeriodFilter(clone $baseTransactionQuery)
            ->where('status', 'pending')
            ->count();

        // 2. Transaksi Terbaru
        $recentTransactions = (clone $baseTransactionQuery)
            ->with('event')
            ->latest()
            ->take(5)
            ->get();

        // 3. Revenue Bulanan (12 Bulan)
        $monthlyRevenue = array_fill(1, 12, 0);
        $rawMonthlyRevenue = (clone $baseTransactionQuery)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->whereIn('status', ['success', 'settlement'])
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('revenue', 'month')
            ->toArray();

        foreach ($rawMonthlyRevenue as $month => $amount) {
            $monthlyRevenue[$month] = (float) $amount;
        }

        // 4. STEP 26 — Volume Penjualan Tiket per Bulan (12 Bulan)
        $monthlyTicketSales = array_fill(1, 12, 0);
        $rawTicketSales = (clone $baseTransactionQuery)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(id) as total_tickets')
            )
            ->whereIn('status', ['success', 'settlement'])
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_tickets', 'month')
            ->toArray();

        foreach ($rawTicketSales as $month => $count) {
            $monthlyTicketSales[$month] = (int) $count;
        }

        // 5. Rasio Status Transaksi
        $statusCounts = $applyPeriodFilter(clone $baseTransactionQuery)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusData = [
            'success' => ($statusCounts['success'] ?? 0) + ($statusCounts['settlement'] ?? 0),
            'pending' => $statusCounts['pending'] ?? 0,
            'failed'  => ($statusCounts['cancel'] ?? 0) + ($statusCounts['deny'] ?? 0) + ($statusCounts['expire'] ?? 0),
        ];

        // 6. Revenue per Event (Top 10)
        $revenuePerEvent = Transaction::select(
                'events.title as event_title',
                DB::raw('SUM(transactions.total_price) as total_revenue')
            )
            ->join('events', 'events.id', '=', 'transactions.event_id')
            ->whereIn('transactions.status', ['success', 'settlement'])
            ->when(!$isAdmin, fn($q) => $q->whereIn('transactions.event_id', $eventIds))
            ->groupBy('events.id', 'events.title')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions',
            'monthlyRevenue',
            'monthlyTicketSales',
            'statusData',
            'revenuePerEvent',
            'period'
        ));
    }
}