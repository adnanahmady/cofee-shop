<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use App\Settings\Delegators\MainCurrency;
use App\Settings\SettingContainer;
use App\Settings\SettingContainerInterface;
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
        $this->bootSettingTools();
    }

    private function bootSettingTools(): void
    {
        $registers = new SettingContainer();
        $this->app->singleton(
            SettingContainerInterface::class,
            fn() => $registers
        );
        $this->app->singleton(SettingManager::class, SettingManager::class);
        $registers->register(new MainCurrency());
    }
}
