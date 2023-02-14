<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'subject_id',
        'task_id',
        'faculty_id',
        'department',
        'level',
        'email',
        'setId',
        'menuId',   
        'nots',
        'stuId',
        'stuDegree',
    ];

    public function task(){
        return $this->belongsTo('App\Task');
    }
    
    public function subject(){
        return $this->belongsTo('App\Subject');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function files(){
        return $this->morphMany('App\File', 'fileable');
    }

    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }
}
