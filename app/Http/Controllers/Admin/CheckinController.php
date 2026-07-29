<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    /**
     * Menampilkan Halaman HTML5 QR Scanner Panitia
     */
    public function index()
    {
        return view('admin.checkin.index');
    }

    /**
     * Memproses Validasi QR Code / Order ID dari Scanner
     */
    public function process(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $transaction = Transaction::with('event')
            ->where('order_id', trim($request->order_id))
            ->first();

        // 1. Cek Apakah Tiket Ditemukan
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket TIDAK DITEMUKAN / Kode Invalid!'
            ], 404);
        }

        // 2. Cek Status Pembayaran/Pendaftaran
        if (!in_array(strtolower($transaction->status), ['success', 'settlement'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket INVALID! Status transaksi masih ' . strtoupper($transaction->status)
            ], 400);
        }

        // 3. Cek Apakah Tiket Sudah Pernah Dipakai (Mencegah Double Entry)
        if ($transaction->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'PERINGATAN! Tiket ini SUDAH DIGUNAKAN pada ' . \Carbon\Carbon::parse($transaction->used_at)->format('H:i:s d M Y'),
                'customer' => $transaction->customer_name,
                'event' => $transaction->event->title ?? '-'
            ], 422);
        }

        // 4. Ubah Status Tiket Menjadi "USED"
        $transaction->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'CHECK-IN BERHASIL! Silakan Masuk.',
            'customer' => $transaction->customer_name,
            'email'    => $transaction->customer_email,
            'event'    => $transaction->event->title ?? '-',
            'time'     => now()->format('H:i:s')
        ]);
    }
}