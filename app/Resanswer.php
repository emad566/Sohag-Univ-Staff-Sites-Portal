<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resanswer extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'faculty_id',
        'subject_id',
        'research_id',
        'facStu_id',
        'setId',  
        'stuId',
        'pass',
        'file_id',
    ];

    public function research(){
        return $this->belongsTo('App\Research');
    }
    
    public function subject(){
        return $this->belongsTo('App\Subject');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function file(){
        return $this->belongsTo('App\File');
    }

    public function files(){
        return $this->morphMany('App\File', 'fileable');
    }

    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }
}
