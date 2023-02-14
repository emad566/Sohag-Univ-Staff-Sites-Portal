<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'title',
        'content',
        'file_id',
        'user_id',
        'faculty_id',
    ];

    public function file(){
        return $this->belongsTo('App\File');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }
    
    
    
    public function supplements(){
        return $this->hasMany('App\Supplement');
    }
    
    public function tasks(){
        return $this->hasMany('App\Task');
    }
    
    public function files(){
        return $this->morphMany('App\File', 'fileable');
    }

    public function answers(){
        return $this->hasManyThrough('App\Answer', 'App\Task');
    }

    public function researchs(){
        return $this->hasMany('App\Research');
    }
}
