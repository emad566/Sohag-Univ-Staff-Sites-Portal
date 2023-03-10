<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'user_id',
        'ip',
        'lastVisit',
        'times',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
