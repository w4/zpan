<?php
namespace App\Http\Controllers\DJ;

use App\Http\Controllers\Controller;
use App\Models\DJSays;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Allow the DJ to update the DJ status displayed on the main site.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class DJSaysController extends Controller
{
    /**
     * Get the form to change the DJ Says.
     *
     * @return mixed
     */
    public function getForm()
    {
        $says = DJSays::remember(5)->orderBy('id', 'desc')->take(1)->first();
        return view('dj.dj-says', ['current' => $says ? e($says->msg) : _('Currently unset.')]);
    }

    /**
     * Change the DJ Says that is shown on the main site.
     *
     * @param Request $request
     * @return mixed
     */
    public function postForm(Request $request)
    {
        $this->validate($request, [
            'msg' => 'required|string|max:200'
        ], [], ['msg' => _('DJ Says')]);

        $says = new DJSays;
        $says->dj = auth()->user()->userid;
        $says->msg = $request->get('msg');
        $says->save();

        return redirect()->back()->with('msg', [
            'type' => 'success',
            'msg' => _('Successfully updated the DJ Says, this change has been reflected on the main site.')
        ]);
    }

    /**
     * API method call. Allow the frontend site to get the current DJ Says.
     *
     * @return array
     */
    public function getSays()
    {
        $says = DJSays::remember(5)->orderBy('id', 'desc')->take(1)->first();

        if ($says) {
            return ['dj' => User::remember(30)->find($says->dj)->username, 'msg' => e($says->msg)];
        } else {
            return ['dj' => _('Unavailable'), 'msg' => _('Currently unset.')];
        }
    }
}
