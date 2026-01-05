@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Riwayat Pesanan</h2>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nomor Pesanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">#{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge rounded-pill px-3 py-2
                                            @if ($order->status == 'pending') bg-warning text-dark 
                                            @elseif($order->status == 'shipped') 
                                                bg-info {{-- Warna Biru Muda --}}
                                            @elseif($order->status == 'delivered') 
                                                bg-success {{-- Warna Hijau --}}
                                            @elseif($order->status == 'cancelled') 
                                                bg-danger {{-- Warna Merah --}}
                                            @else 
                                                bg-secondary @endif">
                                            {{ strtoupper($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary px-3">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        Belum ada pesanan. <br>
                                        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Mulai Belanja</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
