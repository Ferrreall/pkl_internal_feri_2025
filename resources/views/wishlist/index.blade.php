@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">Wishlist Saya</h1>
        <span class="badge bg-primary px-3 py-2">
            <span id="wishlist-total-count">{{ $products->total() }}</span> Barang
        </span>
    </div>

    @if($products->count())
        <div class="row row-cols-2 row-cols-md-4 g-4" id="wishlist-grid">
            @foreach($products as $product)
                {{-- Tambah ID di kolom biar bisa kita hapus dari layar pakai JS --}}
                <div class="col" id="product-col-{{ $product->id }}">
                    <div class="position-relative">
                        <x-product-card :product="$product" />

                        {{-- 
                            GANTI FORM JADI BUTTON BIASA 
                            Kita panggil fungsi yang ada di app.blade
                        --}}
                        <button type="button" 
                                onclick="removeFromWishlist({{ $product->id }})" 
                                class="btn btn-danger btn-sm shadow-sm position-absolute top-0 end-0 m-2" 
                                style="border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" 
                                title="Hapus dari Wishlist">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4" id="pagination-wrapper">
            {{ $products->links() }}
        </div>
    @else
        {{-- Tampilan kalau wishlist kosong --}}
        <div class="text-center py-5 bg-light rounded-3 shadow-sm">
            <div class="mb-3">
                <i class="bi bi-heart text-secondary" style="font-size: 4rem;"></i>
            </div>
            <h3 class="h5 fw-medium text-dark">Wishlist Kosong</h3>
            <p class="text-muted mt-1">Simpan produk yang kamu suka di sini.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary mt-3 px-4">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    /**
     * Fungsi khusus untuk halaman Index Wishlist
     * Biar setelah hapus, kartu produknya langsung hilang (animasi)
     */
    async function removeFromWishlist(productId) {
        // 1. Jalankan fungsi toggle yang ada di master layout (app.blade)
        // Ini akan tetap mengirim request ke WishlistController@toggle
        await toggleWishlist(productId);
        
        // 2. Animasi menghapus element dari layar
        const productCol = document.getElementById(`product-col-${productId}`);
        if (productCol) {
            productCol.style.transition = "all 0.4s ease";
            productCol.style.opacity = "0";
            productCol.style.transform = "scale(0.8)";
            
            setTimeout(() => {
                productCol.remove();
                
                // 3. Update total text di header
                const totalSpan = document.getElementById('wishlist-total-count');
                if(totalSpan) {
                    let currentTotal = parseInt(totalSpan.innerText);
                    totalSpan.innerText = currentTotal - 1;
                    
                    // 4. Kalau barang habis, reload biar muncul tampilan "Wishlist Kosong"
                    if (currentTotal - 1 <= 0) {
                        location.reload();
                    }
                }
            }, 400);
        }
    }
</script>
@endpush