{{-- ================================================
     FILE: resources/views/home.blade.php
     FUNGSI: Halaman Beranda - PLUS ULTRA Edition
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Beranda - PLUS ULTRA!')

@section('content')
    {{-- Hero Section --}}
    <section class="hero-gradient py-5">
        <div class="container">
            <div class="row align-items-center">
                {{-- Text Content --}}
                <div class="col-lg-5">
                    <h1 class="display-4 fw-bold mb-3 hero-text-main">
                        Belanja Online <br><span style="color: var(--primary-color);">PLUS ULTRA!</span>
                    </h1>
                    <p class="lead mb-4 hero-text-sub">
                        Temukan berbagai produk berkualitas dengan harga terbaik. 
                        Gratis ongkir untuk pahlawan belanja seperti kamu!
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-bag me-2"></i>Mulai Belanja
                        </a>
                        <a href="#terbaru" class="btn btn-outline-dark btn-lg">
                            Lihat Produk
                        </a>
                    </div>
                </div>

                {{-- Carousel Slider (Pengganti Gambar Statis) --}}
                <div class="col-lg-7 d-none d-lg-block">
                    <div id="heroCarousel" class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel">
                        {{-- Indicators --}}
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                        </div>

                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="4000">
                                <img src="{{ asset('images/orenn.png') }}" class="d-block w-100" alt="Slide 1">
                            </div>
                            <div class="carousel-item" data-bs-interval="4000">
                                <img src="{{ asset('images/pee4.png') }}" class="d-block w-100" alt="Slide 2">
                            </div>
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4 fw-bold">Kategori Populer</h2>
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm text-center h-100 category-card">
                                <div class="card-body">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="rounded-circle mb-3 border border-2 border-warning" width="80" height="80" style="object-fit: cover;">
                                    <h6 class="card-title mb-0 text-dark fw-bold">{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $category->products_count }} produk</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Produk Unggulan --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 fw-bold">Produk Unggulan</h2>
                <a href="{{ route('catalog.index') }}" class="btn btn-brand-search">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{-- Produk Terbaru --}}
    <section id="terbaru" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4 fw-bold">Produk Terbaru</h2>
            <div class="row g-4">
                @foreach($latestProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection