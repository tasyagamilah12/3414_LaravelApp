<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Menyimpan Ulasan dari Pembeli
     */
    public function create(Transaction $transaction)
    {
        // Validasi Pemilik Transaksi (sama seperti di store)
        if ($transaction->customer_email !== Auth::user()->email && $transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        $event = $transaction->event;
        $eventEndDate = Carbon::parse($event->date)->addDay()->startOfDay();
        $isEligible = now()->gte($eventEndDate);
        $alreadyReviewed = $transaction->review()->exists();

        return view('transactions.review', compact('transaction', 'event', 'isEligible', 'alreadyReviewed'));
    }

    public function store(Request $request, Transaction $transaction)
    {
        // 1. Validasi Pemilik Transaksi
        if ($transaction->customer_email !== Auth::user()->email && $transaction->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk memberikan ulasan pada transaksi ini.');
        }

        // 2. Validasi Status Transaksi Harus Sukses
        if (! in_array(strtolower($transaction->status), ['success', 'settlement'])) {
            return back()->with('error', 'Ulasan hanya dapat diberikan untuk transaksi yang telah berhasil.');
        }

        // 3. Validasi H+1 Acara Tuntas (Sesuai Ketentuan UAS)
        $event = $transaction->event;
        $eventEndDate = Carbon::parse($event->date)->addDay()->startOfDay();

        if (now()->lt($eventEndDate)) {
            return back()->with('error', 'Ulasan dan rating baru dapat diberikan sehari (H+1) setelah acara selesai.');
        }

        // 4. Validasi Apakah Sudah Pernah Mengulas
        if ($transaction->review()->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk transaksi ini.');
        }

        // 5. Validasi Input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // 6. Simpan Ulasan
        Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'organizer_id' => $event->user_id, // ID HIMA / Kepanitiaan
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
    }
}
