<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CodigoParametros;
use App\Observers\CodigoParametrosObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void 
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        CodigoParametros::observe(CodigoParametrosObserver::class);

    }
}
