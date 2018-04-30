<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'address',
        'bedrooms',
        'bathrooms',
        'price',
        'open_house',
        'sqf_area',
        'sqm_area',
        'internal_number',
        'latitude',
        'longitude'
    ];

    public function events()
    {
        return $this->belongsToMany('App\Models\Event', 'property_event')->withPivot('number', 'is_active');
    }

    public function addToEvent($eventId, $number = null, $active = true)
    {
        $this->events()->detach($eventId);
        $this->events()->attach($eventId, [
            'number' => $number,
            'is_active' => $active
        ]);
    }

    public function endAuction($eventId)
    {
        $bid = Bid::select('bid.*', 'users.name', 'user_event.number')->where('property_id', '=', $this->id)
            ->where('bid.event_id', '=', $eventId)
            ->join('users', 'users.id', '=', 'bid.user_id')
            ->join('user_event', 'user_event.user_id', '=', 'bid.user_id')
            ->orderBy('bid.created_at', 'desc')->first();

        if ($bid) {
            $bid->is_winner = true;
            $bid->save();
        }

        return $bid;
    }
}
