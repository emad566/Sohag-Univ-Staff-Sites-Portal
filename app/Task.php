<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;
    protected $fillable = [
        'user_id',
        'faculty_id',
        'subject_id',
        'title',
        'content',
        'fullDegree',
    ];

    public function subject(){
        return $this->belongsTo('App\Subject');
    }

    public function user(){
        return $this->belongsToThrough('App\User', 'App\Subject');
    }
    
    /*public function user(){
        return $this->belongsTo('App\User');
    }*/

    public function photos(){
        return $this->morphMany('App\Photo', 'Photoable');
    }

    public function files(){
        return $this->morphMany('App\File', 'fileable');
    }

    public function Answers(){
        return $this->hasMany('App\Answer');
    }

    public function stuTaskDegree($task, $answer, &$fullDegrees){
       $isAnswer = $task->Answers()->where('name', '=', $answer->name)->first();
       //return $isAnswer;
       if($isAnswer){
           $fullDegrees += $isAnswer->stuDegree;
           if($isAnswer->stuDegree == null){
               return '
               <a target="_blank" class="print-hide" href="'.url('stuff/answers/show/'.$isAnswer->id.'?p=tasks').'"><span data-toggle="tooltip" data-placement="bottom" title="'.trans('main.edit').'" class="icoHover">No</span></a>
               <span data-toggle="tooltip" data-placement="bottom" title="'.trans('main.edit').'" class="print-show icoHover">No</span>
               ';
           }
           return $isAnswer->stuDegree;
       }
       return "غـ";
    }
}
