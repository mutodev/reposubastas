<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
