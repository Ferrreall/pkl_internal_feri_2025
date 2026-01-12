@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    /* Custom Theme Colors */
    :root {
        --hero-blue: #1A237E;
        --hero-red: #D32F2F;
        --hero-yellow: #FFEB3B;
        --hero-gold: #FF8F00;
    }

    .hover-shadow:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
    }
    .transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-stats {
        overflow: hidden;
        border: none !important;
        position: relative;
    }
    /* Decorative Background Pattern */
    .card-stats::after {
        content: "";
        position: absolute;
        top: -20px;
        right: -20px;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    .card-stats i {
        transition: all 0.5s ease;
        z-index: 1;
    }
    .card-stats:hover i {
        transform: scale(1.3) rotate(-15deg);
        color: #fff !important;
    }
    .card-stats:hover .stats-icon-container {
        background-color: rgba(255,255,255,0.2) !important;
    }
    .card-stats:hover p, .card-stats:hover h4 {
        color: #fff !important;
    }

    /* Spesifik warna hover All Might */
    .hover-hero-blue:hover { background-color: var(--hero-blue) !important; }
    .hover-hero-red:hover { background-color: var(--hero-red) !important; }
    .hover-hero-gold:hover { background-color: var(--hero-gold) !important; }
    .hover-hero-success:hover { background-color: #2e7d32 !important; }

    .chart-container {
        background: linear-gradient(to bottom right, #ffffff, #f8fafc);
    }
</style>

<div class="container-fluid px-0">
    {{-- 1. Stats Cards Grid --}}
    <div class="row g-4 mb-4">
        {{-- Revenue Card (Success/Green) --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats shadow-sm border-start border-4 border-success h-100 transition hover-shadow hover-hero-success">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px;">Total Pendapatan</p>
                            <h4 class="fw-extrabold mb-0 text-dark">
                                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="stats-icon-container bg-success bg-opacity-10 p-3 rounded-3 transition">
                            <i class="bi bi-cash-stack text-success fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Orders (Warning/Gold) --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats shadow-sm border-start border-4 border-warning h-100 transition hover-shadow hover-hero-gold">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px;">Butuh Aksi</p>
                            <h4 class="fw-extrabold mb-0 text-dark">
                                {{ number_format($stats['pending_orders']) }} <small class="fw-normal fs-6">Orders</small>
                            </h4>
                        </div>
                        <div class="stats-icon-container bg-warning bg-opacity-10 p-3 rounded-3 transition">
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Low Stock (Red) --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats shadow-sm border-start border-4 border-danger h-100 transition hover-shadow hover-hero-red">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px;">Stok Kritis</p>
                            <h4 class="fw-extrabold mb-0 text-dark">
                                {{ number_format($stats['low_stock']) }} <small class="fw-normal fs-6">Item</small>
                            </h4>
                        </div>
                        <div class="stats-icon-container bg-danger bg-opacity-10 p-3 rounded-3 transition">
                            <i class="bi bi-lightning-charge-fill text-danger fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Products (Blue) --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats shadow-sm border-start border-4 border-primary h-100 transition hover-shadow hover-hero-blue">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px;">Total Katalog</p>
                            <h4 class="fw-extrabold mb-0 text-dark">
                                {{ number_format($stats['total_products']) }}
                            </h4>
                        </div>
                        <div class="stats-icon-container bg-primary bg-opacity-10 p-3 rounded-3 transition">
                            <i class="bi bi-box-fill text-primary fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- 2. Revenue Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100 chart-container">
                <div class="card-header bg-transparent border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary text-uppercase" style="letter-spacing: 1px;">Grafik Penjualan</h5>
                        <small class="text-muted">Pantau pertumbuhan tokomu setiap hari</small>
                    </div>
                    <span class="badge rounded-pill bg-primary px-3 py-2">7 Hari Terakhir</span>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 320px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Recent Orders --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 py-4 px-4 d-flex align-items-center">
                    <i class="bi bi-clock-history text-primary fs-4 me-2"></i>
                    <h5 class="card-title mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">Aksi Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3 border-light transition hover-bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light p-2 rounded-circle me-3">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">#{{ $order->order_number }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">{{ Str::limit($order->user->name, 15) }}</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary mb-1" style="font-size: 0.85rem">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                    @php
                                        $statusClass = match($order->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'processing' => 'bg-info text-white',
                                            'completed' => 'bg-success text-white',
                                            'cancelled' => 'bg-danger text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}" style="font-size: 0.6rem; letter-spacing: 0.5px;">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted fs-1"></i>
                                <p class="text-muted mt-2">Belum ada pesanan masuk</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 text-center py-4">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-all-might w-75 rounded-pill shadow-sm">
                        LIHAT SEMUA PESANAN
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Top Selling Products --}}
    <div class="card border-0 shadow-sm mt-4 mb-5">
        <div class="card-header bg-transparent border-0 py-4 px-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-trophy-fill text-warning fs-4 me-2"></i>
                <h5 class="card-title mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">Laporan Penjualan (Terlaris)</h5>
            </div>
        </div>
        <div class="card-body px-4">
            <div class="row g-4">
                @foreach($topProducts as $product)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="card h-100 border-0 text-center transition hover-shadow p-2 bg-light bg-opacity-50">
                            <div class="position-relative overflow-hidden rounded shadow-sm">
                                <img src="{{ $product->image_url }}" class="card-img-top" style="height: 140px; object-fit: cover; transition: 0.5s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge rounded-pill bg-danger shadow">#{{ $loop->iteration }}</span>
                                </div>
                            </div>
                            <div class="card-body px-1 py-3">
                                <h6 class="card-title text-truncate mb-2 fw-bold" title="{{ $product->name }}" style="font-size: 0.8rem; color: var(--hero-blue);">{{ $product->name }}</h6>
                                <div class="bg-white rounded-pill py-1 shadow-sm">
                                    <span class="text-danger fw-bold" style="font-size: 0.85rem">{{ $product->sold }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">Terjual</small>
                                </div>
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
        
        // Gradient Blue All Might
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(26, 35, 126, 0.4)');
        gradient.addColorStop(1, 'rgba(26, 35, 126, 0)');

        const labels = {!! json_encode($revenueChart->pluck('date')) !!};
        const data = {!! json_encode($revenueChart->pluck('total')) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data,
                    borderColor: '#1A237E',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#FFEB3B',
                    pointBorderColor: '#D32F2F',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 15,
                        backgroundColor: '#0d1142',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return ' ⚡ Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)', borderDash: [5, 5] },
                        ticks: {
                            callback: value => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: "compact" }).format(value)
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection