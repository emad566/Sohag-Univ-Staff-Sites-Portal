<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adv extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'faculty_id',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }

    public function files(){
        return $this->morphMany('App\File', 'fileable');
    }

    public function photos(){
        return $this->morphMany('App\Photo', 'Photoable');
    }

}
