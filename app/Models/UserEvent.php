<?php

namespace App\Models;

use App\Models\Base as Model;

class UserEvent extends Model
{
    protected $table = 'user_event';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'original_deposit',
        'remaining_deposit',
        'number',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo('App\Event', 'event_id', 'id');
    }
}
