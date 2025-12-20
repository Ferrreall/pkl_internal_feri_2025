<?php


namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::query()
            ->active()
            ->withCount(['activeProducts' => function($q) {
            //   ['activeProducts' => function($q)] = custom count dengan kondisi
                $q->where('is_active', true)
                  ->where('stock', '>', 0);
            }])

            ->having('active_products_count', '>', 0)
            ->orderBy('name')
            ->take(6)
            ->get();
            // ↑ EKSEKUSI QUERY dan ambil hasilnya
            //   Return: Collection berisi object Category
            //
            //   QUERY SQL LENGKAP:
            //   SELECT categories.*,
            //          (SELECT COUNT(*) FROM products
            //           WHERE products.category_id = categories.id
            //           AND is_active = 1 AND stock > 0) as active_products_count
            //   FROM categories
            //   WHERE is_active = 1
            //   HAVING active_products_count > 0
            //   ORDER BY name ASC
            //   LIMIT 6

        $featuredProducts = Product::query()
            ->with(['category', 'primaryImage'])
            // ↑ EAGER LOADING - SANGAT PENTING UNTUK PERFORMA!
            //
            //   MASALAH N+1 QUERY:
            //   Tanpa with(), jika kita punya 8 produk dan loop:
            //   @foreach($products as $p) {{ $p->category->name }} @endforeach
            //
            //   Akan terjadi:
            //   1 query ambil products
            //   + 8 query ambil category (1 per produk)
            //   + 8 query ambil image (1 per produk)
            //   = 17 query total!
            //
            //   DENGAN with():
            //   1 query: SELECT * FROM products WHERE ...
            //   1 query: SELECT * FROM categories WHERE id IN (1,2,3...)
            //   1 query: SELECT * FROM product_images WHERE product_id IN (1,2...) AND is_primary = 1
            //   = 3 query saja! Jauh lebih cepat!

            ->active()
            // ↑ Scope: WHERE is_active = true

            ->inStock()
            // ↑ Scope: WHERE stock > 0
            //   Produk yang stoknya habis tidak ditampilkan

            ->featured()
            // ↑ Scope: WHERE is_featured = true
            //   Produk yang di-flag featured oleh admin

            ->latest()
            // ↑ ORDER BY created_at DESC
            //   Tampilkan yang terbaru duluan

            ->take(8)
            // ↑ LIMIT 8 produk
            //   8 = 2 baris x 4 kolom di desktop

            ->get();
            // ↑ Eksekusi dan ambil hasil

        // ============================================================
        // STEP 3: AMBIL PRODUK TERBARU (LATEST PRODUCTS)
        // ============================================================

        $latestProducts = Product::query()
            ->with(['category', 'primaryImage'])
            ->active()
            ->inStock()
            // Tidak pakai ->featured() karena kita mau semua produk,
            // bukan hanya yang featured
            ->latest()
            // ↑ Urutkan dari yang paling baru
            ->take(8)
            ->get();

        // ============================================================
        // STEP 4: KIRIM DATA KE VIEW (BLADE)
        // ============================================================

        return view('home', compact(
            'categories',
            'featuredProducts',
            'latestProducts'
        ));
        // ↑ PENJELASAN:
        //
        //   view('home', [...]) artinya:
        //   - Cari file: resources/views/home.blade.php
        //   - Kirim data ke file tersebut
        //
        //   compact('categories', 'featuredProducts', 'latestProducts')
        //   adalah shortcut untuk:
        //   [
        //       'categories' => $categories,
        //       'featuredProducts' => $featuredProducts,
        //       'latestProducts' => $latestProducts,
        //   ]
        //
        //   Di dalam view, sekarang kita bisa akses:
        //   $categories, $featuredProducts, $latestProducts
    }
}
