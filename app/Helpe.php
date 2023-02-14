<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Helpe extends Model
{
    protected $fillable = [
        'sName',
        'user-email',
        'userIdPhoto1',
        'userIdPhoto2',
        'userId',
        'user-phone',
        'sWeb',
        'sMessage'
    ];
}
