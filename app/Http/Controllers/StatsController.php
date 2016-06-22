<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\ConnectionInfo;
use App\Models\Timetable;
use Carbon\Carbon;
use LastFmApi\Api\AuthApi;
use LastFmApi\Api\TrackApi;
use stdClass;

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
                $old = file_exists(storage_path('stats.json')) ?
                    json_decode(file_get_contents(storage_path('stats.json'))) : false;

                $ret['dj'] = empty($stats->dj) ? ($old ? $old->dj : 'Unset') : $stats->dj;
                $ret['listeners'] = $stats->currentlisteners;

                if (!$ret['dj']) {
                    $timetable = Timetable::where('week', Carbon::now()->weekOfYear)
                        ->where('year', Carbon::now()->year)
                        ->where('day', Carbon::now()->format('N') - 1)
                        ->where('hour', Carbon::now()->hour)
                        ->first();

                    $ret['dj'] = $timetable ? $timetable->user->getDisplayName()->toHtml() : 'Offline';
                }

                if (count(explode(' - ', $stats->songtitle, 2)) == 2) {
                    list($artist, $song) = explode(' - ', $stats->songtitle, 2);
                    $ret['artist'] = $artist;
                    $ret['song'] = $song;

                    /*if ($stats->songtitle !== "{$old->artist} - {$old->song}") {
                        $lastfm = new AuthApi('getsession', [
                            'apiKey' => env('LASTFM_API_KEY'),
                            'secret' => env('LASTFM_SECRET'),
                            'token' => env('LASTFM_TOKEN')
                        ]);
                        $trackApi = new TrackApi($lastfm);
                        $trackApi->scrobble(['artist' => $artist, 'track' => $song, 'timestamp' => 400]);
                    }*/
                } else {
                    $ret['song'] = $stats->songtitle;
                }

                file_put_contents(storage_path('stats.json'), json_encode($ret));
            }

            return $ret;
        } else {
            return json_decode(file_get_contents(storage_path('stats.json')), true);
        }
    }
}
