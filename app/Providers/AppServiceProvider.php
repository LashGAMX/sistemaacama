<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CodigoParametros;
use App\Observers\CodigoParametrosObserver;
use App\Models\LoteDetalleDbo;
use App\Observers\LoteDetalleDboObserver;

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
        LoteDetalleDbo::observe(LoteDetalleDboObserver::class);

    }
}
