<?php

namespace App\Providers;

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
        // Configurar JsonResource para no escapar caracteres Unicode
        \Illuminate\Http\Resources\Json\JsonResource::withoutWrapping();
        \Illuminate\Http\Resources\Json\JsonResource::$wrap = null;
    }
}
