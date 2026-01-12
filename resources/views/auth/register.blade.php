@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            {{-- Card Match dengan Style Login --}}
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                
                {{-- Header Gradient --}}
                <div class="card-header bg-primary bg-gradient text-white text-center py-4 border-0">
                    <h3 class="fw-bold mb-0">Buat Akun Baru</h3>
                    <p class="small mb-0 text-white-50">Daftar sekarang untuk mulai belanja</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Input Nama --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input id="name" type="text" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" 
                                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Masukkan nama Anda">
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Input Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input id="email" type="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Input Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                <input id="password" type="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" 
                                    name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                                <input id="password-confirm" type="password" class="form-control bg-light border-start-0" 
                                    name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                            </div>
                        </div>

                        {{-- Tombol Register Utama --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill shadow-sm">
                                Daftar Akun
                            </button>
                        </div>

                        {{-- Pembatas Social Login --}}
                        <div class="position-relative my-4">
                            <hr class="text-muted">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">atau daftar dengan</span>
                        </div>

                        {{-- Tombol Google --}}
                        <div class="d-grid mb-4">
                            <a href="{{ route('auth.google') }}" class="btn btn-outline-dark btn-lg rounded-pill d-flex align-items-center justify-content-center border-2 shadow-sm">
                                <svg class="me-2" width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span class="fw-bold small">Google</span>
                            </a>
                        </div>

                        {{-- Link Kembali ke Login --}}
                        <div class="text-center mt-4">
                            <p class="mb-0 text-muted">Sudah punya akun? 
                                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Login di Sini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-muted small text-decoration-none">
                    <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; }
    .form-control:focus { box-shadow: none; border-color: #0d6efd; }
    .input-group-text { color: #6c757d; }
    .rounded-pill { letter-spacing: 0.5px; }
</style>
@endsection