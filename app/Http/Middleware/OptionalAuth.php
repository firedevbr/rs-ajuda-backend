<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionalAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Tenta autenticar sem obrigatoriedade
        Auth::shouldUse('sanctum');

        // Tenta autenticar o usuário se um token Bearer estiver presente
        if ($token = $request->bearerToken()) {
            $request->user('sanctum');
        }

        return $next($request);
    }
}
