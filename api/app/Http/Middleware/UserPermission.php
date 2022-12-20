<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $permissions = $enableds = [];
        $arrayPermissions = (array) $request->authUser->permissions ?? [];

        if (!is_array($arrayPermissions)) {
            $arrayPermissions = json_decode($arrayPermissions, true);
        }

        foreach ($arrayPermissions as $key => $value) {
            if($value) $enableds[] = $key;
        }

        foreach ($guards as $p => $permission) {
            $permissions[$p] = in_array($permission, $enableds);

            if (
                in_array('users', $enableds) &&
                ($permission === 'is_self' && $request->has("id"))
            ) {
                $permissions[$p] = $request->id == $request->authUser->id;
            }
        }

        if (in_array(true, $permissions)) return $next($request);
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
