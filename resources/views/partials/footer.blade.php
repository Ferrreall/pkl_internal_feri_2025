{{-- ================================================
     FILE: resources/views/partials/footer.blade.php
     FUNGSI: Footer Website (Orange Comic Theme)
     ================================================ --}}

<style>
    .footer-comic {
        border-top: 5px solid var(--primary-color);
        background-color: #111111 !important;
        padding: 60px 0 30px 0;
        /* Tambah padding dikit biar lega */
    }

    .footer-logo {
        color: #ffffff !important;
        font-weight: 900;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.5rem;
    }

    /* INI KUNCINYA BIAR LOGO KELIHATAN */
    .footer-logo img,
    .footer-logo i {
        /* Kasih 'aura' putih tipis biar siluet pensil & matanya muncul */
        filter: drop-shadow(0px 0px 8px rgba(255, 255, 255, 0.6)) drop-shadow(2px 2px 0px rgba(0, 0, 0, 1));
        max-height: 50px;
        /* Sesuaikan ukuran */
    }

    /* Tambahan biar teks deskripsi di bawah logo juga kebaca jelas */
    .footer-comic p.text-secondary {
        color: #bbbbbb !important;
        /* Abu-abu terang biar gak mati */
    }

    .footer-link {
        transition: 0.2s;
        font-weight: 500;
    }

    .footer-link:hover {
        color: var(--primary-color) !important;
        padding-left: 5px;
    }

    .social-icon {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #333;
        border: 2px solid #444;
        color: #fff !important;
        transition: 0.3s;
        text-decoration: none;
    }

    .social-icon:hover {
        background-color: var(--primary-color);
        border-color: #000;
        transform: translateY(-5px);
        box-shadow: 4px 4px 0px #000;
        color: #000 !important;
    }

    .footer-section-title {
        border-left: 4px solid var(--primary-color);
        padding-left: 10px;
        text-transform: uppercase;
        font-weight: 800;
    }
</style>

<footer class="footer-comic text-light pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            {{-- Brand & Description --}}
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <a href="/" class="text-decoration-none footer-logo">
                        <img src="{{ asset('images/almait.png') }}" alt="Logo All Might" height="65"
                            class="me-2 logo-pensil"></i>Alatku
                    </a>
                </div>
                <p class="text-secondary small">
                    Markas besar produk-produk sekolah!
                    Belanja aman, cepat, dan pastinya tampil beda dengan koleksi terbaik kami.
                </p>
                <div class="d-flex gap-2 mt-4">
                    <a href="https://wa.me/qr/RXNKWGODZDDRE1" class="social-icon"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://www.instagram.com/fwrrrdn/" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="https://github.com/Ferrreall" class="social-icon"><i class="bi bi-github"></i></a>
                    <a href="https://www.youtube.com/watch?v=IjqQEu-VZTU" class="social-icon"><i
                            class="bi bi-youtube"></i></a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white mb-4 footer-section-title">Menu</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('catalog.index') }}" class="text-secondary text-decoration-none footer-link">
                            Katalog Produk
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('cart.index') }}" class="text-secondary text-decoration-none footer-link">Keranjang</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('profile.edit') }}" class="text-secondary text-decoration-none footer-link">Profil</a>
                    </li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="text-white mb-4 footer-section-title">Markas Kami</h6>
                <ul class="list-unstyled text-secondary small">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill text-warning me-3"></i>
                        <span>Jl. Tarate No. 8, Bandung,<br>Jawa Barat, Indonesia</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-telephone-fill text-warning me-3"></i>
                        <span>+62 831-1301-4310</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-envelope-paper-heart-fill text-warning me-3"></i>
                        <span>feri1221feri@gmail.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="my-4 border-secondary opacity-25">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-secondary mb-0 small">
                    &copy; {{ date('Y') }} <span class="text-warning fw-bold">Alatku</span>. Dibuat dengan <i
                        class="bi bi-lightning-fill text-warning"></i>.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <div class="fs-4 text-secondary d-flex justify-content-center justify-content-md-end gap-3">
                    <i class="bi bi-credit-card-2-front"></i>
                    <i class="bi bi-wallet2"></i>
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
        </div>
    </div>
</footer>
