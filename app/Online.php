<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Online extends Model
{
    protected $fillable = [
        'user_id',
        'faculty_id',
        'lastvisit',
        'url',
        'ip',
        'updated_at',
        'created_at',
        
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }
}
