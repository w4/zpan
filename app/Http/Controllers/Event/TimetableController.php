<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Allow events staff to view this week's timetable.
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
        return view('events.timetable', ['timetable' => $this->getJSONTimetable(false)]);
    }

    /**
     * Show the form to the user to book a slot.
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

        return view('events.book-slot');
    }

    /**
     * Book a slot for the user.
     *
     * @param Request $request
     * @return mixed
     */
    public function save(Request $request)
    {
        $this->validate($request, [
            'day' => 'required|integer|min:0|max:6',
            'hour' => 'required|integer|min:0|max:23',
            'event_type' => 'required|exists:event_types,name,deleted_at,NULL',
            'room_id' => 'required|integer|regex:/^[\d]{8,8}$/'
        ]);

        $carbon = Carbon::now(auth()->user()->getTimezone())->setISODate(
            Carbon::now()->year,
            Carbon::now()->weekOfYear,
            $request->get('day')
        )->setTime($request->get('hour'), 0)->tz(config('app.timezone'));

        if (Carbon::now()->weekOfYear !== $carbon->weekOfYear) {
            if ($request->ajax()) {
                return [
                    'error' => true,
                    'msg' => _('This slot will be unbooked when the timetable is cleared on Monday at 00:00 GMT.')
                ];
            } else {
                return redirect()
                    ->back()
                    ->with('msg', [
                        'type' => 'danger',
                        'msg' => _('This slot will be unbooked when the timetable is cleared on Monday at 00:00 GMT.')
                    ])
                    ->with('tab', $request->get('day'));
            }
        }

        $slot = Event::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('day', $carbon->dayOfWeek)
            ->where('hour', $carbon->hour)
            ->where('approved', true)
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
                    ->route('dashboard::event::timetable')
                    ->with('msg', ['type' => 'danger', 'msg' => _('This slot is already booked.')])
                    ->with('tab', $request->get('day'));
            }
        }

        $slot = Event::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('day', $carbon->dayOfWeek)
            ->where('hour', $carbon->hour)
            ->where('user', auth()->user()->userid)
            ->where('approved', false)
            ->count();

        if ($slot !== 0) {
            // this user has already submitted a request for this slot
            if ($request->ajax()) {
                return [
                    'error' => true,
                    'msg' => _('You have already submitted a request for this slot.')
                ];
            } else {
                return redirect()
                    ->route('dashboard::event::timetable')
                    ->with('msg', [
                        'type' => 'danger',
                        'msg' => _('You have already submitted a request for this slot.')
                    ])
                    ->with('tab', $request->get('day'));
            }
        }

        $slot = new Event;
        $slot->year = Carbon::now()->year;
        $slot->week = Carbon::now()->weekOfYear;
        $slot->day = $carbon->dayOfWeek;
        $slot->hour = $carbon->hour;
        $slot->event_type_id = EventType::whereName($request->get('event_type'))->firstOrFail()->id;
        $slot->user = auth()->user()->userid;
        $slot->room_id = $request->get('room_id');
        $slot->approved = false;
        $slot->save();

        if ($request->ajax()) {
            return [
                'error' => false,
                'msg' => _('Successfully booked slot.')
            ];
        } else {
            return redirect()
                ->route('dashboard::event::timetable')
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

        $slot = Event::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('day', $request->get('day'))
            ->where('hour', $request->get('hour'))
            ->where('approved', true)
            ->take(1);

        if ($slot->first()->user !== auth()->user()->userid && !auth()->user()->isAdmin()) {
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
     * Get the current event.
     *
     * @return array
     */
    public function getCurrentEvent()
    {
        $event = Event::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('day', Carbon::now()->format('N') - 1)
            ->where('hour', Carbon::now()->hour)
            ->where('approved', true)
            ->first();

        if ($event) {
            return [
                'id' => $event->user()->first()->userid,
                'name' => $event->user()->first()->getDisplayName()->toHtml(),
                'type' => $event->type->name,
                'room' => $event->room_id,
                'booked' => true
            ];
        } else {
            return [
                'booked' => false
            ];
        }
    }

    /**
     * Get the booked slots for this week in JSON format.
     *
     * @param bool $raw should we return the user's name in raw html
     * @return array
     */
    public function getJSONTimetable($raw = true)
    {
        $week = Event::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('approved', true)
            ->get();

        $timetable = [
            0 => ['name' => _('Monday')],
            1 => ['name' => _('Tuesday')],
            2 => ['name' => _('Wednesday')],
            3 => ['name' => _('Thursday')],
            4 => ['name' => _('Friday')],
            5 => ['name' => _('Saturday')],
            6 => ['name' => _('Sunday')]
        ];

        for ($i = 0; $i != 24; $i++) {
            foreach ($timetable as &$s) {
                $s[$i] = null;
            }
        }

        foreach ($week as $slot) {
            if (!$slot->type) {
                // the type this slot belonged to has gone.
                $slot->delete();
                continue;
            }

            $type = $slot->type->name;

            $carbon = Carbon::now()->setISODate(
                Carbon::now()->year,
                Carbon::now()->weekOfYear,
                $slot->day
            )->setTime($slot->hour, 0)->tz(auth()->check() ? auth()->user()->getTimezone() : 'Europe/London');

            if (Carbon::now()->weekOfYear !== $carbon->weekOfYear) {
                continue;
            }

            $timetable[$carbon->dayOfWeek][$carbon->hour] = [
                'id' => $slot->user()->first()->userid,
                'name' => $raw ? $slot->user()->first()->getDisplayName()->toHtml() :
                    $slot->user()->first()->getDisplayName(),
                'type' => $type
            ];
        }

        return $timetable;
    }
}
