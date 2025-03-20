<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // IP's permitidas
    private $ipsValidas = [
        '192.168.0.22',
        '192.168.88.30',
    ];

    public function handle(Request $request, Closure $next):Response
    {
        if (!in_array($request->ip(), $this->ipsValidas)) {
            return response(view('errors.accesoRestringido'), 403);
        }

        return $next($request);
    }
}
