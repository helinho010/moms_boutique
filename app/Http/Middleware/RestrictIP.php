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

    // Hostnames permitidos
    private $hostnamesPermitidos = [
        'PC_OFICINA_SU',
        'LAPTOP_MIRAFLORES',
        'X555LAB-5c914e7f',
    ];

    public function handle(Request $request, Closure $next):Response
    {
        if (in_array($request->ip(), $this->ipsValidas)) {
            return $next($request);    
        }

        $hostname = gethostbyaddr($request->ip());
        if (in_array($hostname, $this->hostnamesPermitidos)) {
            return $next($request);
        }

        return response(view('errors.accesoRestringido'), 403);
        
    }
}
