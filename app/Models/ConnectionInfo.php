<?php
namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Table containing all previous and current SHOUTcast connection information.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 * @property integer $id
 * @property string $ip
 * @property integer $port
 * @property string $password
 * @property integer $added_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|ConnectionInfo whereId($value)
 * @method static Builder|ConnectionInfo whereIp($value)
 * @method static Builder|ConnectionInfo wherePort($value)
 * @method static Builder|ConnectionInfo wherePassword($value)
 * @method static Builder|ConnectionInfo whereAddedBy($value)
 * @method static Builder|ConnectionInfo whereCreatedAt($value)
 * @method static Builder|ConnectionInfo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ConnectionInfo extends Model
{
    protected $table = 'connection_info';
}
