<?php
namespace App\Http\Controllers;

use App\Models\Timezone;
use App\Models\User;
use DateTimeZone;
use Illuminate\Http\Request;

/**
 * Allow the user to update their settings.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class SettingsController extends Controller
{
    /**
     * Show the form for the user to update their timezone
     *
     * @return mixed
     */
    public function timezoneForm()
    {
        return view('settings.timezone', [
            'timezones' => DateTimeZone::listIdentifiers()
        ]);
    }

    /**
     * Update the user's timezone and return back to the form.
     *
     * @param Request $request
     * @return mixed
     */
    public function updateTimezone(Request $request)
    {
        $timezones = DateTimeZone::listIdentifiers();

        $this->validate($request, [
            'timezone' => 'required|in:' . implode(',', $timezones)
        ]);

        $timezone = Timezone::firstOrCreate(['user_id' => auth()->user()->userid]);
        $timezone->timezone = $request->get('timezone');
        $timezone->save();

        return redirect()->back()->with('msg', [
            'type' => 'success',
            'msg' => 'Successfully updated your timezone.'
        ]);
    }
}
