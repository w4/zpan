<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(_('Unauthorized.'), $request->is('auth/pusher') ? 403 : 401);
            } else {
                return redirect()->guest(route('auth::login'));
            }
        } elseif (!Auth::guard($guard)->user()->isStaff()) {
            Auth::guard($guard)->logout();

            return redirect()->guest(route('auth::login'))->with('msg', [
                'type' => 'danger',
                'msg' => _('You have been logged out as you are no longer a staff member.')
            ]);
        }

        return $next($request);
    }
}
