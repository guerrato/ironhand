<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'member_status';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'slug'
    ];

    /**
     * Get the users that contains have status.
     */
    public function members()
    {
        return $this->hasMany('App\Models\Member', 'status_id', 'id');
    }
}
