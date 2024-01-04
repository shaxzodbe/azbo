<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Color extends Model
{
    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $color_translation = $this->hasMany(ColorTranslation::class)->where('lang', $lang)->first();
        return $color_translation != null ? $color_translation->$field : $this->$field;
    }

    public function color_translations(){
        return $this->hasMany(ColorTranslation::class);
    }
}
