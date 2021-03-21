<?php

namespace App\Providers;

use App\Permit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
        //Directiva de permisos de los modulos
        \Blade::if('permit', function ($reference) {
            $user = Auth::user();
            $permit = Permit::whereHas('getModule', function ($q) use ($reference) {
                $q->where('reference', $reference);
            })->where('rol_id', $user->rol_id)->where('state', 1)->exists();
            return $permit;
        });

        //Directiva para checkear si es admin
        \Blade::if('isAdmin', function () {
            $user = Auth::user();
            if ($user->rol_id == 1) {
                return true;
            } else {
                return false;
            }
        });
    }
}
