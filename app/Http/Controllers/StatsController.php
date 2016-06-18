<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\ConnectionInfo;
use LastFmApi\Api\AuthApi;
use LastFmApi\Api\TrackApi;

class StatsController extends Controller
{
    /**
     * Return the current radio stats.
     *
     * @return array
     */
    public function index()
    {
        // we need old values so we can't use Laravel's Cache class.
        $time = @filemtime(storage_path('stats.json'));

        if (!$time || (time() - $time >= 15)) {
            $connection = ConnectionInfo::orderBy('id', 'desc')->take(1)->first();

            $json = file_get_contents("http://{$connection->ip}:{$connection->port}/stats?json=1");
            $stats = json_decode($json);

            $ret = [];

            if (empty($stats->songtitle)) {
                $ret = ['status' => false];
            } else {
                $old = json_decode(file_get_contents(storage_path('stats.json')));

                $ret['dj'] = empty($json->dj) ? $old->dj : $stats->dj;
                $ret['listeners'] = $stats->listeners;

                if (count(explode(' - ', $json->songtitle, 2)) == 2) {
                    list($artist, $song) = explode(' - ', $json->songtitle, 2);
                    $ret['artist'] = $artist;
                    $ret['song'] = $song;

                    if ($stats->songtitle !== "{$old->artist} - {$old->song}") {
                        $lastfm = new AuthApi('getsession', [
                            'apiKey' => env('LASTFM_API_KEY'),
                            'secret' => env('LASTFM_SECRET'),
                            'token' => env('LASTFM_TOKEN')
                        ]);
                        $trackApi = new TrackApi($lastfm);
                        $trackApi->scrobble(['artist' => $artist, 'track' => $song, 'timestamp' => 400]);
                    }
                } else {
                    $ret['song'] = $json->songtitle;
                }

                file_put_contents(storage_path('stats.json'), json_encode($ret));
            }

            return $ret;
        } else {
            return json_decode(file_get_contents(storage_path('stats.json')), true);
        }
    }
}
