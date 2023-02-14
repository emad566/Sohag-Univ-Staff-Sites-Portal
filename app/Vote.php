<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    
    protected $fillable = [
        'user_id',
        'ip',
        'lastVote',
        'val',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
    
}
