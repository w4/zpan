<?php
namespace App\Http\Controllers\DJ;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Allow the DJ to view the timetable and book slots on the timetable.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class TimetableController extends Controller
{
    /**
     * Show the timetable to the user.
     *
     * @return mixed
     */
    public function getTimetable()
    {
        return view('dj.timetable', ['timetable' => $this->getJSONTimetable(false)]);
    }

    /**
     * Book slot for the user.
     *
     * @param Request $request
     * @return mixed
     */
    public function bookSlot(Request $request)
    {
        $this->validate($request, [
            'day' => 'required|integer|min:0|max:6',
            'hour' => 'required|integer|min:0|max:23'
        ]);

        $carbon = Carbon::now(auth()->user()->getTimezone())->setISODate(
            Carbon::now()->year,
            Carbon::now()->weekOfYear,
            $request->get('day') + 1
        )->setTime($request->get('hour'), 0);

        if (Carbon::now()->weekOfYear !== $carbon->weekOfYear) {
            if ($request->ajax()) {
                return [
                    'error' => true,
                    'msg' => _('This slot will be unbooked when the timetable is cleared on Monday at 00:00 GMT.')
                ];
            } else {
                return redirect()
                    ->back()
                    ->with('msg', ['type' => 'danger', 'msg' => _('This slot will be unbooked when the timetable is cleared on Monday at 00:00 GMT.')])
                    ->with('tab', $request->get('day'));
            }
        }

        $slot = Timetable::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('day', $carbon->dayOfWeek)
            ->where('hour', $carbon->hour)
            ->take(1)
            ->count();

        if ($slot !== 0) {
            // this slot is already booked.
            if ($request->ajax()) {
                return [
                    'error' => true,
                    'msg' => _('This slot is already booked.')
                ];
            } else {
                return redirect()
                    ->back()
                    ->with('msg', ['type' => 'danger', 'msg' => _('This slot is already booked.')])
                    ->with('tab', $request->get('day'));
            }
        }

        $slot = new Timetable;
        $slot->year = Carbon::now()->year;
        $slot->week = Carbon::now()->weekOfYear;
        $slot->day = $carbon->dayOfWeek;
        $slot->hour = $carbon->hour;
        $slot->dj = auth()->user()->userid;
        $slot->save();

        if ($request->ajax()) {
            return [
                'error' => false,
                'msg' => _('Successfully booked slot.')
            ];
        } else {
            return redirect()
                ->back()
                ->with('msg', ['type' => 'success', 'msg' => _('Successfully booked slot.')])
                ->with('tab', $request->get('day'));
        }
    }

    /**
     * Unbook slot for user if the slot is theirs or if they're an admin.
     *
     * @param Request $request
     * @return mixed
     */
    public function unbookSlot(Request $request)
    {
        $this->validate($request, [
            'day' => 'required|integer|min:0|max:6',
            'hour' => 'required|integer|min:0|max:23'
        ]);

        $slot = Timetable::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('day', $request->get('day'))
            ->where('hour', $request->get('hour'))
            ->take(1);

        if ($slot->first()->dj !== auth()->user()->userid && !auth()->user()->isAdmin()) {
            if ($request->ajax()) {
                return [
                    'error' => true,
                    'msg' => _('You do not have permission to unbook this slot as it is not your slot.')
                ];
            } else {
                return redirect()->back()->with('msg', [
                    'type' => 'danger',
                    'msg' => _('You do not have permission to unbook this slot as it is not your slot.')
                ]);
            }
        }

        $slot->delete();

        if ($request->ajax()) {
            return [
                'error' => false,
                'msg' => _('Successfully unbooked slot.')
            ];
        } else {
            return redirect()
                ->back()
                ->with('msg', ['type' => 'success', 'msg' => _('Successfully unbooked slot.')])
                ->with('tab', $request->get('day'));
        }
    }

    /**
     * Get the booked slots for this week in JSON format.
     *
     * @param bool $raw should we return raw html
     * @return array
     */
    public function getJSONTimetable($raw = true)
    {
        $week = Timetable::where('week', Carbon::now()->weekOfYear)->where('year', Carbon::now()->year)->get();

        $timetable = [
            0 => ['name' => _('Monday')],
            1 => ['name' => _('Tuesday')],
            2 => ['name' => _('Wednesday')],
            3 => ['name' => _('Thursday')],
            4 => ['name' => _('Friday')],
            5 => ['name' => _('Saturday')],
            6 => ['name' => _('Sunday')]
        ];

        for($i = 0; $i != 24; $i++) {
            foreach($timetable as &$s) {
                $s[$i] = null;
            }
        }

        foreach ($week as $slot) {
            $carbon = Carbon::now()->setISODate(
                Carbon::now()->year,
                Carbon::now()->weekOfYear,
                $slot->day + 1
            )->setTime($slot->hour, 0)->tz(auth()->check() ? auth()->user()->getTimezone() : 'Europe/London');

            if (Carbon::now()->weekOfYear !== $carbon->weekOfYear) {
                continue;
            }

            $timetable[$carbon->dayOfWeek - 1][$carbon->hour] = [
                'id' => $slot->user->userid,
                'name' => $raw ? $slot->user->getDisplayName()->toHtml() : $slot->user->getDisplayName()
            ];
        }

        return $timetable;
    }
}
