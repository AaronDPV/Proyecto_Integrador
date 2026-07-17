<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role && $user->role->permissions) {
            $permisos = json_decode($user->role->permissions, true);

            if (is_array($permisos) && in_array($permission, $permisos)) {
                return $next($request);
            }
        }

        abort(403, 'No tienes privilegios suficientes para acceder a este módulo corporativo.');
    }
}