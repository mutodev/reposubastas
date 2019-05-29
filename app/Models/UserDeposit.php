<?php

namespace App\Models;

use App\Models\Base as Model;

class UserDeposit extends Model
{
    protected $table = 'user_deposit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'user_id',
        'property_id',
        'refunded'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
