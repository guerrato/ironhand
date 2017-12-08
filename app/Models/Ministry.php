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
        'name', 'slug', 'description', 'requires_gender', 'coordinator_id', 'default_demographic_id'
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
}
