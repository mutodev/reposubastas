<?php

namespace App\Models;

use App\Models\Base as Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title_es',
        'title_en',
        'content_es',
        'content_en',
        'slug_es',
        'slug_en'
    ];
}
