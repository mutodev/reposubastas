<?php

namespace App\Models;

use App\Models\Base as Model;
use Intervention\Image\ImageManagerStatic as Image;
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
        'optioned_by',
        'source_id',
        'investor_reference_id',
        'address',
        'bedrooms',
        'bathrooms',
        'price',
        'check_number',
        'check_amount',
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
        'image1_thumb',
        'image2_thumb',
        'image3_thumb',
        'image4_thumb',
        'image5_thumb',
        'image6_thumb',
        'image7_thumb',
        'image8_thumb',
        'image9_thumb',
        'image10_thumb',
        'lister_broker',
        'seller_broker',
        'commission',
        'end_at',
        'start_at',
        'optioned_approved_at',
        'optioned_end_at',
        'optioned_price',
        'main_image'
    ];

    public function events()
    {
        return $this->belongsToMany('App\Models\Event', 'property_event')->withPivot('number', 'is_active');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\PropertyStatus', 'status_id');
    }

    public function optionedUser()
    {
        return $this->belongsTo('App\User', 'optioned_by');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\PropertyType', 'type_id', 'id');
    }

    public function getMainImage($postfix = '')
    {
        return $this->getImage($this->main_image, $postfix);
    }

    public function getImage($index = 1, $postfix = '')
    {
        if (!$this["image{$index}"]) {
            return null;
        }

        $image = $this["image{$index}{$postfix}"];
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

    public function proccessImages()
    {
        $s3 = \Storage::disk('s3');

        foreach(range(1, 10) as $index) {
            $image = $this["image{$index}"];

            if (!$image) {
                continue;
            }

            $parts = pathinfo($image);
            $image = Image::make(env('AWS_S3_URL').urlencode($image));
            $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $thumbFilename = "{$this->id}/{$parts['filename']}_thumb.{$parts['extension']}";

            $s3->put($thumbFilename, (string)$image->encode($parts['extension']), 'public');

            $this["image{$index}_thumb"] = $thumbFilename;
        }
    }
}
