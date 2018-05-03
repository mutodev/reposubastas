<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
