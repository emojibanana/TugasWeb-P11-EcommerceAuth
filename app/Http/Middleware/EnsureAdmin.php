<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Sesuai slide P11 hal.55: Middleware Security Checkpoint
// Dipakai sebagai alias 'admin' -> hanya role admin yang lolos, selain itu 403.
class EnsureAdmin
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role !== 'admin') {
            abort(403, 'Hanya admin yang boleh mengakses halaman ini.');
        }

        return $next($request);
    }
}
