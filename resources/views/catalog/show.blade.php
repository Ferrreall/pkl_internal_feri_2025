@extends('layouts.app')

@section('title', $product->name)

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    /* 1. Perbaikan Breadcrumb sesuai request */
    .breadcrumb-item a {
        color: #6c757d !important; 
        text-decoration: none;
        transition: color 0.2s;
    }
    .breadcrumb-item a:hover {
        color: #000 !important;
    }
    .breadcrumb-item.active {
        color: #000 !important;
        font-weight: 600;
    }
    .breadcrumb-divider {
        color: #6c757d;
    }

    /* 2. Styling Slider */
    .main-slider {
        height: 450px;
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }
    .main-slider img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 20px;
    }
    .thumb-slider {
        height: 90px;
        margin-top: 15px;
    }
    .thumb-slider .swiper-slide {
        width: 20%;
        height: 100%;
        opacity: 0.5;
        cursor: pointer;
        transition: opacity 0.3s;
    }
    .thumb-slider .swiper-slide-thumb-active { opacity: 1; }
    .thumb-slider img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid transparent;
    }
    .thumb-slider .swiper-slide-thumb-active img { border-color: #4C80C0; }

    /* Custom Arrow Swiper */
    .swiper-button-next, .swiper-button-prev {
        color: #000 !important;
        background: rgba(255,255,255,0.8);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .swiper-button-next::after, .swiper-button-prev::after {
        font-size: 18px;
        font-weight: bold;
    }
</style>

<div class="container py-4">
    {{-- Breadcrumb Modern --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Katalog</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}">
                    {{ $product->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        {{-- Left Side: Product Images --}}
        <div class="col-lg-6">
            <div class="swiper main-slider shadow-sm">
                <div class="swiper-wrapper">
                    @foreach($product->images as $image)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}">
                        </div>
                    @endforeach
                </div>
                
                @if($product->has_discount)
                    <div class="position-absolute top-0 start-0 m-3 z-3">
                        <span class="badge bg-danger px-3 py-2 rounded-3 shadow-sm">
                            -{{ $product->discount_percentage }}%
                        </span>
                    </div>
                @endif
                
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

            @if($product->images->count() > 1)
                <div class="swiper thumb-slider">
                    <div class="swiper-wrapper">
                        @foreach($product->images as $image)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $image->image_path) }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Side: Product Details --}}
        <div class="col-lg-6">
            <div class="ps-lg-3">
                <span class="badge bg-secondary bg-opacity-10 text-dark mb-2 px-3 py-2 rounded-pill fw-medium">
                    {{ $product->category->name }}
                </span>
                
                <h1 class="display-6 fw-bold mb-3 text-dark">{{ $product->name }}</h1>

                <div class="mb-4">
                    @if($product->has_discount)
                        <span class="text-muted text-decoration-line-through fs-5 me-2">
                            {{ $product->formatted_original_price }}
                        </span>
                    @endif
                    <span class="h2 fw-bold text-primary mb-0 d-block d-sm-inline">
                        {{ $product->formatted_price }}
                    </span>
                </div>

                <div class="mb-4">
                    @if($product->stock > 0)
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                            <i class="bi bi-check-circle-fill me-1"></i> Stok Tersedia
                        </span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                            <i class="bi bi-x-circle-fill me-1"></i> Stok Habis
                        </span>
                    @endif
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="mb-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <div class="input-group shadow-sm" style="width: 130px; height: 50px;">
                            <button class="btn btn-outline-light border text-dark fw-bold" type="button" onclick="decrementQty()">-</button>
                            <input type="number" name="quantity" id="quantity" class="form-control text-center border-start-0 border-end-0 fw-bold" value="1" min="1" max="{{ $product->stock }}">
                            <button class="btn btn-outline-light border text-dark fw-bold" type="button" onclick="incrementQty()">+</button>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1 fw-bold shadow-sm rounded-3" style="height: 50px;" {{ $product->stock == 0 ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </form>

                @auth
                    <button class="btn btn-outline-danger w-100 rounded-3 py-2 mb-4 transition" onclick="toggleWishlist({{ $product->id }})">
                        <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                        {{ auth()->user()->hasInWishlist($product) ? 'Hapus dari Wishlist' : 'Tambah ke Favorit' }}
                    </button>
                @endauth

                <div class="bg-light p-4 rounded-4">
                    <h5 class="fw-bold mb-3 text-dark">Deskripsi Produk</h5>
                    <div class="text-muted small lh-lg">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                    <div class="row mt-4 pt-3 border-top border-2 border-white">
                        <div class="col-6 small">
                            <i class="bi bi-box-seam text-primary me-2"></i> Berat: {{ $product->weight }} gr
                        </div>
                        <div class="col-6 small text-end">
                            <i class="bi bi-qr-code text-primary me-2"></i> SKU: PROD-{{ $product->id }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiperThumbs = new Swiper(".thumb-slider", {
        spaceBetween: 12,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });

    const swiperMain = new Swiper(".main-slider", {
        spaceBetween: 10,
        grabCursor: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiperThumbs,
        },
    });

    function incrementQty() {
        let input = document.getElementById('quantity');
        if (parseInt(input.value) < parseInt(input.max)) input.value = parseInt(input.value) + 1;
    }
    function decrementQty() {
        let input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
    }
</script>
@endpush
@endsection