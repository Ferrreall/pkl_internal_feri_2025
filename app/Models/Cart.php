<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id'];

    // Relasi: Satu keranjang punya banyak item produk
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Relasi: Keranjang ini milik siapa? (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}