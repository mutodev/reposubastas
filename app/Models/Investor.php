<?php

namespace App\Models;

use App\Models\Base as Model;
use DB;

class Investor extends Model
{
    protected $table = 'investor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public static function forSelect($empty = '-- Select One --') {
        $investors = [];
        $investors[''] = __($empty);
        foreach (self::all() as $investor) {
            $investors[$investor->id] = $investor->name;
        }

        return $investors;
    }
}
