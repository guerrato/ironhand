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
        'name', 'slug', 'description', 'required_gender', 'coordinator_id', 'default_demographic_id'
    ];

    /**
     * The rules to validate the fillable attibutes.
     */
    public $rules = [
        'name' => 'required|unique:ministries,name',
        'slug' => 'unique:ministries,slug',
        'description' => 'nullable',
        'required_gender' => 'nullable|in:female,male',
        'coordinator_id' => 'required|exists:users,id',
        'default_demographic_id' => 'required|exists:demographics,id',
    ];

    /**
     * Get the user that coordinate the ministry.
     */
    public function coordinator()
    {
        return $this->belongsTo('App\User', 'coordinator_id', 'id');
    }

    /**
     * Get the default demographic of the ministry.
     */
    public function defaultDemographic()
    {
        return $this->belongsTo('App\Models\Demographic', 'default_demographic_id', 'id');
    }

    /**
     * Get the groups that belongs to the ministry.
     */
    public function groups()
    {
        return $this->hasMany('App\Models\Groups', 'ministry_id', 'id');
    }
}
