<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Base extends Model
{
    public function __get($property) {
        $localeProperty = "{$property}_".App::getLocale();
        return in_array($localeProperty, $this->fillable) ? $this[$localeProperty] : $this[$property];
    }
}
