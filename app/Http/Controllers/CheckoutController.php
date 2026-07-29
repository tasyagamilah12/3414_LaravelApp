<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    /**
     * Tampilan form checkout
     */
    public function create(Event $event)
    {
        $categories = Category::all();

        return view('checkout.create', compact(
            'event',
            'categories'
        ));
    }

    /**
     * Memproses pendaftaran/pembelian tiket (Berbayar & Gratis)
     */
    public function store(Request $request, Event $event)
    {
        // 1. Validasi Stok Tiket
        if ($event->stock <= 0) {
            return back()->with('error', 'Maaf, stok tiket untuk event ini telah habis.');
        }

        // 2. Validasi Input Pembeli
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 3. Generate Order ID Unik
        $orderId = 'TRX-' . time() . '-' . strtoupper(Str::random(5));

        // ------------------------------------------------------------------
        // STEP 37: PERCABANGAN BIFURKASI (EVENT GRATIS / RP 0 VS BERBAYAR)
        // ------------------------------------------------------------------
        if ((int) $event->price === 0) {
            
            // Gunakan Database Transaction untuk menjamin integritas stok & data
            return DB::transaction(function () use ($request, $event, $orderId) {
                
                // A. Buat Transaksi Langsung Status SETTLEMENT/SUCCESS
                $transaction = Transaction::create([
                    'event_id'       => $event->id,
                    'user_id'        => Auth::id(), // Simpan ID User jika terautentikasi
                    'order_id'       => $orderId,
                    'customer_name'  => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'total_price'    => 0,
                    'payment_type'   => 'free_event', // Penanda metode gratis
                    'status'         => 'settlement', // Langsung lunas/berhasil
                    'snap_token'     => null,
                ]);

                // B. Potong Stok Tiket Saat Itu Juga
                $event->decrement('stock');

                // C. Bypass Midtrans, Redirect Langsung ke Halaman Success / E-Ticket
                return redirect()->route('checkout.success', $transaction->order_id)
                    ->with('success', 'Pendaftaran berhasil! E-Ticket gratis Anda telah diterbitkan.');
            });

        } else {

            // --------------------------------------------------------------
            // ALUR REGULER: TRANSAKSI BERBAYAR DENGAN MIDTRANS
            // --------------------------------------------------------------
            $totalPrice = $event->price;

            $transaction = Transaction::create([
                'event_id'       => $event->id,
                'user_id'        => Auth::id(),
                'order_id'       => $orderId,
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price'    => $totalPrice,
                'payment_type'   => 'pending',
                'status'         => 'pending',
            ]);

            // Konfigurasi Payload Midtrans Snap
            Config::$serverKey = env('MIDTRANS_SERVER_KEY', config('services.midtrans.server_key'));
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', config('services.midtrans.is_production', false));
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $transaction->order_id,
                    'gross_amount' => $transaction->total_price,
                ],
                'customer_details' => [
                    'first_name' => $transaction->customer_name,
                    'email'      => $transaction->customer_email,
                    'phone'      => $transaction->customer_phone,
                ],
                'item_details' => [
                    [
                        'id'       => $event->id,
                        'price'    => $event->price,
                        'quantity' => 1,
                        'name'     => Str::limit($event->title, 50),
                    ]
                ]
            ];

            try {
                // Generate Snap Token
                $snapToken = Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);

                return redirect()->route('checkout.payment', $transaction->order_id);

            } catch (\Exception $e) {
                return back()->with(
                    'error',
                    'Midtrans Error : ' . $e->getMessage()
                );
            }
        }
    }

    /**
     * Halaman pembayaran Midtrans
     */
    public function payment($order_id)
    {
        $categories = Category::all();

        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        return view(
            'checkout.payment',
            compact(
                'transaction',
                'categories'
            )
        );
    }

    /**
     * Halaman sukses / rincian E-Ticket
     */
    public function success($order_id)
    {
        $categories = Category::all();

        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        return view(
            'checkout.success',
            compact(
                'transaction',
                'categories'
            )
        );
    }
}