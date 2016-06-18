<?php
namespace App\Http\Controllers\DJ;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Models\RequestBan;
use Illuminate\Http\Request as HttpRequest;
use Vinkla\Pusher\Facades\Pusher;

/**
 * Allow the DJ to view requests submitted by users of the site.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class RequestController extends Controller
{
    /**
     * Get the request list.
     *
     * @return mixed
     */
    public function getList()
    {
        $requests = Request::orderBy('id', 'desc')->paginate(15);
        return view('dj.requests', ['requests' => $requests]);
    }

    /**
     * Soft delete the request from the list.
     *
     * @param int $id id to delete
     * @return mixed
     */
    public function deleteRequest(int $id)
    {
        Request::findOrFail($id)->delete();
        return redirect()->back()->with('msg', ['type' => 'success', 'msg' => _('Successfully deleted request.')]);
    }

    /**
     * Submit a request which a DJ can then view.
     *
     * @param HttpRequest $request
     * @return mixed
     */
    public function request(HttpRequest $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:30',
            'request' => 'required|string|max:500'
        ]);

        if (RequestBan::where('ip_address', $request->ip())->count()) {
            // This IP is banned from the request line.
            abort(403);
        }

        $r = new Request;
        $r->name = $request->get('name');
        $r->request = $request->get('request');
        $r->ip_address = $request->ip();
        $r->save();

        Pusher::trigger('private-dj', 'request', ['msg' => str_limit(e($r->request)), 'sender' => e($r->name)]);

        return ['type' => 'success', 'msg' => _('Successfully submitted your request. We\'ll let the DJ know!')];
    }
}
