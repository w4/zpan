<?php
namespace App\Http\Controllers\Event\Senior;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;

/**
 * Allow a senior event staff member to accept or deny events.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class EventsTimetableController extends Controller
{
    public function index()
    {
        $unapproved = Event::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('approved', false)
            ->orderBy('id', 'desc')
            ->get();

        return view('events.senior.events-timetable', ['unapproved' => $unapproved]);
    }

    /**
     * Approve an event and delete every other event for that hour.
     *
     * @param $id
     * @return mixed
     */
    public function approve($id)
    {
        $event = Event::findOrFail($id);

        if ($event->approved) {
            return redirect()->back()->with('msg', [
                'type' => 'success',
                'msg' => _('This event has already been approved.')
            ]);
        }

        $others = Event::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('day', $event->day)
            ->where('hour', $event->hour)
            ->where('approved', true)
            ->count();

        if ($others) {
            // there is already an approved event in this slot.
            return redirect()->back()->with('msg', [
                'msg' => _('There is already an approved event in this slot.'),
                'type' => 'danger'
            ]);
        }

        // approve this event
        $event->approved = true;
        $event->save();

        // delete all the other unapproved events that wanted this slot.
        Event::where('week', Carbon::now()->weekOfYear)
            ->where('year', Carbon::now()->year)
            ->where('day', $event->day)
            ->where('hour', $event->hour)
            ->where('approved', false)
            ->delete();

        return redirect()->back()->with('msg', [
            'msg' => _('Successfully approved event and deleted other events which wanted this slot.'),
            'type' => 'success'
        ]);
    }

    /**
     * Deny an event.
     *
     * @param $id
     * @return mixed
     */
    public function deny($id)
    {
        $event = Event::findOrFail($id);

        if ($event->approved) {
            return redirect()->back()->with('msg', [
                'type' => 'success',
                'msg' => _('This event has already been approved.')
            ]);
        }

        $event->delete();

        return redirect()->back()->with('msg', [
            'msg' => _('Successfully declined event.'),
            'type' => 'success'
        ]);
    }
}
