<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    public $dayArr = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس' ];

    protected $fillable = [
        'user_id',
        'office',
        'startTime',
        'endTime',
        'days',
    ];

    public function User(){
        return $this->belongsTo('App\User');
    }

    public function checkSetDaysAttribute($days){
        if(count($days) > 0)
        {
           return implode( ', ' ,$days);
        }
    }

    public function setDaysAttribute($days){
        if(count($days) > 0)
        {
            $this->attributes['days'] = implode( ', ' ,$days);
        }
    }

    public function days2box()
    {
        $daynum = explode(", ",$this->days);
        $officeDays = "";
        $i = 0;
        
        foreach ($this->dayArr as $day) {
            $checked = (in_array($day, $daynum)) ? 'checked' : "";
            $officeDays .= '<li><label for="days' . $i . '"><input type="checkbox" '. $checked .' name="days[]" value="' . $day . '" id="days' . $i . '">' . $day . '</label></li>';
            $i++;
        }
        return $officeDays;
    }
}
