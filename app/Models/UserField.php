<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

/**
 * Custom fields set by the user on the forum.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class UserField extends Model
{
    use Rememberable;

    protected $connection = 'forum';
    protected $table = 'userfield';
    protected $primaryKey = 'userid';
    public $timestamps = false;
}
