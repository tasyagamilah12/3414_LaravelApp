<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('event')
            ->latest()
            ->paginate(20);

        return view(
            'admin.transactions.index',
            compact('transactions')
        );
    }
}