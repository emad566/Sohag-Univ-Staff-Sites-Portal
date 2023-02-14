<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $fillable = [
        'user_id',
        'faculty_id',
        'subject_id',
        'title',
        'facStu_id',
        'department',
        'level_id',
        'content',
    ];

    public function subject(){
        return $this->belongsTo('App\Subject');
    }

    public function faculty(){
        return $this->belongsTo('App\Faculty', 'facStu_id', 'id');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function files(){
        return $this->morphMany('App\File', 'fileable');
    }

    public function answers(){
        return $this->hasMany('App\Resanswer');
    }

    public function level(){
        return $this->belongsTo('App\Level');
    }
}
