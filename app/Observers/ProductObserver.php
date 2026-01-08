<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Handle the Product "saving" event.
     * Terpanggil OTOMATIS saat Create & Update (sebelum data masuk ke DB)
     */
    public function saving(Product $product): void
    {
        // 1. Logika Hitung Persentase Diskon Otomatis
        if ($product->discount_price && $product->discount_price > 0 && $product->price > 0) {
            $discountValue = $product->price - $product->discount_price;
            $percentage = ($discountValue / $product->price) * 100;
            $product->discount_percentage = (int) round($percentage);
        } else {
            $product->discount_percentage = 0;
        }

        // 2. Logika Slug Otomatis (Jika Nama Berubah atau Slug Kosong)
        if ($product->isDirty('name') || empty($product->slug)) {
            $baseSlug = Str::slug($product->name);
            $slug = $baseSlug;
            $counter = 1;

            // Cek biar gak bentrok sama slug produk lain
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $product->slug = $slug;
        }
    }

    public function created(Product $product): void
    {
        Cache::forget('featured_products');
        Cache::forget('category_' . $product->category_id . '_products');

        activity()
            ->performedOn($product)
            ->causedBy(auth()->user())
            ->log('Produk baru dibuat: ' . $product->name);
    }

    public function updated(Product $product): void
    {
        Cache::forget('product_' . $product->id);
        Cache::forget('featured_products');

        if ($product->isDirty('category_id')) {
            Cache::forget('category_' . $product->getOriginal('category_id') . '_products');
            Cache::forget('category_' . $product->category_id . '_products');
        }
    }

    public function deleted(Product $product): void
    {
        Cache::forget('product_' . $product->id);
        Cache::forget('featured_products');
        Cache::forget('category_' . $product->category_id . '_products');
    }
}