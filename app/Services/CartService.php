<?php
// app/Services/CartService.php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Mendapatkan (atau membuat) keranjang untuk user saat ini.
     * Mendukung Guest (Session) dan Member (Auth).
     */
    public function getCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = Session::getId();
            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }

    /**
     * Menambahkan produk ke keranjang dengan LOGIKA DISKON.
     */
    public function addProduct(Product $product, int $quantity = 1): void
    {
        $cart = $this->getCart();

        // Cari apakah produk sudah ada di keranjang?
        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;

            if ($newQuantity > $product->stock) {
                throw new \Exception("Stok tidak mencukupi. Maksimal: {$product->stock}");
            }

            // UPDATE: Kita update juga harganya ke harga diskon terbaru (jika ada)
            $existingItem->update([
                'quantity' => $newQuantity,
                'price' => $product->display_price // MENGGUNAKAN DISKON
            ]);
        } else {
            if ($quantity > $product->stock) {
                throw new \Exception("Stok tidak mencukupi.");
            }

            // PENTING: Masukkan 'price' dengan harga diskon (display_price)
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->display_price, // HARGA DISKON MASUK DISINI
            ]);
        }

        $cart->touch();
        $this->refreshCartTotal($cart);
    }

    /**
     * Mengupdate jumlah item & hitung ulang harga.
     */
    public function updateQuantity(int $itemId, int $quantity): void
    {
        $item = CartItem::findOrFail($itemId);
        $product = $item->product;

        $this->verifyCartOwnership($item->cart);

        if ($quantity > $product->stock) {
            throw new \Exception("Stok tidak mencukupi. Tersisa: {$product->stock}");
        }

        if ($quantity <= 0) {
            $item->delete();
        } else {
            $item->update([
                'quantity' => $quantity,
                'price' => $product->display_price // Pastikan harga tetap harga diskon
            ]);
        }

        $this->refreshCartTotal($item->cart);
    }

    /**
     * Menghapus item & hitung ulang total.
     */
    public function removeItem(int $itemId): void
    {
        $item = CartItem::findOrFail($itemId);
        $cart = $item->cart;

        $this->verifyCartOwnership($cart);

        $item->delete();
        $this->refreshCartTotal($cart);
    }

    /**
     * LOGIKA BARU: Menghitung total belanja yang sudah dipotong diskon.
     * Ini yang akan dikirim ke Checkout & Midtrans.
     */
    public function refreshCartTotal(Cart $cart)
    {
        $total = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $cart->update(['total_price' => $total]);
        return $total;
    }

    /**
     * Menggabungkan keranjang Guest ke User saat Login.
     */
    public function mergeCartOnLogin(): void
    {
        $sessionId = Session::getId();
        $guestCart = Cart::where('session_id', $sessionId)->with('items')->first();

        if (!$guestCart) return;

        $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($guestCart->items as $item) {
            $existingUserItem = $userCart->items()
                ->where('product_id', $item->product_id)
                ->first();

            if ($existingUserItem) {
                $existingUserItem->increment('quantity', $item->quantity);
                // Pastikan harga update ke harga terbaru
                $existingUserItem->update(['price' => $item->product->display_price]);
            } else {
                $item->update([
                    'cart_id' => $userCart->id,
                    'price' => $item->product->display_price
                ]);
            }
        }

        $this->refreshCartTotal($userCart);
        $guestCart->delete();
    }

    private function verifyCartOwnership(Cart $cart): void
    {
        $currentCart = $this->getCart();
        if ($cart->id !== $currentCart->id) {
            abort(403, 'Akses ditolak.');
        }
    }
}