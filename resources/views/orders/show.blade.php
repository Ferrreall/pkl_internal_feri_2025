@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                {{-- Header Detail Pesanan --}}
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold text-dark">Detail Pesanan #{{ $order->order_number }}</h4>
                    <span class="badge rounded-pill 
                        @if($order->status == 'pending') bg-warning text-dark 
                        @elseif($order->status == 'success') bg-success 
                        @else bg-secondary @endif px-3 py-2">
                        {{ strtoupper($order->status) }}
                    </span>
                </div>

                <div class="card-body p-4">
                    <p class="text-muted small mb-4">Dipesan pada: {{ $order->created_at->format('d M Y, H:i') }}</p>

                    <h6 class="fw-bold mb-3">Produk yang Dipesan</h6>
                    <div class="table-responsive mb-4">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top-0">
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-end text-muted">Ongkos Kirim:</td>
                                    <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="fs-5">
                                    <td colspan="3" class="text-end fw-bold">TOTAL BAYAR:</td>
                                    <td class="text-end fw-bold text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Alamat Pengiriman --}}
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <h6 class="fw-bold mb-2">Alamat Pengiriman</h6>
                        <p class="mb-1 fw-semibold">{{ $order->shipping_name }}</p>
                        <p class="mb-1 text-muted">{{ $order->shipping_phone }}</p>
                        <p class="mb-0 text-muted">{{ $order->shipping_address }}</p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-grid gap-2 text-center">
                        @if($order->status === 'pending' && isset($snapToken))
                            <div class="alert alert-info border-0 small mb-3">
                                Silakan selesaikan pembayaran agar pesanan segera diproses.
                            </div>
                            <button id="pay-button" class="btn btn-primary btn-lg shadow-sm py-3 fw-bold" style="border-radius: 10px;">
                                💳 Bayar Sekarang
                            </button>
                        @endif
                        <a href="{{ url('/') }}" class="btn btn-link text-decoration-none text-muted mt-2">
                            &larr; Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Midtrans --}}
@if(isset($snapToken) && $order->status === 'pending')
    @push('scripts')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                const payButton = document.getElementById('pay-button');
                if (payButton) {
                    payButton.addEventListener('click', function() {
                        payButton.disabled = true;
                        payButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

                        window.snap.pay('{{ $snapToken }}', {
                            onSuccess: function(result) {
                                window.location.href = "{{ route('orders.show', $order) }}?status=success";
                            },
                            onPending: function(result) {
                                window.location.href = "{{ route('orders.show', $order) }}?status=pending";
                            },
                            onError: function(result) {
                                alert("Pembayaran gagal!");
                                location.reload();
                            },
                            onClose: function() {
                                payButton.disabled = false;
                                payButton.innerHTML = '💳 Bayar Sekarang';
                            }
                        });
                    });
                }
            });
        </script>
    @endpush
@endif
@endsection