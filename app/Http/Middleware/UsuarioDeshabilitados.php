<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function PHPSTORM_META\type;

class UsuarioDeshabilitados
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ( auth()->check() && auth()->user()->estado == 0) {
            auth()->logout();
            return redirect()->route('login')->with('mensaje', 'Usuario deshabilitado');
        }
        return $next($request);
    }
}
