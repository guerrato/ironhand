<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','birthdate', 'image', 'gender', 'phone', 'whatsapp', 'facebook', 'uuid', 'status_id'
    ];
    
    /**
     * Get the status that is related to the user.
     */
    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id', 'id');
    }

    /**
     * Get the ministries that the user coordinates.
     */
    public function ministries()
    {
        return $this->hasMany('App\Models\Ministry', 'coordinator_id', 'id');
    }

    /**
     * Get the groups that the user leads.
     */
    public function groups()
    {
        return $this->hasMany('App\Models\Groups', 'leader_id', 'id');
    }

    /**
     * The groups that the user is member.
     */
    public function members()
    {
        return $this->belongsToMany('App\Models\Groups', 'members', 'group_id', 'member_id')->withTimestamps();
    }

    /**
     * Get the users that is related to the member.
     */
    public function user()
    {
        return $this->hasOne('App\User', 'member_id', 'id');
    }
}
