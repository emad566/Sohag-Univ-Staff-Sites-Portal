<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $uploads = 'uploads/';
    protected $fillable = [
        'name',
        'fileable_id',
        'fileable_type',
        'downloaded',
        'user_id',
        'faculty_id',
    ];

    public function fileable(){
        return $this->morphTo();
    } 
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }

    
}
