{{-- ================================================
     FILE: resources/views/layouts/admin.blade.php
     FUNGSI: Master layout Admin - ALL MIGHT THEME
     ================================================ --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Admin Panel All Might</title>

    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --all-might-blue: #1A237E;
            --all-might-red: #D32F2F;
            --all-might-yellow: #FFEB3B;
            --all-might-gold: #FF8F00;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7fa;
        }

        /* --- SIDEBAR CUSTOM --- */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--all-might-blue) 0%, #0d1142 100%);
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .sidebar .brand-zone {
            background: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-bottom: 2px solid var(--all-might-gold);
        }

        .brand-text {
            font-family: 'Bangers', cursive;
            letter-spacing: 2px;
            color: var(--all-might-yellow);
            font-size: 1.5rem;
            text-shadow: 2px 2px var(--all-might-red);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 12px 20px;
            border-radius: 0 50px 50px 0;
            margin: 5px 15px 5px 0;
            transition: all 0.3s;
            font-weight: 500;
            border-left: 4px solid transparent;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--all-might-yellow);
            padding-left: 25px;
        }

        .sidebar .nav-link.active {
            background: linear-gradient(90deg, rgba(211, 47, 47, 0.2) 0%, rgba(255, 255, 255, 0.05) 100%);
            color: #fff;
            border-left: 4px solid var(--all-might-yellow);
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            margin-right: 10px;
            color: var(--all-might-gold);
        }

        /* --- TOPBAR --- */
        .top-navbar {
            background: #fff;
            border-bottom: 3px solid var(--all-might-red);
        }

        .page-title {
            font-weight: 800;
            color: var(--all-might-blue);
            text-transform: uppercase;
        }

        /* --- CARDS & UI --- */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .btn-all-might {
            background-color: var(--all-might-red);
            color: white;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-all-might:hover {
            background-color: var(--all-might-blue);
            color: var(--all-might-yellow);
            transform: translateY(-2px);
        }

        .user-panel {
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            margin: 15px;
            padding: 12px;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="sidebar d-flex flex-column" style="width: 280px;">
            {{-- Brand --}}
            <div class="brand-zone">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                   <img src="{{ asset('images/almait.png') }}" alt="Logo All Might" height="65"
                class="me-2 logo-pensil">
                    <span class="brand-text">ADMIN ULTRA!</span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-grow-1 py-4">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}"
                            class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="bi bi-cart-fill"></i> Manajemen Produk
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}"
                            class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="bi bi-tags-fill"></i> Kategori Koleksi
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}"
                            class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <span><i class="bi bi-receipt-cutoff"></i> Daftar Pesanan</span>
                            @php
                                $pendingCount = \App\Models\Order::where('status', 'pending')->count();
                            @endphp
                            @if ($pendingCount > 0)
                                <span class="badge rounded-pill bg-danger shadow-sm">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item mt-4">
                        <span class="nav-link small text-uppercase fw-bold"
                            style="color: var(--all-might-gold); opacity: 0.6; font-size: 0.7rem; letter-spacing: 2px;">
                            Analysis & Reports
                        </span>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.reports.sales') }}" 
                           class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="bi bi-graph-up-arrow"></i> Laporan Penjualan
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- User Info Panel --}}
            <div class="user-panel">
                <div class="d-flex align-items-center">
                    <div class="position-relative">
                        <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.auth()->user()->name }}" 
                             class="rounded-circle border border-2 border-warning" width="40" height="40">
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                    </div>
                    <div class="ms-3 overflow-hidden">
                        <div class="small fw-bold text-white text-truncate">{{ auth()->user()->name }}</div>
                        <div class="small text-warning" style="font-size: 0.7rem;">Symbol of Admin</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-grow-1">
            {{-- Top Bar --}}
            <header class="top-navbar py-3 px-4 d-flex justify-content-between align-items-center shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="bi bi-list fs-4 me-3 d-md-none pointer"></i>
                    <h5 class="page-title mb-0">@yield('page-title', 'Dashboard')</h5>
                </div>
                
                <div class="d-flex align-items-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm me-3" target="_blank">
                        <i class="bi bi-globe2 me-1"></i> Visit Site
                    </a>
                    
                    <div class="vr me-3 opacity-25"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-all-might btn-sm px-3">
                            <i class="bi bi-power me-1"></i> LOGOUT
                        </button>
                    </form>
                </div>
            </header>

            {{-- Content Area --}}
            <div class="p-4" style="background: #f4f7fa; min-height: calc(100vh - 75px);">
                {{-- Breadcrumb (Optional) --}}
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Admin</a></li>
                        <li class="breadcrumb-item active">@yield('page-title')</li>
                    </ol>
                </nav>

                {{-- Alert Messages --}}
                @include('partials.flash-messages')

                {{-- The Real Content --}}
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>