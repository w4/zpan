<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class IsGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $group
     * @return mixed
     */
    public function handle($request, Closure $next, $group)
    {
        if ($group === 'admin') {
            if (!Auth::user()->isAdmin()) {
                Session::flash('msg', [
                    'type' => 'danger',
                    'msg' => sprintf('%s %s',
                        '<strong>' . _('Oh snap!') . '</strong>',
                        _('You do not have permission to view this page.')
                    )
                ]);

                return redirect()->route('dashboard::home');
            }
        } elseif ($group === 'management') {
            if (!Auth::user()->isManagement()) {
                Session::flash('msg', [
                    'type' => 'danger',
                    'msg' => sprintf('%s %s',
                        '<strong>' . _('Oh snap!') . '</strong>',
                        _('You do not have permission to view this page.')
                    )
                ]);

                return redirect()->route('dashboard::home');
            }
        } elseif (!Auth::user()->is($group)) {
            Session::flash('msg', [
                'type' => 'danger',
                'msg' => sprintf('%s %s',
                    '<strong>' . _('Oh snap!') . '</strong>',
                    _('You do not have permission to view this page.')
                )
            ]);

            return redirect()->route('dashboard::home');
        }

        return $next($request);
    }
}
