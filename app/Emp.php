<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emp extends Model
{
    protected $fillable = [
        'ar',
        'en',
    ];

    public function users(){
        return $this->hasMany('App\User');
    }

    public function emp(){
        return $this->belongsTo('App\Emp');
    }
}
