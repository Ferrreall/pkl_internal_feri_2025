<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Tampilkan semua daftar pesanan milik user yang sedang login
     */
    public function index()
    {
        // Mengambil pesanan milik user, diurutkan dari yang terbaru
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan tunggal
     */
    public function show(Order $order)
    {
        // 🔐 Security check: Pastikan user cuma bisa lihat pesanan dia sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Refresh data biar statusnya paling update dari database
        $order->refresh();

        return view('orders.show', [
            'order' => $order
        ]);
    }
}