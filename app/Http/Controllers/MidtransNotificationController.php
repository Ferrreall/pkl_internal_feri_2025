<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Events\OrderPaidEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $payload = $request->all();
            $midtransOrderId = $payload['order_id'];
            $transactionStatus = $payload['transaction_status'];

            Log::info('WEBHOOK MASUK: ' . $midtransOrderId . ' Status: ' . $transactionStatus);

            // 1. Cari Order
            $order = Order::where('midtrans_order_id', $midtransOrderId)->first();
            if (!$order) {
                $cleanId = preg_replace('/-\d+$/', '', $midtransOrderId);
                $order = Order::where('order_number', $cleanId)->first();
            }

            if ($order) {
                // 2. Jika Berhasil (Settlement/Capture)
                if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                    
                    $order->update([
                        'status' => 'processing',    // Sesuai enum awal kamu biar sinkron
                        'payment_status' => 'paid',   // Kolom status bayar jadi lunas
                    ]);

                    // 3. Potong Stok
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->decrement('stock', $item->quantity);
                        }
                    }

                    event(new OrderPaidEvent($order));
                    Log::info('PEMBAYARAN SUKSES: Order #' . $order->order_number);
                }
            }

            return response()->json(['message' => 'Notification Handled']);

        } catch (\Exception $e) {
            Log::error('MPC ERROR: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}