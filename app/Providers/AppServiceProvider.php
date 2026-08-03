<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Specialite;
use App\Models\Utilisateur;
use App\Models\Equipement;
use App\Observers\AuditObserver;

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
        Specialite::observe(AuditObserver::class);

        Utilisateur::observe(AuditObserver::class);

        Equipement::observe(AuditObserver::class);

// ...
    }
}
