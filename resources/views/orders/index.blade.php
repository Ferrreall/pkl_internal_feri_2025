{{-- ================================================
     FILE: resources/views/orders/index.blade.php
     FUNGSI: Daftar Pesanan (Orange Theme)
     ================================================ --}}

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Pakai class catalog-title yang kita buat di CSS tadi biar ada coretan orange di bawahnya --}}
        <h4 class="fw-bold text-uppercase" style="color: var(--text-dark);">Pesanan Saya</h4>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 0; border: 3px solid var(--text-dark) !important;">
            <div class="card-body">
                <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted fw-bold">Kamu belum memiliki pesanan.</p>
                {{-- Ganti ke btn-primary (yang sudah kita override jadi orange di app.css) --}}
                <a href="{{ route('catalog.index') }}" class="btn btn-primary px-4">Mulai Belanja</a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover bg-white rounded shadow-sm" style="border: 2px solid #eee;">
                <thead style="background-color: var(--primary-color); color: white;">
                    <tr>
                        <th class="ps-3 border-0">Nomor Pesanan</th>
                        <th class="border-0">Tanggal</th>
                        <th class="border-0">Total</th>
                        <th class="border-0">Status</th>
                        <th class="text-center border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="align-middle">
                            <td class="ps-3 fw-bold text-dark">{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status === 'paid')
                                    <span class="badge bg-success" style="border-radius: 0;">DIBAYAR</span>
                                @elseif($order->status === 'failed')
                                    <span class="badge bg-danger" style="border-radius: 0;">GAGAL</span>
                                @else
                                    {{-- Status pending pakai warna orange terang --}}
                                    <span class="badge text-dark text-uppercase" >{{ $order->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- INI PERUBAHANNYA: Pakai btn-brand-search atau btn-outline-warning --}}
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   class="btn btn-sm btn-brand-search fw-bold text-uppercase px-3">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection