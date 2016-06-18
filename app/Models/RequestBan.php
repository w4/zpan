<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Table containing IPs of everyone that is banned from the request line.
 *
 * @property integer $id
 * @property string $ip_address
 * @property integer $added_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class RequestBan extends Model
{
    use SoftDeletes;
}
