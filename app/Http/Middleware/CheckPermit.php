<?php

namespace App\Http\Middleware;

use App\Permit;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $module)
    {

        $user = Auth::user();

        if ($module == 'units' && $user->rol_id == 2 && $user->getCommerce->commerce_type_vp == 9) {
            return redirect()->route('error.401');
        }

        $userRol = $user->getRol->id;
        $permit = Permit::whereHas('getModule', function ($q) use ($module) {
            $q->where('reference', $module);
        })->where('rol_id', $userRol)->where('state', 1)->exists();
        if ($permit) {
            return $next($request);
        } else {
            return redirect()->route('error.401');
        }
    }
}
