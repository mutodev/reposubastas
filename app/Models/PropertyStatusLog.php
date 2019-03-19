<?php

namespace App\Models;

use App\Models\Base as Model;
use DB;

class PropertyStatusLog extends Model
{
    protected $table = 'property_status_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_id',
        'old_status_id',
        'new_status_id',
        'optioned_by',
        'payload'
    ];

    public function oldStatus()
    {
        return $this->belongsTo('App\Models\PropertyStatus', 'old_status_id');
    }

    public function newStatus()
    {
        return $this->belongsTo('App\Models\PropertyStatus', 'new_status_id');
    }

    public function optionedBy()
    {
        return $this->belongsTo('App\User', 'optioned_by');
    }
}
