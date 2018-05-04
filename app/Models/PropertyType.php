<?php

namespace App\Models;

use App\Models\Base as Model;
use DB;

class PropertyType extends Model
{
    protected $table = 'property_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_es',
        'name_en'
    ];
}
