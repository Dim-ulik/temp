<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TokenChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        $adminQuery = Admin::query()->where('token', $token);
        if (!$adminQuery->exists()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        $admin = $adminQuery->first();

        $time = Carbon::parse($admin->tokenTime);

        if ($time->diffInMinutes(Carbon::now()) >= 40) {
            return response(['message' => 'Token expired'], 403);
        }

        $request->merge(['admin' => $admin]);

        return $next($request);
    }
}
