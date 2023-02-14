<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'ar',
        'en',
    ];

    public function researchs(){
        return $this->hasMany('App\Research');
    }
}
