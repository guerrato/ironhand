<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
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
        'description', 'slug', 'ministry_id', 'leader_id'
    ];

    /**
     * The rules to validate the fillable attibutes.
     */
    public $rules = [
        'description' => 'required',
        'slug' => 'unique:groups,slug',
        'ministry_id' => 'required|exists:ministries,id',
        'leader_id' => 'required|exists:users,id'
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
        return $this->belongsTo('App\User', 'leader_id', 'id');
    }

    /**
     * The members that belong to the group.
     */
    public function members()
    {
        return $this->belongsToMany('App\User', 'members', 'group_id', 'user_id');
    }
}
