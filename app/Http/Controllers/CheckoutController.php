<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = Category::all();

        return view('checkout.create', compact(
            'event',
            'categories'
        ));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cek stok sebelum checkout
        |--------------------------------------------------------------------------
        */
        if ($event->stock <= 0) {
            return back()->with(
                'error',
                'Maaf, tiket event ini sudah habis.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Order ID
        |--------------------------------------------------------------------------
        */
        $orderId = 'TRX-' . time() . '-' . strtoupper(Str::random(5));

        /*
        |--------------------------------------------------------------------------
        | Hitung total pembayaran
        |--------------------------------------------------------------------------
        */
        $totalPrice = $event->price + 5000;

        /*
        |--------------------------------------------------------------------------
        | Simpan transaksi
        |--------------------------------------------------------------------------
        */
        $transaction = Transaction::create([
            'event_id'        => $event->id,
            'order_id'        => $orderId,
            'customer_name'   => $request->customer_name,
            'customer_email'  => $request->customer_email,
            'customer_phone'  => $request->customer_phone,
            'total_price'     => $totalPrice,
            'status'          => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Konfigurasi Midtrans
        |--------------------------------------------------------------------------
        */
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [

            'transaction_details' => [

                'order_id' => $orderId,
                'gross_amount' => $totalPrice,

            ],

            'customer_details' => [

                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,

            ],

        ];

        try {

            $snapToken = Snap::getSnapToken($params);

            $transaction->update([
                'snap_token' => $snapToken
            ]);

            return redirect()->route(
                'checkout.payment',
                $transaction->order_id
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Midtrans Error : ' . $e->getMessage()
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Halaman pembayaran
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | Halaman sukses
    |--------------------------------------------------------------------------
    | Halaman ini hanya menampilkan informasi pembayaran.
    | Status transaksi, pengurangan stok dan pengiriman email
    | ditangani oleh MidtransWebhookController.
    |--------------------------------------------------------------------------
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