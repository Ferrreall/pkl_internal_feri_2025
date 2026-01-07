{{-- ================================================
     FILE: resources/views/cart/index.blade.php
     FUNGSI: Halaman keranjang belanja (Fixed Total 0)
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">
        <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
    </h2>

    @if($cart && $cart->items->count())
        {{-- Logika Fix: Jika total_price di DB masih 0, kita hitung manual dari items --}}
        @php 
            $calculatedTotal = $cart->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $grandTotal = $cart->total_price > 0 ? $cart->total_price : $calculatedTotal;
        @endphp 
        
        <div class="row">
            {{-- Cart Items --}}
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50%">Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->items as $item)
                                    @php 
                                        $itemSubtotal = $item->price * $item->quantity;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->product->image_url }}"
                                                     class="rounded me-3"
                                                     width="60" height="60"
                                                     style="object-fit: cover;">
                                                <div>
                                                    <a href="{{ route('catalog.show', $item->product->slug) }}"
                                                       class="text-decoration-none text-dark fw-medium">
                                                        {{ Str::limit($item->product->name, 40) }}
                                                    </a>
                                                    <div class="small text-muted">
                                                        {{ $item->product->category->name ?? 'Kategori' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            {{-- Harga Diskon --}}
                                            <div class="fw-bold text-primary">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </div>
                                            {{-- Harga Coret --}}
                                            @if($item->price < $item->product->price)
                                                <div class="text-muted small text-decoration-line-through">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                  class="d-inline-flex align-items-center">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity"
                                                       value="{{ $item->quantity }}"
                                                       min="1" max="{{ $item->product->stock }}"
                                                       class="form-control form-control-sm text-center"
                                                       style="width: 70px;"
                                                       onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="text-end align-middle fw-bold">
                                            Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
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

            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Harga ({{ $cart->items->sum('quantity') }} barang)</span>
                            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total Tagihan</span>
                            <span class="fw-bold fs-5 text-primary">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 btn-lg shadow-sm">
                            <i class="bi bi-credit-card me-2"></i>Checkout
                        </a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-light w-100 mt-2">
                            <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Empty Cart --}}
        <div class="text-center py-5 shadow-sm bg-white rounded">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h4 class="mt-3">Keranjang Kosong</h4>
            <p class="text-muted">Ayo cari produk impianmu sekarang!</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary px-4">
                <i class="bi bi-bag me-2"></i>Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection