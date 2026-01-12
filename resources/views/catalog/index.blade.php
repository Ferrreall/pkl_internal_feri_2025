{{-- ================================================
     FILE: resources/views/catalog/index.blade.php
     FUNGSI: Halaman katalog dengan tema Orange Comic Style
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Katalog Produk')

@push('styles')
<style>
    /* Heroic Orange Palette */
    :root {
        --main-orange: #FF8F00;
        --dark-orange: #E67E00;
        --light-orange: #FFB300;
        --comic-black: #1A1A1A;
    }

    /* Background Halftone halus (CSS Only) */
    body {
        background-color: #fdfdfd;
        background-image: radial-gradient(var(--main-orange) 0.5px, transparent 0.5px);
        background-size: 24px 24px;
        background-attachment: fixed;
    }

    /* Comic Card Style */
    .comic-card {
        border: 3px solid var(--comic-black) !important;
        border-radius: 0px !important;
        box-shadow: 6px 6px 0px var(--comic-black);
        transition: 0.2s ease;
        background: white;
    }

    .comic-card:hover {
        transform: translate(-2px, -2px);
        box-shadow: 10px 10px 0px var(--main-orange);
    }

    /* Header Styling */
    .comic-header {
        background-color: var(--main-orange) !important;
        color: white;
        border-bottom: 3px solid var(--comic-black);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Heroic Button */
    .btn-heroic {
        background-color: var(--main-orange);
        color: white;
        border: 2px solid var(--comic-black);
        border-radius: 0px;
        font-weight: 900;
        text-transform: uppercase;
        box-shadow: 3px 3px 0px var(--comic-black);
        transition: 0.1s;
    }

    .btn-heroic:hover {
        background-color: var(--dark-orange);
        color: white;
        transform: translate(-1px, -1px);
        box-shadow: 5px 5px 0px var(--comic-black);
    }

    /* Typography */
    .catalog-title {
        font-weight: 900;
        color: var(--comic-black);
        text-transform: uppercase;
        position: relative;
        display: inline-block;
        z-index: 1;
    }

    .catalog-title::after {
        content: "";
        position: absolute;
        bottom: 5px;
        left: 0;
        width: 100%;
        height: 12px;
        background: var(--light-orange);
        z-index: -1;
        opacity: 0.6;
    }

    /* Custom Form Check */
    .form-check-input:checked {
        background-color: var(--main-orange);
        border-color: var(--comic-black);
    }

    .badge-heroic {
        background-color: var(--comic-black);
        color: white;
        border-radius: 0;
    }

    /* Pagination */
    .pagination .page-link {
        border: 2px solid var(--comic-black);
        color: var(--comic-black);
        font-weight: bold;
        padding: 6px 12px;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--main-orange);
        border-color: var(--comic-black);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- SIDEBAR FILTER --}}
        <div class="col-lg-3 mb-4">
            <div class="card comic-card">
                <div class="card-header comic-header">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel-fill me-2"></i>FILTER AMUNISI
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('catalog.index') }}" method="GET" id="filter-form">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        {{-- Filter Kategori --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-uppercase">Kategori</h6>
                            @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="category"
                                           id="cat-{{ $category->slug }}"
                                           value="{{ $category->slug }}"
                                           {{ request('category') == $category->slug ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label d-flex justify-content-between align-items-center"
                                           for="cat-{{ $category->slug }}">
                                        <span>{{ $category->name }}</span>
                                        <span class="badge badge-heroic">{{ $category->products_count }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Filter Harga --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-uppercase">Harga (Rp)</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number"
                                           class="form-control border-2"
                                           style="border-color: var(--comic-black)"
                                           name="min_price"
                                           placeholder="Min"
                                           value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number"
                                           class="form-control border-2"
                                           style="border-color: var(--comic-black)"
                                           name="max_price"
                                           placeholder="Max"
                                           value="{{ request('max_price') }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-heroic w-100 mt-3">
                                TERAPKAN
                            </button>
                        </div>

                        {{-- Reset Filter --}}
                        @if(request()->hasAny(['category', 'min_price', 'max_price', 'on_sale']))
                            <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-dark w-100 mt-2 fw-bold" style="border-radius: 0">
                                <i class="bi bi-x-circle me-1"></i> RESET FILTER
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="catalog-title mb-1">
                        @if(request('q'))
                            PENCARIAN: "{{ request('q') }}"
                        @elseif(request('category'))
                            KATEGORI: {{ $categories->firstWhere('slug', request('category'))?->name }}
                        @else
                            SEMUA PRODUK
                        @endif
                    </h2>
                    <p class="text-muted fw-bold mb-0">{{ $products->total() }} ITEM TERSEDIA</p>
                </div>
                <div class="d-flex align-items-center">
                    <label class="me-2 fw-bold text-uppercase small">Urutkan:</label>
                    <select class="form-select border-2" style="border-color: var(--comic-black); width: auto; border-radius: 0;"
                            onchange="window.location.href = this.value">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                                {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>TERBARU</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"
                                {{ request('sort') == 'price_asc' ? 'selected' : '' }}>TERMURAH</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}"
                                {{ request('sort') == 'price_desc' ? 'selected' : '' }}>TERMAHAL</option>
                    </select>
                </div>
            </div>

            {{-- Product Grid --}}
            @if($products->count())
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4">
                            {{-- Product card dipanggil dari partials --}}
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="display-1 text-muted mb-3"><i class="bi bi-emoji-frown"></i></div>
                    <h3 class="fw-900 text-uppercase">Kosong, Twin!</h3>
                    <p class="text-muted">Coba cari kata kunci lain atau reset filter.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-heroic px-4">KEMBALI KE KATALOG</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection