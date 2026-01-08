@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
    .card-stats i {
        transition: all 0.3s ease;
    }
    .card-stats:hover i {
        transform: scale(1.2) rotate(10deg);
    }
</style>

<div class="container-fluid px-4">
    {{-- 1. Stats Cards Grid --}}
    <div class="row g-4 mb-4">
        {{-- Revenue Card --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats border-0 shadow-sm border-start border-4 border-success h-100 transition hover-shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Pendapatan</p>
                            <h4 class="fw-bold mb-0 text-dark">
                                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-currency-dollar text-success fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Action Card --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats border-0 shadow-sm border-start border-4 border-warning h-100 transition hover-shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Perlu Diproses</p>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ number_format($stats['pending_orders']) }}
                            </h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-clock-history text-warning fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Low Stock Card --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats border-0 shadow-sm border-start border-4 border-danger h-100 transition hover-shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Stok Menipis</p>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ number_format($stats['low_stock']) }}
                            </h4>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-box-arrow-in-down text-danger fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Products --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats border-0 shadow-sm border-start border-4 border-primary h-100 transition hover-shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Produk</p>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ number_format($stats['total_products']) }}
                            </h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-box-seam text-primary fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- 2. Revenue Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Analisis Penjualan</h5>
                    <span class="badge bg-light text-dark border">7 Hari Terakhir</span>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Recent Orders --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">Pesanan Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3 border-light">
                                <div>
                                    <div class="fw-bold text-dark">#{{ $order->order_number }}</div>
                                    <small class="text-muted">{{ Str::limit($order->user->name, 20) }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success" style="font-size: 0.9rem">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                    @php
                                        $statusColor = match($order->status) {
                                            'pending' => 'bg-warning',
                                            'processing' => 'bg-info',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusColor }} bg-opacity-10 {{ str_replace('bg-', 'text-', $statusColor) }}" style="font-size: 0.7rem">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <p class="text-muted">Belum ada pesanan</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                        Lihat Semua Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Top Selling Products --}}
    <div class="card border-0 shadow-sm mt-4 mb-5">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="card-title mb-0 fw-bold">Produk Terlaris</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @foreach($topProducts as $product)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="card h-100 border-0 text-center transition hover-shadow p-2">
                            <div class="position-relative">
                                <img src="{{ $product->image_url }}" class="card-img-top rounded shadow-sm" style="height: 120px; object-fit: cover;">
                                <span class="position-absolute top-0 start-0 badge rounded-pill bg-dark bg-opacity-75 m-1">
                                    #{{ $loop->iteration }}
                                </span>
                            </div>
                            <div class="card-body px-1 py-2">
                                <h6 class="card-title text-truncate mb-1" title="{{ $product->name }}" style="font-size: 0.85rem">{{ $product->name }}</h6>
                                <p class="text-primary fw-bold mb-0" style="font-size: 0.8rem">{{ $product->sold }} <small class="text-muted fw-normal">Terjual</small></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient for Chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(13, 110, 253, 0.2)');
        gradient.addColorStop(1, 'rgba(13, 110, 253, 0)');

        const labels = {!! json_encode($revenueChart->pluck('date')) !!};
        const data = {!! json_encode($revenueChart->pluck('total')) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data,
                    borderColor: '#0d6efd',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0d6efd',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 12,
                        backgroundColor: '#1e293b',
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                return ' Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#e2e8f0' },
                        ticks: {
                            font: { size: 11 },
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: "compact" }).format(value);
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endsection