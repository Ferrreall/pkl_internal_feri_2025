<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function index(Request $request): View
    {
        // HAPUS withTrashed() di sini agar produk yang dihapus otomatis hilang dari daftar
        $products = Product::query()
            ->with(['category', 'primaryImage'])
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $product = Product::create($request->validated());
            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }
            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // Menampilkan detail produk yang AKTIF saja
    public function show(Product $product): View
    {
        $product->load(['category', 'images', 'orderItems']);
        return view('admin.products.show', compact('product'));
    }

    // Mengedit produk yang AKTIF saja
    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $product->update($request->validated());

            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }
            if ($request->has('delete_images')) {
                $this->deleteImages($request->delete_images);
            }
            if ($request->has('primary_image')) {
                $this->setPrimaryImage($product, $request->primary_image);
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    // FUNGSI HAPUS: Silakan pilih salah satu logic di bawah
    public function destroy(Product $product): RedirectResponse
    {
        try {
            // OPSI A: Tetap Soft Delete tapi otomatis hilang dari daftar (karena index tidak pakai withTrashed)
            $product->delete(); 

            // OPSI B: Jika ingin hapus TOTAL dari database & hapus gambar permanen, gunakan:
            // $this->deleteImages($product->images->pluck('id')->toArray());
            // $product->forceDelete();

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // --- Helper Methods ---
    protected function uploadImages(array $files, Product $product): void
    {
        $isFirst = $product->images()->count() === 0;
        foreach ($files as $index => $file) {
            $filename = 'product-' . $product->id . '-' . time() . '-' . $index . '.' . $file->extension();
            $path = $file->storeAs('products', $filename, 'public');
            $product->images()->create([
                'image_path' => $path,
                'is_primary' => $isFirst && $index === 0,
                'sort_order' => $product->images()->count() + $index,
            ]);
        }
    }

    protected function deleteImages(array $imageIds): void
    {
        $images = ProductImage::whereIn('id', $imageIds)->get();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }

    protected function setPrimaryImage(Product $product, int $imageId): void
    {
        $product->images()->update(['is_primary' => false]);
        $product->images()->where('id', $imageId)->update(['is_primary' => true]);
    }
}