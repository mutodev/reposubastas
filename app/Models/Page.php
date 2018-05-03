<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

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
    ];
}
