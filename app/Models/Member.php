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
        'name', 'email', 'nickname', 'birthdate', 'image', 'image_name', 'gender', 'phone', 'whatsapp', 'facebook', 'uuid', 'role_id', 'status_id'
    ];

    /**
     * The rules to validate the fillable attibutes.
     */
    public $rules = [
        'name' => 'required',
        'email' => 'nullable|email|required_if:member_role,2,3',
        'birthdate' => 'nullable|date',
        'image' => 'nullable|base64image',
        'image_name' => 'nullable|required_with:image',
        'gender' => 'required|in:female,male',
        'facebook' => 'nullable|url',
        'role_id' => 'required|exists:member_roles,id',
        'status_id' => 'required|exists:member_status,id'
    ];

    /**
     * Get the role that is related to the user.
     */
    public function role()
    {
        return $this->belongsTo('App\Models\MemberRole', 'role_id', 'id');
    }
    
    /**
     * Get the status that is related to the user.
     */
    public function status()
    {
        return $this->belongsTo('App\Models\MemberStatus', 'status_id', 'id');
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
