{{-- ================================================
     FILE: resources/views/cart/index.blade.php
     FUNGSI: Halaman Keranjang (Full Orange Comic)
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')

<style>
    /* Styling Tabel & Card ala Komik */
    .comic-card {
        border: 3px solid #000 !important;
        border-radius: 0px !important;
        box-shadow: 10px 10px 0px rgba(0,0,0,0.1);
    }
    
    .table thead {
        background-color: var(--primary-color);
        color: white;
        border-bottom: 3px solid #000;
    }

    /* Warna Harga (Sikat Biru!) */
    .text-orange-price {
        color: var(--primary-color) !important;
        font-weight: 800;
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

<div class="container py-5">
    <h2 class="fw-black mb-4 text-uppercase" style="letter-spacing: -1px;">
        <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
    </h2>

    @if($cart && $cart->items->count())
        @php 
            $calculatedTotal = $cart->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $grandTotal = $cart->total_price > 0 ? $cart->total_price : $calculatedTotal;
        @endphp 
        
        <div class="row g-4">
            {{-- Cart Items --}}
            <div class="col-lg-8">
                <div class="card comic-card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-4 py-3">Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        @php 
                                            $itemSubtotal = $item->price * $item->quantity;
                                        @endphp
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->image_url }}"
                                                         class="border border-2 border-dark me-3"
                                                         width="70" height="70"
                                                         style="object-fit: cover;">
                                                    <div>
                                                        <a href="{{ route('catalog.show', $item->product->slug) }}"
                                                           class="text-decoration-none text-dark fw-bold">
                                                            {{ Str::limit($item->product->name, 40) }}
                                                        </a>
                                                        <div class="small text-muted text-uppercase fw-bold">
                                                            {{ $item->product->category->name ?? 'Kategori' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                {{-- FIX: Warna Orange --}}
                                                <div class="text-orange-price">
                                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </div>
                                                @if($item->price < $item->product->price)
                                                    <div class="text-muted small text-decoration-line-through">
                                                        Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity"
                                                           value="{{ $item->quantity }}"
                                                           min="1" max="{{ $item->product->stock }}"
                                                           class="form-control form-control-sm text-center qty-input mx-auto"
                                                           style="width: 70px;"
                                                           onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td class="text-end pe-4 fw-black">
                                                Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                                            </td>
                                            <td class="pe-3">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-2"
                                                            onclick="return confirm('Hapus item ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="card comic-card shadow-sm border-0">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0 text-uppercase fw-bold">Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted fw-bold">Total Barang</span>
                            <span class="fw-bold">{{ $cart->items->sum('quantity') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted fw-bold">Total Harga</span>
                            <span class="fw-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <hr style="border-top: 2px dashed #000;">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-black text-uppercase">Total Tagihan</span>
                            {{-- FIX: text-primary diganti text-orange-price --}}
                            <span class="fs-4 text-orange-price">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-checkout w-100 btn-lg mb-2">
                            <i class="bi bi-credit-card me-2"></i>Lanjut Checkout
                        </a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-continue btn-light w-100">
                            <i class="bi bi-arrow-left me-2"></i>Kembali Pilih Barang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Empty Cart --}}
        <div class="text-center py-5 comic-card bg-white">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="mt-3 fw-black text-uppercase">Keranjangmu Masih Kosong!</h3>
            <p class="text-muted fw-bold">Jangan biarkan hero-mu kekurangan perlengkapan.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-checkout px-5 py-3 mt-2">
                <i class="bi bi-bag-plus-fill me-2"></i>Mulai Cari Produk
            </a>
        </div>
    @endif
</div>
@endsection