<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestBan;
use Illuminate\Http\Request;

class RequestLineBanController extends Controller {
    /**
     * Show the administrator a list of all bans.
     *
     * @return mixed
     */
    public function index()
    {
        return view('admin.request-bans', [
            'bans' => RequestBan::orderBy('id', 'desc')->paginate(15)
        ]);
    }

    /**
     * Show the administrator a form to ban an IP address.
     *
     * @return mixed
     */
    public function banForm()
    {
        return view('admin.request-bans-form');
    }

    /**
     * Ban an IP from the request line
     *
     * @param Request $request
     * @return mixed
     */
    public function ban(Request $request)
    {
        $this->validate($request, [
            'ip' => 'required|ip|unique:request_bans,ip_address,NULL,id,deleted_at,NULL'
        ]);

        $ban = new RequestBan;
        $ban->ip_address = $request->get('ip');
        $ban->added_by = auth()->user()->userid;
        $ban->save();

        return redirect()->route('dashboard::admin::request-ban')->with('msg', [
            'type' => 'success',
            'msg' => _('Successfully banned IP address from the request line.')
        ]);
    }

    /**
     * Unban an IP from the request line.
     *
     * @param int $id id of the ip to unban
     * @return mixed
     */
    public function unban(int $id)
    {
        RequestBan::findOrFail($id)->delete();

        return redirect()->back()->with('msg', [
            'type' => 'success',
            'msg' => _('Successfully unbanned IP from the request line.')
        ]);
    }
}
