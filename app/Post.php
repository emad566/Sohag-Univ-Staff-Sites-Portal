<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'year',
        'type',
        'tags',
        'auther',
        'url',
        'urlTitle',
       'content',
        'file_id',
        'photo_id',
        'user_id',
        'faculty_id',
        'journal',
        'num',
        'yearNum',
    ];

    public function photo(){
        return $this->belongsTo('App\Photo');
    }
    
    public function file(){
        return $this->belongsTo('App\File');
    }

    public function files(){
        return $this->morphMany('App\File', 'fileable');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }
    
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'tagable');
    }
}
