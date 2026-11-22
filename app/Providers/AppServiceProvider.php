<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Aplikasi diakses lewat HTTPS (reverse proxy cloud), tapi Laravel
        // tidak menerima header X-Forwarded-Proto. Tanpa ini route() bikin
        // URL http:// → form POST kena redirect 301 HTTPS dan error validasi
        // hilang, sehingga halaman terasa "stuck".
        URL::forceScheme('https');
    }
}
