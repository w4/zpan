<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * Table containing all requests sent in by users.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 * @property integer $id
 * @property string $name
 * @property string $request
 * @property string $ip_address
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * @method static Builder|Request whereId($value)
 * @method static Builder|Request whereName($value)
 * @method static Builder|Request whereRequest($value)
 * @method static Builder|Request whereIpAddress($value)
 * @method static Builder|Request whereCreatedAt($value)
 * @method static Builder|Request whereUpdatedAt($value)
 * @method static Builder|Request whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Request extends Model
{
    use SoftDeletes;
}
