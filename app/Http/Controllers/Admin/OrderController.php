<?php
// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan untuk admin.
     */
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('user')
            // Cek apakah ada parameter status di URL
            ->when($request->filled('status'), function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString(); // PENTING: Supaya saat pindah halaman (pagination), filternya tidak hilang

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Detail order untuk admin.
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status pesanan
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Pastikan in: sesuai dengan yang ada di database kamu (shipped, delivered)
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Logika Restock jika pesanan dibatalkan
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', "Status pesanan #{$order->order_number} berhasil diubah menjadi " . ucfirst($newStatus));
    }
}