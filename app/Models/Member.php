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
        'name', 'email', 'nickname', 'birthdate', 'image', 'image_name', 'gender', 'phone', 'whatsapp', 'facebook', 'uuid', 'status_id'
    ];

    /**
     * The rules to validate the fillable attibutes.
     */
    public $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:members,email',
        'birthdate' => 'nullable|date',
        'image' => 'nullable|base64image',
        'image_name' => 'nullable|required_with:image',
        'gender' => 'required|in:female,male',
        'facebook' => 'nullable|url',
        'status_id' => 'required|exists:member_status,id',
        'role_id' => 'required|exists:member_roles,id',
        'ministry_id' => 'required|exists:ministries,id'
    ];

    /**
     * Get the role that is related to the user.
     */
    // public function role()
    // {
    //     return $this->belongsToMany('App\Models\MemberRole', 'member_has_roles', 'role_id', 'member_id')
    //         ->withPivot(['ministry_id'])
    //         ->wherePivot('ministry_id', 1)
    //         ->withTimestamps();
    // }

    /**
     * Get the status that is related to the user.
     */
    public function status()
    {
        return $this->belongsTo('App\Models\MemberStatus', 'status_id', 'id');
    }

    /**
     * Get the groups that the user leads.
     */
    public function groups()
    {
        return $this->hasMany('App\Models\Group', 'leader_id', 'id');
    }

    /**
     * Get the roles that the member has in a ministry.
     */
    public function roles(){
        return $this->belongsToMany("App\Models\MemberRole", 'member_has_roles', 'member_id', 'role_id')
        ->withPivot('ministry_id')
        ->withTimestamps();
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
