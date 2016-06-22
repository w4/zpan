<?php
namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Table containing all user groups on the forum.
 *
 * @mixin Eloquent
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class Group extends Model
{
    protected $connection = 'forum';
    protected $table = 'usergroup';
    protected $primaryKey = 'usergroupid';
    public $timestamps = false;

    const STAFF_PREFIX = '[STAFF]';
    const GUEST_DJ = 'Guest DJ';
    const RADIO_DJ = 'Radio DJ';
    const HEAD_DJ = 'Head DJ';
    const EVENT = 'Events';
    const SENIOR_EVENTS = 'Senior Events';
    const MANAGEMENT = 'Management';
    const ADMINISTRATOR = 'Administrator';
    const OWNERSHIP = 'Ownership';
    const BANNED = 'Banned Users';

    /**
     * Check if this group is the specified one. Use constants in this class (ie. Group::RADIO_DJ)
     *
     * @param $name string group to check this group is.
     * @return bool
     */
    public function is($name)
    {
        $group = $this->title;

        if ($this->isManagement()) {
            return true;
        }

        return str_contains($group, $name) && $this->isStaff();
    }

    /**
     * Check if this group is an admin group.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->adminpermissions === 3;
    }

    public function isManagement()
    {
        $group = $this->title;

        if ($this->isAdmin()) {
            return true;
        }

        return ends_with($group, static::MANAGEMENT) && $this->isStaff();
    }

    /**
     * Check if this group is a banned group.
     *
     * @return bool
     */
    public function isBanned()
    {
        return str_contains($this->title, static::BANNED);
    }

    /**
     * Check if this group is a staff group.
     *
     * @return bool
     */
    public function isStaff()
    {
        return starts_with($this->title, static::STAFF_PREFIX) && !$this->isBanned();
    }
}
