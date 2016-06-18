<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * Table containing all booked slots by DJ staff.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 * @property integer $id
 * @property integer $dj
 * @property integer $week
 * @property integer $year
 * @property integer $day
 * @property integer $hour
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * @method static Builder|Timetable whereId($value)
 * @method static Builder|Timetable whereDj($value)
 * @method static Builder|Timetable whereWeek($value)
 * @method static Builder|Timetable whereYear($value)
 * @method static Builder|Timetable whereDay($value)
 * @method static Builder|Timetable whereHour($value)
 * @method static Builder|Timetable whereCreatedAt($value)
 * @method static Builder|Timetable whereUpdatedAt($value)
 * @method static Builder|Timetable whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Timetable extends Model
{
    use SoftDeletes;

    /**
     * Get the user that this slot belongs to.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'dj');
    }
}
