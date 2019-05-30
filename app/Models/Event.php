<?php

namespace App\Models;

use App\Models\Base as Model;
use App\User;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'location',
        'start_at',
        'live_at',
        'end_at',
        'is_active',
        'is_online'
    ];

    public function properties()
    {
        return $this->belongsToMany('App\Models\Property', 'property_event', 'event_id', 'property_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_event', 'event_id', 'user_id')
            ->withPivot('number', 'original_deposit', 'remaining_deposit', 'is_active');
    }

    public function getRegisteredUsers()
    {
        return User::select('users.*', 'users.name', 'user_event.number')
            ->where('user_event.event_id', '=', $this->id)
            ->where('user_event.is_active', '=', true)
            ->join('user_event', 'user_event.user_id', '=', 'users.id')->get();
    }
}
