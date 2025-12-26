<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Tambahkan baris ini supaya data dari OrderService bisa masuk ke database
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_status',
        'shipping_name',
        'shipping_address',
        'shipping_phone',
        'total_amount',
    ];

    // Relasi ke User (Opsional tapi berguna)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Item Pesanan
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
 * Accessor untuk warna badge status
 * Digunakan di dashboard: bg-{{ $order->status_color }}
 */
public function getStatusColorAttribute()
{
    return [
        'pending'    => 'warning',   // Kuning
        'processing' => 'info',      // Biru Muda
        'shipped'    => 'primary',   // Biru
        'delivered'  => 'success',   // Hijau
        'cancelled'  => 'danger',    // Merah
    ][$this->status] ?? 'secondary'; // Abu-abu jika tidak cocok
}
}