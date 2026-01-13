{{-- ================================================
     FILE: resources/views/catalog/show.blade.php
     FUNGSI: Detail Produk (Orange Comic Theme) + Wishlist
     ================================================ --}}

@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        /* 1. Breadcrumb Fix */
        .breadcrumb-item a {
            color: var(--text-muted) !important;
            text-decoration: none;
            font-weight: 600;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-color) !important;
        }

        .breadcrumb-item.active {
            color: var(--comic-black) !important;
            font-weight: 800;
        }

        /* 2. Slider Comic Style */
        .main-slider {
            height: 450px;
            background: #fff;
            border-radius: 0px;
            overflow: hidden;
            border: 3px solid var(--comic-black);
            box-shadow: 8px 8px 0px rgba(0, 0, 0, 0.1);
        }

        .main-slider img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
        }

        .thumb-slider .swiper-slide-thumb-active img {
            border-color: var(--primary-color) !important;
            border-width: 3px;
        }

        /* 3. Price Tag */
        .price-tag {
            color: var(--primary-color) !important;
            font-weight: 900;
            letter-spacing: -1px;
        }

        /* 4. Button Heroic Style */
        .btn-heroic-lg {
            background-color: var(--primary-color);
            color: white;
            border: 3px solid var(--comic-black);
            border-radius: 15px;
            font-weight: 900;
            text-transform: uppercase;
            box-shadow: 5px 5px 0px var(--comic-black);
            transition: 0.2s;
        }

        .btn-heroic-lg:hover:not(:disabled) {
            background-color: #F57C00;
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0px var(--comic-black);
            color: white;
            border: 3px solid var(--comic-black);
        }

        /* Tombol Wishlist Style */
        .btn-wishlist-heroic {
            background-color: white;
            color: var(--comic-black);
            border: 3px solid var(--comic-black);
            border-radius: 15px;
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 5px 5px 0px var(--comic-black);
            transition: 0.2s;
        }

        .btn-wishlist-heroic:hover {
            transform: translate(-2px, -2px);
            box-shadow: 7px 7px 0px var(--primary-color);
            color: var(--primary-color);
        }

        .btn-wishlist-heroic.active {
            background-color: #ff4757;
            color: white;
        }

        /* Input Quantity */
        .qty-input-group {
            border: 3px solid var(--comic-black);
            border-radius: 15px;
            overflow: hidden;
        }

        .qty-input-group input {
            border: none !important;
            font-weight: 800;
        }

        .qty-input-group button {
            background: #eee;
            border: none;
            font-weight: 900;
        }
        /* Tombol Custom */
    .btn-checkout {
        background-color: var(--primary-color) !important;
        border: 3px solid #000 !important;
        color: white !important;
        border-radius: 0px !important;
        font-weight: 900;
        text-transform: uppercase;
        transition: 0.2s;
    }
    .btn-checkout:hover {
        background-color: #F57C00 !important;
        transform: translate(-3px, -3px);
        box-shadow: 6px 6px 0px #000;
    }

    .btn-continue {
        border: 3px solid #000 !important;
        border-radius: 0px !important;
        font-weight: 700;
    }

    /* Input Quantity */
    .qty-input {
        border: 2px solid #000 !important;
        border-radius: 0px !important;
        font-weight: 700;
    }
    </style>

    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Katalog</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            {{-- Left Side: Images --}}
            <div class="col-lg-6">
                <div class="swiper main-slider mb-3">
                    <div class="swiper-wrapper">
                        @foreach ($product->images as $image)
                            <div class="swiper-slide text-center">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    </div>
                    @if ($product->has_discount)
                        <div class="position-absolute top-0 start-0 m-3 z-3">
                            <span class="badge bg-danger px-3 py-2 fw-bold"
                                style="border-radius: 0; border: 2px solid black;">
                                -{{ $product->discount_percentage }}%
                            </span>
                        </div>
                    @endif
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                @if ($product->images->count() > 1)
                    <div class="swiper thumb-slider">
                        <div class="swiper-wrapper">
                            @foreach ($product->images as $image)
                                <div class="swiper-slide" style="cursor: pointer;">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid border"
                                        style="height: 80px; width: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Side: Details --}}
            <div class="col-lg-6">
                <div class="ps-lg-3">
                    <span class="badge mb-2 px-3 py-2 text-dark text-uppercase fw-bold"
                        style="background-color: var(--secondary-color); border: 2px solid black;">
                        {{ $product->category->name }}
                    </span>

                    <h1 class="display-5 fw-black mb-3 text-dark text-uppercase" style="font-weight: 900;">
                        {{ $product->name }}</h1>

                    <div class="mb-4">
                        @if ($product->has_discount)
                            <span class="text-muted text-decoration-line-through fs-5 me-2">
                                {{ $product->formatted_original_price }}
                            </span>
                        @endif
                        <span class="h1 price-tag mb-0 d-block d-sm-inline">
                            {{ $product->formatted_price }}
                        </span>
                    </div>

                    <div class="mb-4">
                        @if ($product->stock > 0)
                            <span class="fw-bold text-success text-uppercase">
                                <i class="bi bi-check-square-fill me-1"></i> Stok Siap Tempur
                            </span>
                        @else
                            <span class="fw-bold text-danger text-uppercase">
                                <i class="bi bi-x-square-fill me-1"></i> Stok Habis Total
                            </span>
                        @endif
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            {{-- Quantity --}}
                            <div class="input-group qty-input-group shadow-sm" style="width: 140px; height: 55px;">
                                <button class="btn btn-light fw-bold" type="button" onclick="decrementQty()">-</button>
                                <input type="number" name="quantity" id="quantity"
                                    class="form-control text-center fw-bold fs-5" value="1" min="1"
                                    max="{{ $product->stock }}">
                                <button class="btn btn-light fw-bold" type="button" onclick="incrementQty()">+</button>
                            </div>

                            {{-- Add to Cart --}}
                            <button type="submit" class="btn btn-heroic-lg flex-grow-1 shadow-sm" style="height: 55px;"
                                {{ $product->stock == 0 ? 'disabled' : '' }}>
                                <i class="bi bi-cart-plus-fill me-2"></i> Tambah ke Keranjang
                            </button>

                            {{-- WISHLIST BUTTON --}}
                            {{-- Pakai type="button" supaya dia GAK SUBMIT FORM --}}
                            <button type="button" onclick="toggleWishlist({{ $product->id }})"
                                class="btn-wishlist-heroic shadow-sm wishlist-btn-{{ $product->id }} {{ $product->isWishlisted() ? 'active' : '' }}"
                                title="Tambah ke Wishlist">
                                <i
                                    class="bi {{ $product->isWishlisted() ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }}"></i>
                            </button>
                            <a href="{{ route('checkout.index') }}" class="btn btn-checkout w-100 btn-lg mb-2">
                            <i class="bi bi-credit-card me-2"></i>Lanjut Checkout
                        </a>
                        </div>
                    </form>

                    <div class="mt-5 p-4 bg-white border-top border-3 border-dark"
                        style="background-image: linear-gradient(45deg, #f9f9f9 25%, transparent 25%); background-size: 4px 4px;">
                        <h5 class="fw-bold mb-3 text-uppercase">Deskripsi Produk</h5>
                        <div class="text-muted lh-lg">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                        <div class="row mt-4 pt-3 border-top border-2 border-dark border-dashed">
                            <div class="col-6 fw-bold small text-uppercase">
                                <i class="bi bi-box-seam me-2"></i> Berat: {{ $product->weight }} gr
                            </div>
                            <div class="col-6 fw-bold small text-end text-uppercase">
                                <i class="bi bi-qr-code me-2"></i> SKU: P-{{ $product->id }}
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
            // Swiper Config
            const swiperThumbs = new Swiper(".thumb-slider", {
                spaceBetween: 10,
                slidesPerView: 4,
                watchSlidesProgress: true,
            });

            const swiperMain = new Swiper(".main-slider", {
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
                thumbs: {
                    swiper: swiperThumbs
                },
            });

            // Qty Logic
            function incrementQty() {
                let input = document.getElementById('quantity');
                if (parseInt(input.value) < parseInt(input.max)) input.value = parseInt(input.value) + 1;
            }

            function decrementQty() {
                let input = document.getElementById('quantity');
                if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
            }

            // WISHLIST AJAX LOGIC (Poin Plus Sidang!)
            function toggleWishlist(productId) {
                // 1. Proteksi kalau belum login
                @guest
                window.location.href = "{{ route('login') }}";
                return;
            @endguest

            // 2. Memanggil Route POST yang lo buat tadi
            // Kita masukkan ID produk ke dalam URL-nya (Parameter {product})
            fetch("{{ url('/wishlist/toggle') }}/" + productId, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}" // Wajib ada untuk Route POST di Laravel
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const btn = document.getElementById(`wishlist-btn-${productId}`);
                    const icon = btn.querySelector('i');

                    // Logika warna tombol berdasarkan respon Controller
                    if (data.status === 'added') {
                        btn.classList.add('active');
                        icon.classList.replace('bi-heart', 'bi-heart-fill');
                    } else {
                        btn.classList.remove('active');
                        icon.classList.replace('bi-heart-fill', 'bi-heart');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        </script>
    @endpush
@endsection
