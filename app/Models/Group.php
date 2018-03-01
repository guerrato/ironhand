<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'slug', 'required_gender', 'ministry_id', 'leader_id'
    ];

    /**
     * The rules to validate the fillable attibutes.
     */
    public $rules = [
        'description' => 'required',
        'slug' => 'unique:groups,slug',
        'required_gender' => 'nullable|in:female,male',
        'ministry_id' => 'required|exists:ministries,id',
        'leader_id' => 'required|exists:members,id'
    ];

    /**
     * Get the ministry which the group belongs to.
     */
    public function ministry()
    {
        return $this->belongsTo('App\Models\Ministry', 'ministry_id', 'id');
    }

    /**
     * Get the user that leads the group.
     */
    public function leader()
    {
        return $this->belongsTo('App\Models\Member', 'leader_id', 'id');
    }

    /**
     * The members that belong to the group.
     */
    public function members()
    {
        return $this->belongsToMany('App\Models\Member', 'members_in_group', 'group_id', 'member_id')->withTimestamps();
    }
}
