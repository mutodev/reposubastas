<?php

namespace App\Models;

use App\Models\Base as Model;
use DB;

class Property extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'status_id',
        'investor_id',
        'source_id',
        'investor_reference_id',
        'address',
        'bedrooms',
        'bathrooms',
        'price',
        'check_number',
        'check_type',
        'bank',
        'deposit',
        'reserve',
        'capacity',
        'open_house_es',
        'open_house_en',
        'sqf_area',
        'sqm_area',
        'cuerdas',
        'internal_number',
        'latitude',
        'longitude',
        'city',
        'description_es',
        'description_en',
        'zonification_es',
        'zonification_en',
        'roof_height',
        'lot_size',
        'levels',
        'amenities_es',
        'amenities_en',
        'region_es',
        'region_en',
        'catastro',
        'image1',
        'image2',
        'image3',
        'image4',
        'image5',
        'image6',
        'image7',
        'image8',
        'image9',
        'image10',
        'lister_broker',
        'seller_broker',
        'commission',
        'end_at',
        'start_at'
    ];

    public function events()
    {
        return $this->belongsToMany('App\Models\Event', 'property_event')->withPivot('number', 'is_active');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\PropertyStatus', 'status_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\PropertyType', 'type_id', 'id');
    }

    public function getImage($index = 1)
    {
        $image = $this["image{$index}"];
        return $image ? env('AWS_S3_URL') . urlencode($image) : null;
    }

    public function getEventData($eventId)
    {
        return DB::table('property_event')
            ->where('property_id', '=', $this->id)
            ->where('event_id', '=', $eventId)->first();
    }

    public function getBids($eventId)
    {
        return Bid::select('bid.*', 'users.name', 'user_event.number')
            ->where('property_id', '=', $this->id)
            ->where('bid.event_id', '=', $eventId)
            ->join('users', 'users.id', '=', 'bid.user_id')
            ->leftJoin('user_event', 'user_event.user_id', '=', 'bid.user_id')
            ->orderBy('bid.created_at', 'desc')->get();
    }

    public function addToEvent($eventId, $number = null, $active = true)
    {
        $this->events()->detach($eventId);
        $this->events()->attach($eventId, [
            'number' => $number,
            'is_active' => $active
        ]);
    }

    public function endAuction($eventId, $statusId = null)
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

        if ($statusId) {
            $this->status_id = $statusId;
            $this->save();
        }

        return $bid;
    }
}
