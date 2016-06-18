<?php
namespace App\Http\Controllers\DJ;

use App\Http\Controllers\Controller;
use App\Models\ConnectionInfo;

/**
 * DJ-facing connection info controller. Allow the DJ to view the connection info.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class ConnectionInfoController extends Controller
{
    /**
     * View the connection info to the SHOUTcast server.
     *
     * @return mixed
     */
    public function viewConnection()
    {
        $connection = ConnectionInfo::orderBy('id', 'desc')->take(1)->first();

        return view('dj.connection-info', [
            'connection' => $connection
        ]);
    }
}
