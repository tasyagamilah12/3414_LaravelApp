<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pendapatan dari transaksi yang sudah dibayar
        $totalRevenue = Transaction::whereIn('status', [
            'settlement',
            'success'
        ])->sum('total_price');

        // Jumlah tiket terjual
        $ticketsSold = Transaction::whereIn('status', [
            'settlement',
            'success'
        ])->count();

        // Event yang masih aktif
        $activeEvents = Event::where('date', '>=', now())->count();

        // Pesanan yang masih pending
        $pendingOrders = Transaction::where('status', 'pending')->count();

        // 5 transaksi terbaru
        $recentTransactions = Transaction::with('event')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions'
        ));
    }
}