<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    // Tambahkan 'price' ke dalam fillable agar bisa disimpan lewat CartService
    protected $fillable = [
        'cart_id', 
        'product_id', 
        'quantity', 
        'price'
    ];

    /**
     * Casts untuk memastikan tipe data akurat saat perhitungan
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Relasi: Item ini bagian dari keranjang mana?
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Relasi: Item ini produknya apa?
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accessor: Hitung subtotal per item (harga * quantity)
     * Bisa kamu panggil di view keranjang dengan: {{ $item->subtotal }}
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Format Rupiah untuk subtotal
     */
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}