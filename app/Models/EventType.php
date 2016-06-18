<?php
namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * Table containing all types of events a host can host.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 * @property integer $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|EventType whereId($value)
 * @method static Builder|EventType whereName($value)
 * @method static Builder|EventType whereCreatedAt($value)
 * @method static Builder|EventType whereUpdatedAt($value)
 * @mixin Eloquent
 */
class EventType extends Model
{
    use SoftDeletes;
}
