<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use Illuminate\Http\Request;

/**
 * Allow management to add and remove event types.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class EventTypeController extends Controller
{
    /**
     * Show the manager a list of types.
     *
     * @return mixed
     */
    public function index()
    {
        return view('management.event-types', [
            'types' => EventType::orderBy('name', 'asc')->paginate(15)
        ]);
    }

    /**
     * Show the manager a form to add a new event type.
     *
     * @return mixed
     */
    public function form()
    {
        return view('management.event-types-form');
    }

    /**
     * Add an event type
     *
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3'
        ]);

        $ban = new EventType();
        $ban->name = $request->get('name');
        $ban->save();

        return redirect()->route('dashboard::management::event-type')->with('msg', [
            'type' => 'success',
            'msg' => _('Successfully added a new event type.')
        ]);
    }

    /**
     * Remove an event type.
     *
     * @param int $id id of the event type to delete.
     * @return mixed
     */
    public function delete(int $id)
    {
        EventType::findOrFail($id)->delete();

        return redirect()->back()->with('msg', [
            'type' => 'success',
            'msg' => _('Successfully removed the event type.')
        ]);
    }
}
