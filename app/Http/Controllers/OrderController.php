<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config; 
use Midtrans\Snap;   
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        $orders = auth()->user()->orders()->with(['items.product'])->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) { abort(403); }

        // SYNC STATUS MIDTRANS
        if ($order->payment_status !== 'paid') {
            try {
                $status = Transaction::status($order->order_number); 
                if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                    $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                }
            } catch (\Exception $e) {}
        }

        $order->load(['items.product']);
        $snapToken = $order->snap_token;

        if ($order->status === 'pending' && !$snapToken) {
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number, 
                    // total_amount di sini sudah harus hasil kalkulasi harga diskon
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
                \Log::error("Midtrans Error: " . $e->getMessage());
            }
        }

        return view('orders.show', compact('order', 'snapToken'));
    }
}