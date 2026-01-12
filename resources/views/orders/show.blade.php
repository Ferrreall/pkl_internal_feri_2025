{{-- ================================================
     FILE: resources/views/orders/show.blade.php
     FUNGSI: Detail Pesanan (Comic Heroic Style)
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<style>
    /* Comic Invoice Style */
    .comic-card-detail {
        border: 4px solid #000 !important;
        border-radius: 0px !important;
        box-shadow: 15px 15px 0px rgba(0,0,0,0.1);
        background-color: #fff;
    }

    .comic-header {
        border-bottom: 4px solid #000;
        background-color: #f8f9fa;
    }

    /* Badge Custom */
    .badge-comic {
        border: 2px solid #000;
        border-radius: 0px;
        font-weight: 900;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    /* Table Styling */
    .table-comic thead {
        background-color: #333;
        color: #fff;
    }
    .table-comic th {
        border: none !important;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    /* Total Section */
    .total-label {
        font-weight: 900;
        text-transform: uppercase;
    }
    .total-amount {
        color: var(--primary-color) !important;
        font-weight: 900;
        font-size: 1.5rem;
    }

    /* Tombol Bayar Heroic */
    .btn-hero-pay {
        background-color: var(--primary-color) !important;
        border: 3px solid #000 !important;
        color: white !important;
        border-radius: 0px !important;
        font-weight: 900;
        text-transform: uppercase;
        padding: 15px;
        transition: 0.2s;
    }
    .btn-hero-pay:hover:not(:disabled) {
        background-color: #F57C00 !important;
        transform: translate(-3px, -3px);
        box-shadow: 7px 7px 0px #000;
    }

    .btn-back-comic {
        border: 2px solid #000 !important;
        border-radius: 0px !important;
        font-weight: 700;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card comic-card-detail">
                {{-- Header Detail --}}
                <div class="card-header comic-header py-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-black text-uppercase">Detail Pesanan</h4>
                        <span class="badge bg-dark">#{{ $order->order_number }}</span>
                    </div>
                    
                    <div class="d-flex gap-2">
                        {{-- Badge Status Dinamis --}}
                        @if($order->payment_status === 'paid')
                            <span class="badge badge-comic bg-success px-3 py-2">LUNAS</span>
                        @elseif($order->status === 'failed')
                            <span class="badge badge-comic bg-danger px-3 py-2">GAGAL</span>
                        @else
                            <span class="badge badge-comic bg-warning text-dark px-3 py-2">
                                {{ $order->payment_status ?? $order->status }}
                            </span>
                        @endif
                        
                        {{-- Badge Status Pengiriman --}}
                        @if($order->payment_status === 'paid')
                            <span class="badge badge-comic bg-info text-white px-3 py-2">
                                {{ strtoupper($order->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    {{-- Info Pembeli & Tanggal --}}
                    <div class="row mb-5 pb-4 border-bottom border-2 border-dark" style="border-style: dashed !important;">
                        <div class="col-sm-6">
                            <h6 class="text-muted text-uppercase fw-bold mb-3 small">Dikirim Ke:</h6>
                            <p class="mb-1 fw-black fs-5">{{ Auth::user()->name }}</p>
                            <p class="mb-0 text-muted small">{{ Auth::user()->email }}</p>
                            {{-- Tambahkan alamat jika ada di model Order --}}
                            @if($order->address)
                                <p class="mb-0 mt-2 small text-dark"><i class="bi bi-geo-alt-fill me-1"></i> {{ $order->address }}</p>
                            @endif
                        </div>
                        <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                            <h6 class="text-muted text-uppercase fw-bold mb-3 small">Waktu Transaksi:</h6>
                            <p class="mb-0 fw-bold">{{ $order->created_at->format('d F Y') }}</p>
                            <p class="mb-0 text-muted small">{{ $order->created_at->format('H:i') }} WIB</p>
                        </div>
                    </div>

                    {{-- Tabel Produk --}}
                    <div class="table-responsive mb-5">
                        <table class="table table-comic align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3 py-3">Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr class="border-bottom border-1">
                                    <td class="py-4 ps-3">
                                        <div class="fw-black text-dark">{{ $item->product_name }}</div>
                                    </td>
                                    <td class="py-4 text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="py-4 text-end">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 text-end pe-3 fw-black">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="3" class="text-end py-4 total-label">Total Pembayaran</th>
                                    <th class="text-end py-4 pe-3 total-amount">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Area Aksi / Tombol --}}
                    @if($order->payment_status !== 'paid' && $order->status !== 'failed')
                        <div class="text-center mb-4">
                            <button id="pay-button" class="btn btn-hero-pay w-100 fs-5">
                                <i class="bi bi-lightning-fill me-2"></i>
                                <span id="button-text">Bayar Sekarang</span>
                                <span id="button-loader" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                            <p class="mt-3 text-muted small">
                                <i class="bi bi-shield-check me-1"></i> Pembayaran aman via Midtrans
                            </p>
                        </div>
                    @else
                        <div class="alert alert-success border-2 border-dark rounded-0 shadow-sm text-center py-4 mb-4">
                            <h4 class="fw-black mb-2 text-uppercase">
                                <i class="bi bi-check-circle-fill me-2"></i>Mission Accomplished!
                            </h4>
                            <p class="mb-0 fw-bold">Pembayaran Anda telah kami terima. Hero sedang menyiapkan pesanan Anda.</p>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('orders.index') }}" class="btn btn-back-comic btn-dark py-3">
                                <i class="bi bi-arrow-left me-2"></i>KEMBALI KE RIWAYAT PESANAN
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($order->payment_status !== 'paid')
<script 
    src="https://app.sandbox.midtrans.com/snap/snap.js" 
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const payButton = document.getElementById('pay-button');
    const btnText = document.getElementById('button-text');
    const btnLoader = document.getElementById('button-loader');

    if (!payButton) return;

    payButton.addEventListener('click', function () {
        payButton.disabled = true;
        btnText.innerText = 'Menghubungkan ke Bank...';
        btnLoader.classList.remove('d-none');

        fetch('{{ route("payments.snap-token", $order->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.token) {
                window.snap.pay(data.token, {
                    onSuccess: function (result) { location.reload(); },
                    onPending: function (result) { location.reload(); },
                    onError: function (result) {
                        alert("Waduh, Pembayaran Gagal!");
                        resetButton();
                    },
                    onClose: function () { resetButton(); }
                });
            } else {
                alert('Waduh: ' + (data.error || 'Gagal mengambil token'));
                resetButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Koneksi terputus, Twin!');
            resetButton();
        });
    });

    function resetButton() {
        payButton.disabled = false;
        btnText.innerText = 'Bayar Sekarang';
        btnLoader.classList.add('d-none');
    }
});
</script>
@endif
@endpush