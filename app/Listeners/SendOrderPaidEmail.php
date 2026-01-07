<?php
// app/Listeners/SendOrderPaidEmail.php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Mail\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue; // <--- PENTING
use Illuminate\Support\Facades\Mail;

class SendOrderPaidEmail // Hapus implements ShouldQueue
{
    public function handle(OrderPaidEvent $event): void
    {
        $order = $event->order->loadMissing('user');

        if ($order && $order->user) {
            Mail::to($order->user->email)
                ->send(new \App\Mail\OrderPaid($order));
        }
    }
}
