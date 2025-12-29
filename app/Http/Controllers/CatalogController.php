<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    /**
     * Menampilkan halaman catalog publik dengan fitur filter lengkap.
     * Logika filtering dibangun secara dinamis menggunakan chain method.
     */
   public function index(Request $request)
{
    // 1. BASE QUERY
    $query = Product::query()
        // OPTIMASI: Select kolom spesifik (hemat memori)
        ->select(['id', 'category_id', 'name', 'slug', 'price', 'discount_price', 'stock', 'is_active'])
        ->with(['category', 'primaryImage'])
        ->available();

    // 2. FILTERING PIPELINE (Tetap sama)
    if ($request->filled('q')) {
        $query->search($request->q);
    }

    if ($request->filled('category')) {
        $query->byCategory($request->category);
    }

    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // 3. SORTING LOGIC (Tetap sama)
    $sort = $request->get('sort', 'newest');
    $query->when($sort === 'price_asc', fn($q) => $q->orderBy('price', 'asc'))
          ->when($sort === 'price_desc', fn($q) => $q->orderBy('price', 'desc'))
          ->when($sort === 'name_asc', fn($q) => $q->orderBy('name', 'asc'))
          ->when($sort === 'name_desc', fn($q) => $q->orderBy('name', 'desc'))
          ->when($sort === 'newest', fn($q) => $q->latest());

    // 4. EXECUTE & PAGINATE
    $products = $query->paginate(12)->withQueryString();

    // 5. DATA PENDUKUNG VIEW (OPTIMASI CACHE)
    // Pindah ke Cache biar database gak kerja keras terus buat ambil list kategori
    $categories = Cache::remember('global_categories', 3600, function () {
        return Category::active()
            ->withCount(['products' => fn($q) => $q->available()])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();
    }); // <-- Pastikan ada tutup kurung & semicolon di sini

    $priceRange = Product::available()
        ->selectRaw('MIN(price) as min, MAX(price) as max')
        ->first();

    return view('catalog.index', compact('products', 'categories', 'priceRange'));
}

    /**
     * Menampilkan detail produk (Single Product Page).
     */
    public function show($slug)
    {
        // Cari produk berdasarkan SLUG, bukan ID (SEO Friendly).
        // PENTING: Gunakan scope available() agar user tidak bisa akses produk yang non-aktif via URL langsung.
        $product = Product::available()
            ->with(['category', 'images']) // Load semua gambar galeri
            ->where('slug', $slug)
            ->firstOrFail(); // 404 jika tidak ketemu

        return view('catalog.show', compact('product'));
    }
}