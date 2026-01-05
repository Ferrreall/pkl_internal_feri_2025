<?php
// app/Listeners/SendOrderPaidEmail.php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Mail\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue; // <--- PENTING
use Illuminate\Support\Facades\Mail;

class SendOrderPaidEmail implements ShouldQueue // <--- PENTING
{
    // Retry jika gagal
    public $tries = 3;

    public function handle(OrderPaidEvent $event): void
{
    // 1. Ambil data order terbaru beserta user-nya dari database
    // Ini krusial agar data user tidak 'null' saat diproses di background
    $order = $event->order->loadMissing('user');

    // 2. Cek apakah user benar-benar ada sebelum kirim email
    if ($order && $order->user) {
        Mail::to($order->user->email)
            ->send(new \App\Mail\OrderPaid($order));
    }
}
}