<?php
namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * Table containing all booked events by events staff.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 * @property integer $id
 * @property integer $user
 * @property integer $week
 * @property integer $year
 * @property integer $day
 * @property integer $hour
 * @property integer $event_type_id
 * @property integer $room_id
 * @property boolean $approved
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereUser($value)
 * @method static Builder|Event whereWeek($value)
 * @method static Builder|Event whereYear($value)
 * @method static Builder|Event whereDay($value)
 * @method static Builder|Event whereHour($value)
 * @method static Builder|Event whereEventTypeId($value)
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereDeletedAt($value)
 * @mixin Eloquent
 */
class Event extends Model
{
    use SoftDeletes;

    /**
     * Get the type of event this is.
     *
     * @return BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    /**
     * Get the user that this event belongs to.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }
}
