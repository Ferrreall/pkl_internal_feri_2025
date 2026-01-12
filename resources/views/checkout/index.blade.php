{{-- ================================================
     FILE: resources/views/checkout/index.blade.php
     FUNGSI: Halaman Checkout (Orange Heroic Theme)
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Checkout')

<style>
    /* Paksa warna background container */
    .checkout-container {
        background-color: #ffffff;
    }

    /* Comic Card Style - Konsisten dengan Cart */
    .card-checkout {
        border: 3px solid #000 !important;
        border-radius: 0px !important;
        transition: all 0.2s ease;
    }

    .card-checkout:hover {
        transform: translate(-4px, -4px);
        box-shadow: 8px 8px 0px rgba(0, 0, 0, 1) !important;
    }

    /* Input Focus ke Orange */
    .form-control {
        border: 2px solid #eee;
        border-radius: 0px;
    }

    .form-control:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 143, 0, 0.25) !important;
        outline: none;
    }

    /* Judul Section ala Komik */
    .section-title {
        color: #000;
        border-left: 8px solid var(--primary-color);
        padding-left: 15px;
        text-transform: uppercase;
        font-weight: 900;
    }

    /* Warna Harga Orange */
    .price-text {
        color: var(--primary-color) !important;
        font-weight: 900;
    }

    /* Tombol Checkout Heroic */
    .btn-checkout-hero {
        background-color: var(--primary-color) !important;
        border: 3px solid #000 !important;
        color: white !important;
        border-radius: 0px !important;
        padding: 15px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.2s;
    }

    .btn-checkout-hero:hover {
        background-color: #F57C00 !important;
        transform: translate(-2px, -2px);
        box-shadow: 5px 5px 0px #000;
    }
</style>

@section('content')
    <div class="container py-5">
        <h2 class="mb-5 section-title">Proses Checkout</h2>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="row g-5">
                {{-- Form Alamat --}}
                <div class="col-lg-7">
                    <div class="card card-checkout shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4 fw-bold text-uppercase">
                                <i class="bi bi-truck me-2 text-warning"></i>Informasi Pengiriman
                            </h5>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase">Nama Lengkap Penerima</label>
                                <input type="text" name="name" class="form-control form-control-lg" value="{{ auth()->user()->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase">Nomor WhatsApp</label>
                                <input type="text" name="phone" class="form-control form-control-lg" placeholder="0812xxxx" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase">Alamat Lengkap</label>
                                <textarea name="address" class="form-control form-control-lg" rows="4" placeholder="Sebutkan jalan, nomor rumah, dan patokan..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan Pesanan --}}
                <div class="col-lg-5">
                    <div class="card card-checkout shadow-sm bg-white">
                        <div class="card-header bg-dark text-white py-3">
                            <h5 class="mb-0 fw-bold text-uppercase">Isi Tas Belanja</h5>
                        </div>
                        <div class="card-body p-4">
                            @php $totalBayar = 0; @endphp

                            @foreach ($cart->items as $item)
                                @php 
                                    $subtotalItem = $item->price * $item->quantity;
                                    $totalBayar += $subtotalItem;
                                @endphp
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-dashed">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image_url }}" width="60" height="60" 
                                             class="border border-2 border-dark me-3" style="object-fit: cover;">
                                        <div>
                                            <p class="mb-0 fw-bold small text-dark">{{ Str::limit($item->product->name, 25) }}</p>
                                            <small class="fw-bold text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-dark">Rp {{ number_format($subtotalItem, 0, ',', '.') }}</span>
                                </div>
                            @endforeach

                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fw-bold small text-uppercase">Subtotal</span>
                                    <span class="fw-bold">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-4 pt-2 border-top border-2 border-dark">
                                    <span class="fw-black fs-5 text-uppercase">Total Bayar</span>
                                    <span class="fs-4 price-text">
                                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                    </span>
                                </div>

                                <button type="submit" class="btn btn-checkout-hero w-100 mb-3">
                                    <i class="bi bi-shield-lock-fill me-2"></i>Selesaikan Pesanan
                                </button>

                                <div class="p-3 bg-light text-center" style="border: 2px dashed #ccc;">
                                    <p class="text-muted small mb-0 fw-bold">
                                        <i class="bi bi-patch-check-fill text-success me-1"></i> 
                                        TRANSAKSI AMAN & TERPROTEKSI
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection