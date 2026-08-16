<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = $request->user();

                if ($user->isTrainer()) {
                    return redirect()->route('trainer.dashboard');
                }

                if ($user->isAdmin()) {
                    return redirect()->route('filament.admin.pages.dashboard');
                }

                return redirect()->route('student.dashboard');
            }
        }

        return $next($request);
    }
}
