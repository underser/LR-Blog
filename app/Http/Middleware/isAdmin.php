<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @throws AuthorizationException|AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        return match (auth()->user()?->isAdmin()) {
            null => throw new AuthenticationException(),
            false => throw new AuthorizationException(),
            true => $next($request)
        };
    }
}
