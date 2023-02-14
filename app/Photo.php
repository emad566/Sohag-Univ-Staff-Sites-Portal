<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public $uploads = 'uploads/';
    protected $fillable = [
        'name',
        'photoable_id',
        'photoable_type',
    ];

    public function photoable(){
        return $this->morphTo();
    }
    
}
