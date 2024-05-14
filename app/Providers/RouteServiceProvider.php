<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/encuesta.php'));
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/campoApp.php'));
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/config.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/clientes.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/analisisQ.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/cotizacion.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/precios.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/usuarios.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/campo.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/capacitacion.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/historial.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/recurso.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/ingresar.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/laboratorio.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/labAnalisis.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/informes.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/supervicion.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/seguimiento.php'));
            Route::middleware('web', 'auth')
                ->namespace($this->namespace)
                ->prefix('admin')
                ->group(base_path('routes/modules/kpi.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
