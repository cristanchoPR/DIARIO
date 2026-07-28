<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSedeAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $sedeId = $request->route('id') ?? $request->route('sede');

        if ($sedeId && $user && !$user->tieneAccesoSede((int) $sedeId)) {
            abort(403, 'No tienes permiso para acceder a esta sede.');
        }

        return $next($request);
    }
}
