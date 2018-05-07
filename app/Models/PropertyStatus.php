<?php

namespace App\Models;

use App\Models\Base as Model;
use DB;

class PropertyStatus extends Model
{
    protected $table = 'property_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_es',
        'name_en'
    ];

    public static function forSelect() {
        $statuses = [];
        $statuses[''] = __('-- Select One --');
        foreach (self::all() as $status) {
            $statuses[$status->id] = $status->name;
        }

        return $statuses;
    }
}
