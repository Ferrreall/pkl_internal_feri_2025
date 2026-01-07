<?php
// app/Services/OrderService.php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Membuat Order baru dari Keranjang belanja.
     * Sudah mendukung Harga Diskon dari tabel Cart Items.
     */
    public function createOrder(User $user, array $shippingData): Order
    {
        // 1. Ambil Keranjang User secara manual agar PASTI dapat data terbaru
        // Kita eager load 'items.product' untuk keperluan validasi stok & nama produk
        $cart = \App\Models\Cart::where('user_id', $user->id)
                    ->with('items.product')
                    ->first();

        // Validasi awal sebelum masuk transaksi
        if (!$cart || $cart->items->count() === 0) {
            throw new \Exception("Keranjang belanja kosong.");
        }

        return DB::transaction(function () use ($user, $cart, $shippingData) {

            // A. VALIDASI STOK & HITUNG TOTAL BERDASARKAN HARGA DISKON
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                // Validasi Stok
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception("Stok produk {$item->product->name} tidak mencukupi.");
                }
                
                // FIX: Gunakan $item->price (Harga Diskon di Cart), bukan $item->product->price
                $totalAmount += $item->price * $item->quantity;
            }

            // B. BUAT HEADER ORDER
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_name' => $shippingData['name'],
                'shipping_address' => $shippingData['address'],
                'shipping_phone' => $shippingData['phone'],
                'total_amount' => $totalAmount, // Sudah harga diskon
            ]);

            // C. PINDAHKAN ITEMS (Snapshot Harga Diskon)
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    // FIX: Simpan harga diskon ke riwayat pesanan
                    'price' => $item->price, 
                    'quantity' => $item->quantity,
                    'subtotal' => $item->price * $item->quantity,
                ]);

                // D. KURANGI STOK (ATOMIC)
                $item->product->decrement('stock', $item->quantity);
            }

            // E. BERSIHKAN KERANJANG
            // Agar tidak error "pending" seperti dulu, kita pastikan data item dihapus
            // dan total_price di tabel carts di-reset jadi 0.
            $cart->items()->delete();
            $cart->update(['total_price' => 0]);

            return $order;
        });
    }
}