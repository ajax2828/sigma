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
        // Produksi di belakang reverse proxy cloud tidak mengirim
        // X-Forwarded-Proto, jadi harus dipaksa ke HTTPS. Local tetap
        // mengikuti skema request (HTTP di :8080, HTTPS di :8443).
        // APP_URL http://localhost:8000 tidak memicu forceScheme di lokal.
        if (! app()->environment('local')
            || str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
