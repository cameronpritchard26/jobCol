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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$types  Allowed account types (e.g. 'student', 'employer', 'admin')
     */
    public function handle(Request $request, Closure $next, string ...$types): Response
    {
        if (! $request->user() || ! in_array($request->user()->account_type->value, $types)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
