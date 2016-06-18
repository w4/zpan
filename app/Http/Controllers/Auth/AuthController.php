<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Vinkla\Pusher\Facades\Pusher;
use Vinkla\Pusher\PusherManager;

/**
 * Allow the user to login to the panel using their forum login details.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Authenticate the user to allow them to use push notifications.
     *
     * @param Request $request
     * @return string
     */
    public function pusher(Request $request)
    {
        $this->validate($request, [
            'channel_name' => 'required',
            'socket_id' => 'required'
        ]);

        switch ($request->get('channel_name')) {
            case 'private-dj':
                // trying to subscribe to DJ push notifications. ensure they're a DJ.
                if (!auth()->user()->is(Group::RADIO_DJ, Group::GUEST_DJ)) {
                    abort(403, _('Unauthorized.'));
                }
                break;

            default:
                // they're trying to subscribe to a channel we don't know about. don't let them do it.
                abort(403, _('Unauthorized.'));
                break;
        }

        return response(Pusher::connection()->socket_auth($request->get('channel_name'), $request->get('socket_id')))
            ->header('Content-Type', 'application/json');
    }
}
