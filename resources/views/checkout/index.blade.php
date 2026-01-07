{{-- ================================================
     FILE: resources/views/checkout/index.blade.php
     FUNGSI: Halaman checkout (Fixed Discount Price)
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Checkout')

<style>
    .checkout-container {
        background-color: #f8f9fa;
        border-radius: 15px;
    }

    .card-checkout {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .card-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(76, 128, 192, 0.1) !important;
    }

    .form-control:focus {
        border-color: #4C80C0;
        box-shadow: 0 0 0 0.25rem rgba(76, 128, 192, 0.25);
    }

    .section-title {
        color: #4C80C0;
        border-left: 4px solid #4C80C0;
        padding-left: 15px;
    }

    .price-text {
        color: #4C80C0;
        font-weight: 700;
    }

    .btn-checkout {
        background: linear-gradient(135deg, #4C80C0 0%, #4e98f4 100%);
        border: none;
        padding: 12px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-checkout:hover {
        opacity: 0.9;
        transform: scale(1.01);
    }
</style>

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4 section-title">Checkout</h2>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                {{-- Form Alamat --}}
                <div class="col-lg-7">
                    <div class="card card-checkout shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4 fw-bold"><i class="bi bi-truck me-2"></i>Informasi Pengiriman</h5>

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap Penerima</label>
                                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nomor WhatsApp</label>
                                <input type="text" name="phone" class="form-control" placeholder="0812xxxx" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="address" class="form-control" rows="4" placeholder="Jl. Fontaine No. 1..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan Pesanan --}}
                <div class="col-lg-5">
                    <div class="card card-checkout shadow-sm border-0 bg-white">
                        <div class="card-body p-4">
                            <h5 class="mb-4 fw-bold">Pesanan Kamu</h5>

                            @php $totalBayar = 0; @endphp

                            @foreach ($cart->items as $item)
                                @php 
                                    // FIX: Gunakan $item->price (harga diskon dari cart), bukan product->price
                                    $subtotalItem = $item->price * $item->quantity;
                                    $totalBayar += $subtotalItem;
                                @endphp
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image_url }}" width="50" class="rounded me-2">
                                        <div>
                                            <p class="mb-0 fw-bold small">{{ Str::limit($item->product->name, 25) }}</p>
                                            <small class="text-muted">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                    <span class="small fw-bold">Rp {{ number_format($subtotalItem, 0, ',', '.') }}</span>
                                </div>
                            @endforeach

                            <hr class="my-4">

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5">Total Pembayaran</span>
                                <span class="fs-5 price-text">
                                    Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                </span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-checkout w-100 mb-3 text-white">
                                <i class="bi bi-lock-fill me-2"></i>Buat Pesanan Sekarang
                            </button>

                            <p class="text-center text-muted small">
                                <i class="bi bi-shield-check me-1"></i> Pembayaran Aman & Terenkripsi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection