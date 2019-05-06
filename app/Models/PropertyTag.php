<?php

namespace App\Models;

use App\Models\Base as Model;

class PropertyTag extends Model
{
    protected $table = 'property_tag';

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
