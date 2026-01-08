<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Pagination\Paginator; // <--- TAMBAHKAN INI

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Memberitahu Laravel untuk menggunakan styling Bootstrap 5 pada pagination
        Paginator::useBootstrapFive(); // <--- TAMBAHKAN INI

        // Observer untuk Product (Slug otomatis, dll)
        Product::observe(ProductObserver::class);
    }
}