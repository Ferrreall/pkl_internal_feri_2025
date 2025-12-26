@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-0">
                    <h4 class="mb-0 fw-bold" style="color: #2F1262;">Detail Pesanan</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="text-muted small">Nomor Pesanan:</h6>
                            <p class="fw-bold text-primary">{{ $order->order_number }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <h6 class="text-muted small">Status:</h6>
                            <span class="badge rounded-pill bg-warning text-dark px-3">
                                {{ strtoupper($order->status) }}
                            </span>
                        </div>
                    </div>

                    <hr class="opacity-10">

                    <h6 class="fw-bold mb-3">Item Pesanan</h6>
                    <ul class="list-group list-group-flush mb-4">
                        @foreach($order->items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <h6 class="mb-0">{{ $item->product_name }}</h6>
                                <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                            </div>
                            <span class="fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <div class="p-3 rounded-3 mb-4" style="background-color: #f8f9fa;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5" style="color: #2F1262;">
                            <span>Total Pembayaran</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg shadow-sm" style="background: #4C80C0; border: none; border-radius: 10px;">
                            Bayar Sekarang (Midtrans)
                        </button>
                        <a href="/" class="btn btn-light text-muted">Kembali Belanja</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection