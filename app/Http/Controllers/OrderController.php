<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config; 
use Midtrans\Snap;   

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    // Ambil dari MODUL: Untuk menampilkan daftar semua pesanan user
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product']) 
            ->latest() 
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // GABUNGAN: Logika Modul + Logika Midtrans kita
   public function show(Order $order)
    {
        // 1. Security Check
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // ---------------------------------------------------------
        // KODE "JALAN PINTAS" (Hapus bagian ini kalau sudah selesai demo/sidang)
        // Kalau lo buka halaman ini, Laravel bakal paksa status jadi PAID & kirim email.
        if ($order->payment_status !== 'paid') {
            $order->update(['payment_status' => 'paid']);
            
            // Memicu Event yang bakal ngirim email ke Mailtrap
            event(new \App\Events\OrderPaidEvent($order));
        }
        // ---------------------------------------------------------

        $order->load(['items.product']);

        // Kita biarkan logika Midtrans tetap ada di bawah biar kodingan lo nggak rusak
        $snapToken = $order->snap_token;
        if ($order->status === 'pending' && !$snapToken) {
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number . '-' . time(), 
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'phone' => $order->shipping_phone,
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                // Biarkan saja, karena kita sudah "paksa" paid di atas
            }
        }

        return view('orders.show', compact('order', 'snapToken'));
    }
}