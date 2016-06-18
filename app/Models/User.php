<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\HtmlString;
use Watson\Rememberable\Rememberable;

/**
 * vBulletin table containing all users.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use Rememberable;

    protected $connection = 'forum';
    protected $table = 'user';
    protected $primaryKey = 'userid';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'salt'
    ];

    /**
     * Get this user's "remember me" token.
     *
     * @return HasOne
     */
    public function token()
    {
        return $this->hasOne(Token::class);
    }

    /**
     * Get this user's display group.
     *
     * @return Group
     */
    public function getDisplayGroup()
    {
        $display = $this->displaygroupid;

        if (!$display) {
            $display = $this->usergroupid;
        }

        return Group::find($display);
    }

    /**
     * Check if this user is a staff member.
     *
     * @return bool
     */
    public function isStaff()
    {
        $staff = false;

        foreach ($this->usergroups->get() as $group) {
            if ($group->isStaff()) {
                $staff = true;
            }

            if ($group->isBanned()) {
                return false;
            }
        }

        return $staff;
    }

    /**
     * Check if this user is management.
     *
     * @return bool
     */
    public function isManagement()
    {
        foreach ($this->usergroups->get() as $group) {
            if ($group->isManagement()) {
                return true;
            }
        }

        return false;
    }


    /**
     * Check if this user is an administrator.
     *
     * @return bool
     */
    public function isAdmin()
    {
        foreach ($this->usergroups->get() as $group) {
            if ($group->isAdmin()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if this user is apart of a group.
     *
     * @param array $groups
     * @return bool
     * @internal param string $group group to check if the user is apart of
     */
    public function is(...$groups)
    {
        foreach ($this->usergroups->get() as $usergroup) {
            foreach ($groups as $group) {
                if ($usergroup->is($group)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the usergroups this user belongs to.
     *
     * @return mixed
     */
    public function getUsergroupsAttribute()
    {
        if (!$this->relationLoaded('usergroups')) {
            $usergroups = Group::whereIn('usergroupid', $this->membergroupids);
            $this->setRelation('usergroups', $usergroups);
        }

        return $this->getRelation('usergroups');
    }

    /**
     * Get a query containing all the usergroups this user belongs to.
     *
     * @return Group
     */
    public function usergroups()
    {
        return Group::whereIn('usergroupid', $this->membergroupids);
    }

    /**
     * Mutate the weird relationship vBulletin gives us into an array to make it easier to work with.
     *
     * @param $commaSeparatedIds
     * @return mixed
     */
    public function getMembergroupidsAttribute($commaSeparatedIds)
    {
        $groups = json_decode("[{$commaSeparatedIds}]", true);
        array_unshift($groups, $this->usergroupid);

        return $groups;
    }

    /**
     * Get the display name of this user.
     *
     * @return HtmlString
     */
    public function getDisplayName()
    {
        $group = $this->getDisplayGroup();
        return new HtmlString($group->opentag . e($this->username) . $group->closetag);
    }
}
