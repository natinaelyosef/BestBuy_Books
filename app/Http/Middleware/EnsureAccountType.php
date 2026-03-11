<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountType
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  ...$types
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        $user = $request->user();

        if (!$user || empty($types)) {
            abort(403);
        }

        if (!in_array($user->account_type, $types, true)) {
            abort(403);
        }

        return $next($request);
    }
}
