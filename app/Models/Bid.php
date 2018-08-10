<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $table = 'bid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'property_id',
        'user_id',
        'offer',
        'is_winner'
    ];

    public function property()
    {
        return $this->belongsTo('App\Models\Property', 'property_id');
    }

    public static function boot(){
        parent::boot();

        static::created(function ($instance){

        });
    }
}
