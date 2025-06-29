<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RestrictIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    private $ipsValidas = [];
    
    public function handle(Request $request, Closure $next):Response
    {
        $hostname = gethostbyaddr($request->ip());

        if (in_array($request->ip(), $this->ipsValidas)) {
            Log::warning("Acceso Correcto: ".$request->ip() . " - " .$hostname);
            return $next($request);
        }

        $hostname = gethostbyaddr($request->ip());
        Log::warning("No pudo acceder al sistema: ".$request->ip() . " - " .$hostname);
        return response(view('errors.accesoRestringido'), 403);
    }
}
