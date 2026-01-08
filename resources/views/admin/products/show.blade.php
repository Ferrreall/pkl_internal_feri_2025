@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0 fw-bold text-info">
                <i class="bi bi-eye me-1"></i> Detail Produk
            </h2>
            <div class="d-flex gap-2">
                {{-- Gunakan parameter ID secara eksplisit untuk keamanan routing --}}
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning text-white shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- ================= IMAGES ================= --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3 text-center">

                        {{-- Primary Image dengan Fallback --}}
                        <div class="rounded mb-3 border overflow-hidden bg-light" style="height:320px;">
                            <img src="{{ $product->image_url }}" 
                                 class="w-100 h-100" 
                                 style="object-fit:contain;"
                                 onerror="this.src='{{ asset('img/no-image.png') }}'">
                        </div>

                        {{-- Gallery --}}
                        <div class="row g-2">
                            @forelse($product->images as $image)
                            <div class="col-4">
                                <div class="rounded border overflow-hidden bg-light" style="height:90px;">
                                    <img src="{{ asset('storage/'.$image->image_path) }}" 
                                         class="w-100 h-100" 
                                         style="object-fit:cover;"
                                         onerror="this.src='{{ asset('img/no-image.png') }}'">
                                </div>
                            </div>
                            @empty
                            <div class="col-12"><small class="text-muted">Tidak ada galeri foto.</small></div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

            {{-- ================= PRODUCT INFO ================= --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-1 text-dark">
                            {{ $product->name }}
                        </h4>

                        <p class="text-muted mb-3">
                            <i class="bi bi-tags me-1"></i>
                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                            <span class="ms-2 text-dark small fw-bold">SKU: PROD-{{ $product->id }}</span>
                        </p>

                        {{-- Price - Perbaikan Logika Null --}}
                        <div class="mb-4">
                            @php
                                $mainPrice = $product->price ?? 0;
                                $discountPrice = $product->discount_price ?? 0;
                            @endphp

                            @if($discountPrice > 0)
                                <h3 class="text-primary fw-bold mb-0">
                                    Rp {{ number_format($discountPrice, 0, ',', '.') }}
                                </h3>
                                <div class="text-muted">
                                    <span class="text-decoration-line-through">Rp {{ number_format($mainPrice, 0, ',', '.') }}</span>
                                    <span class="badge bg-danger ms-2">Diskon</span>
                                </div>
                            @else
                                <h3 class="text-primary fw-bold">
                                    Rp {{ number_format($mainPrice, 0, ',', '.') }}
                                </h3>
                            @endif
                        </div>

                        {{-- Status --}}
                        <div class="mb-4 d-flex gap-2">
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }} px-3 py-2">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>

                            @if($product->is_featured)
                            <span class="badge bg-warning text-dark px-3 py-2">
                                <i class="bi bi-star-fill me-1"></i> Unggulan
                            </span>
                            @endif
                        </div>

                        <h6 class="fw-bold">Deskripsi Produk</h6>
                        <p class="text-muted mb-4" style="white-space: pre-line;">
                            {{ $product->description ?: 'Tidak ada deskripsi.' }}
                        </p>

                        <hr>

                        {{-- Meta Info --}}
                        <div class="row text-center text-md-start">
                            <div class="col-md-4 mb-3">
                                <label class="text-muted d-block small">Stok</label>
                                <span class="fw-bold fs-5">{{ $product->stock ?? 0 }}</span>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="text-muted d-block small">Berat</label>
                                <span class="fw-bold fs-5">{{ $product->weight ?? 0 }} <small>gram</small></span>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="text-muted d-block small">Dibuat Pada</label>
                                <span class="fw-bold">{{ $product->created_at ? $product->created_at->format('d M Y') : '-' }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection