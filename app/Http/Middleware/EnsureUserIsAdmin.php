<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autorizado.'], Response::HTTP_FORBIDDEN);
            }

            return redirect()->route('login')->with('error', 'Debes iniciar sesión con una cuenta administradora para acceder.');
        }

        return $next($request);
    }
}
