<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplement extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;
    protected $fillable = [
        'title',
        'content',
        'file_id',
        'subject_id',
        'user_id',
        'faculty_id',
    ];

    public function user(){
        return $this->belongsToThrough('App\User', 'App\Subject');
    }
    
    /*public function user(){
        return $this->belongsTo('App\User');
    }*/
    
    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }
    
    public function subject(){
        return $this->belongsTo('App\Subject');
    }

    public function files(){
        return $this->morphMany('App\File', 'fileable');
    }
    
}
