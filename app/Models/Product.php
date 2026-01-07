<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'weight',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function firstImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->oldestOfMany('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistedBy(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // ==================== ACCESSORS (LOGIKA HARGA DISKON) ====================

    /**
     * Accessor: HARGA UTAMA UNTUK TRANSAKSI
     * Digunakan oleh CartService & OrderController agar harga otomatis diskon.
     * $product->display_price
     */
    public function getDisplayPriceAttribute(): float
    {
        // Jika ada discount_price, harganya di atas 0, dan lebih kecil dari harga asli
        if ($this->discount_price !== null && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return (float) $this->discount_price;
        }
        
        // Jika tidak ada diskon, balik ke harga normal
        return (float) $this->price;
    }

    /**
     * Menampilkan harga dengan format Rp (Contoh: Rp 1.500.000)
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->display_price, 0, ',', '.');
    }

    /**
     * Menampilkan harga asli yang dicoret jika sedang diskon
     */
    public function getFormattedOriginalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Cek apakah produk sedang diskon atau tidak
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price !== null
            && $this->discount_price > 0
            && $this->discount_price < $this->price;
    }

    /**
     * Menghitung persentase diskon
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->has_discount) {
            return 0;
        }

        $discountValue = $this->price - $this->discount_price;
        return (int) round(($discountValue / $this->price) * 100);
    }

    /**
     * Logika ambil Gambar (Primary -> First -> Placeholder)
     */
    public function getImageUrlAttribute(): string
    {
        $image = $this->primaryImage ?? $this->firstImage ?? $this->images->first();

        if ($image) {
            return $image->image_url;
        }

        return asset('images/furin.jpg');
    }

    /**
     * Label Stok
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    public function getStockLabelAttribute(): string
    {
        if ($this->stock <= 0) return 'Habis';
        if ($this->stock <= 5) return 'Sisa ' . $this->stock;
        return 'Tersedia';
    }

    public function getStockBadgeColorAttribute(): string
    {
        if ($this->stock <= 0) return 'danger';
        if ($this->stock <= 5) return 'warning';
        return 'success';
    }

    public function getFormattedWeightAttribute(): string
    {
        if ($this->weight >= 1000) {
            return number_format($this->weight / 1000, 1) . ' kg';
        }
        return $this->weight . ' gram';
    }

    // ==================== QUERY SCOPES ====================

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopeInStock($query) { return $query->where('stock', '>', 0); }

    public function scopeAvailable($query)
    {
        return $query->active()->inStock();
    }

    public function scopeByCategory($query, string $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('discount_price')
                     ->whereColumn('discount_price', '<', 'price');
    }

    public function scopeSortBy($query, ?string $sort)
    {
        return match ($sort) {
            'newest' => $query->latest(),
            'oldest' => $query->oldest(),
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'popular' => $query->withCount('orderItems')->orderByDesc('order_items_count'),
            default => $query->latest(),
        };
    }

    // ==================== BOOT METHOD ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $product->slug = $slug;
            }
        });
    }

    // ==================== HELPER METHODS ====================

    public function decrementStock(int $quantity): bool
    {
        if ($this->stock < $quantity) return false;
        $this->decrement('stock', $quantity);
        return true;
    }

    public function incrementStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }
}