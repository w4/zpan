<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConnectionInfo;
use Illuminate\Http\Request;

/**
 * Admin-facing connection info controller. Allows the administrator to update the connection information displayed
 * to the DJs.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class ConnectionInfoController extends Controller
{
    /**
     * Show the form to update the radio information.
     *
     * @return mixed
     */
    public function getForm()
    {
        $connection = ConnectionInfo::orderBy('id', 'desc')->take(1)->first();

        return view('admin.connection-info', [
            'connection' => $connection
        ]);
    }

    /**
     * Post the form and update the radio information for all the DJs.
     *
     * @param Request $request
     * @return mixed
     */
    public function postForm(Request $request)
    {
        $this->validate($request, [
            'ip' => 'required|string|max:200',
            'port' => 'required|integer|max:65535',
            'password' => 'required|string|max:200'
        ]);

        $connection = new ConnectionInfo;
        $connection->ip = $request->get('ip');
        $connection->port = $request->get('port');
        $connection->password = $request->get('password');
        $connection->added_by = auth()->user()->userid;
        $connection->save();

        return redirect()->back()->with('msg', [
            'type' => 'success',
            'msg' => _('Successfully updated the radio connection information. All DJs can see the new data.')
        ]);
    }
}
