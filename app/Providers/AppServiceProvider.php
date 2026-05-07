<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use App\Console\Kernel as ConsoleKernel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ConsoleKernelContract::class,
            ConsoleKernel::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
