<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demographic extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demographics';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'slug', 'note'
    ];

    /**
     * Get the ministries that has the default demographic.
     */
    public function users()
    {
        return $this->hasMany('App\Models\Ministry', 'default_demographic_id', 'id');
    }
}
