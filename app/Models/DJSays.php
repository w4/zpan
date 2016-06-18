<?php
namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Watson\Rememberable\Rememberable;

/**
 * Table containing all previous and current DJ statuses displayed on the main site.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 * @property integer $id
 * @property integer $dj
 * @property string $msg
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|DJSays whereId($value)
 * @method static Builder|DJSays whereDj($value)
 * @method static Builder|DJSays whereMsg($value)
 * @method static Builder|DJSays whereCreatedAt($value)
 * @method static Builder|DJSays whereUpdatedAt($value)
 * @mixin Eloquent
 */
class DJSays extends Model {
    use Rememberable;

    protected $table = 'dj_says';
}
