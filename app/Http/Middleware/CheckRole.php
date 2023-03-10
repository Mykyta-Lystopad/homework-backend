<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $role
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, string $role)
    {
        /** @var User $user */
        $user = $request->user();

        // The administrator has access to everything
        if (User::ROLE_ADMIN === $user->role or $role === $user->role) {
            return $next($request);
        }

        throw new AuthorizationException('You have no access to this action');
    }
}
