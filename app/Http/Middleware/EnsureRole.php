<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Middleware multi-role fleksibel untuk Tugas P11 No.6.
// Pemakaian: ->middleware('role:admin'), ->middleware('role:admin,editor')
class EnsureRole
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ('.implode('/', $roles).' only).');
        }

        return $next($request);
    }
}
