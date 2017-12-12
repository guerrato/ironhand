<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','birthdate', 'image', 'gender', 'phone', 'whatsapp', 'facebook', 'uuid', 'role_id', 'status_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the role that is related to the user.
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }
    
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
        return $this->belongsToMany('App\Models\Groups', 'members', 'group_id', 'user_id')->withTimestamps();
    }
}
