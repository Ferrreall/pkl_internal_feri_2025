<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\MergeCartListener;
// Import Event dan Listener barunya
use App\Events\OrderPaidEvent;
use App\Listeners\SendOrderConfirmationEmail; 

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        Login::class => [
            MergeCartListener::class,
        ],
        // TAMBAHKAN INI DI BAWAHNYA
        OrderPaidEvent::class => [
            SendOrderConfirmationEmail::class,
        ],
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}