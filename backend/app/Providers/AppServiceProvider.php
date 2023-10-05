<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use App\Settings\SettingContainer;
use App\Settings\SettingManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Register any application services. */
    public function register(): void {}

    /** Bootstrap any application services. */
    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        $this->app->singleton(
            SettingManager::class,
            fn() => new SettingManager(new SettingContainer())
        );
    }
}
