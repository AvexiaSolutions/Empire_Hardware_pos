<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user && $user->role !== 'admin') {
            $routeName = $request->route()->getName();
            
            // Map route prefixes to permission keys
            $permissionMap = [
                'supplier.' => 'supplier.access',
                'account' => 'account.access',
                'expenses.' => 'expenses.access',
                'employer.' => 'employer.access',
                'credit-cheque.' => 'credit-cheque.access',
                'settings.' => 'settings.access',
            ];

            foreach ($permissionMap as $routePrefix => $permissionKey) {
                if (str_starts_with($routeName ?? '', $routePrefix)) {
                    if (!$user->hasPermission($permissionKey)) {
                        return redirect()->route('pos.index')->with('error', 'You do not have permission to access this page.');
                    }
                    return $next($request);
                }
            }
            
            // Dashboard and activity logs are strictly admin
            if (in_array($routeName, ['dashboard', 'activity-logs.index'])) {
                return redirect()->route('pos.index')->with('error', 'You do not have permission to access this page.');
            }
        }

        return $next($request);
    }
}
