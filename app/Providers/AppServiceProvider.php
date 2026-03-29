<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if (config('app.env') === 'production' || str_contains(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Paksa URL Root agar terhindar dari /public/ jika environment tidak sinkron
        if (config('app.env') === 'production') {
            config(['app.asset_url' => config('app.url')]);
            URL::forceRootUrl(config('app.url'));
            app('url')->formatRoot(config('app.url'));
        }
    }
}
