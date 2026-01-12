<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createSnapToken(Order $order): string
    {
        try {
            // Gabungan order_number dan timestamp agar ID unik di Midtrans
            $midtransOrderId = $order->order_number . '-' . time();

            // Simpan ke kolom midtrans_order_id (Pastikan kamu sudah buat kolom ini di tabel orders)
            $order->update([
                'midtrans_order_id' => $midtransOrderId
            ]);

            $params = [
                'transaction_details' => [
                    'order_id'     => $midtransOrderId,
                    'gross_amount' => (int) round($order->total_amount),
                ],
                'customer_details' => [
                    'first_name' => $order->user->name,
                    'email'      => $order->user->email,
                ],
            ];

            return Snap::getSnapToken($params);

        } catch (\Exception $e) {
            logger()->error('Midtrans Error: ' . $e->getMessage());
            throw new Exception('Gagal membuat transaksi: ' . $e->getMessage());
        }
    }
}