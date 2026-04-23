<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EsHijo
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('hijo_id')) {
            return redirect()->route('hijo.seleccionar');
        }
        return $next($request);
    }
}
