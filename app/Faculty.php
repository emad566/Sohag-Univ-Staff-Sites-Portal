<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = [
        'name',
        'nameEn',
        
    ];

    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }

    public function users(){
        return $this->hasMany('App\User');
    }
    
    public function answer(){
        return $this->belongsTo('App\Answer');
    }
    
    public function advs(){
        return $this->belongsTo('App\Adv');
    }
    
    public function subjects(){
        return $this->hasMany('App\Subject');
    }
    
    public function supplements(){
        return $this->hasMany('App\Supplement');
    }
    
    public function tasks(){
        return $this->hasMany('App\Task');
    }
    
    public function posts(){
        return $this->belongsTo('App\Post');
    }
    
    public function allFiles(){
        return $this->hasMany('App\File')->orderBy('id', 'DESC');
    }

    public function onlines(){
        return $this->hasMany('App\Online')->orderBy('id', 'DESC');
    }
}
