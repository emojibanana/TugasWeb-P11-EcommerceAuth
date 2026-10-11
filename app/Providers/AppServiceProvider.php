<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Support\Facades\Gate;
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
     * Slide P11 hal.56: Gate = closure sederhana tak terikat model,
     * Policy = class per model. Keduanya didaftarkan di sini.
     */
    public function boot(): void
    {
        // Gate sederhana untuk cek role admin (dipakai via Gate::allows('admin') / @can('admin'))
        Gate::define('admin', fn (User $user): bool => $user->role === 'admin');
        Gate::define('edit-products', fn (User $user): bool => in_array($user->role, ['admin', 'editor'], true));

        // Policy per model (otomatis terdeteksi Laravel by convention,
        // didaftarkan eksplisit agar jelas untuk dokumentasi tugas)
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
    }
}
