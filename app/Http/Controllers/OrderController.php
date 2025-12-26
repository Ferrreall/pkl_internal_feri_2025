<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        // Pastikan hanya pemilik order yang bisa melihat
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Eager load items dan produknya agar tidak error di view
        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}