<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ministries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description', 'required_gender'
    ];

    /**
     * The rules to validate the fillable attibutes.
     */
    public $rules = [
        'name' => 'required|unique:ministries,name',
        'slug' => 'unique:ministries,slug',
        'description' => 'nullable',
        'required_gender' => 'nullable|in:female,male',
    ];

    /**
     * Get the groups that belongs to the ministry.
     */
    public function groups()
    {
        return $this->hasMany('App\Models\Groups', 'ministry_id', 'id');
    }

    /**
     * Get the members that the ministry into have the roles.
     */
    public function members(){
        return $this->belongsToMany("App\Models\Member", 'member_has_roles', 'ministry_id', 'member_id')
        ->withPivot('role_id')
        ->withTimestamps();
    }

    /**
     * Get the member roles.
     */
    public function roles(){
        return $this->belongsToMany("App\Models\MemberRole", 'member_has_roles', 'ministry_id', 'role_id')
        ->withPivot('ministry_id')
        ->withTimestamps();
    }
}
