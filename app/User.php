<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Vote;
use App\File;
// include('ArPHP.phar');

class User extends Authenticatable
{
    use Notifiable;
   

    public $ruleArr = [
        'مدير و عضو' => 'Manager and Stuff',
        'عضو' => 'Stuff',
        'مدير' => 'Manager',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'isActive',
        'role_id',
        'fullName',
        'brithDate',
        'degree',
        'currentPosition',
        'faculty_id',
        'photo_id',
        'webIntro',
        'title',
        'phone',
        'fax',
        'mobile',
        'fb',
        'twitter',
        'yt',
        'linkedIn',
        'googlePlus',
        'schooler',
        'researchGate',
        'EKP',
        'contentCV',
        'sex',
        'lang',
        'mostContent',
        'mostView',
        'mostActive',
        'userID',
        'lastLoged',
        'islogin',
        'countTime',
        'verify',
        'general',
        'special',
        'masterar',
        'masteren',
        'phdar',
        'phden',
        'positions',
        'race',
        'raceMark',
        'emailVerify',

    ];

    public function uploads($dep =""){
        if ($dep == ""){ 
            return 'uploads/' . $this->id . '/';
        }else{
            return 'uploads/' . $dep . '/'. $this->id . '/';
        }
    }
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($password){
        if(!empty($password))
        {
            $this->attributes['password'] = bcrypt($password);
        }
    }
    
    /* public function getCountTimeAttribute($countTime){
        return intval($countTime/60);
    } */
    
    public function userfield(){
        return $this->belongsTo('App\Userfield');
    }
    
    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function photo(){
        return $this->belongsTo('App\Photo');
    }
    
    public function file(){
        return $this->belongsTo('App\File');
    }

    public function subjects(){
        return $this->hasMany('App\Subject')->orderBy('id', 'DESC');
    }
    
    public function allFiles(){
        return $this->hasMany('App\File')->orderBy('id', 'DESC');
    }

    public function visitors(){
        return $this->hasMany('App\Visitor');
    }

    public function votes(){
        return $this->hasMany('App\Vote');
    }
    
    public function advs(){
        return $this->hasMany('App\Adv')->orderBy('id', 'DESC');;
    }

    public function faculty(){
        return $this->belongsTo('App\Faculty');
    }

    public function emp()
    {
        return $this->belongsTo('App\Emp', 'degree', 'id');
    }

    public function posts(){
        return $this->hasMany('App\Post')->orderBy('year', 'DESC');;
    }

    public function Offices(){
        return $this->hasMany('App\Office')->orderBy('id', 'DESC');
    }

    public function files(){
        return $this->morphMany('App\File', 'fileable');
    }

    public function tasks(){
        return $this->hasManyThrough('App\Task', 'App\Subject')->orderBy('id', 'DESC');
    }
    
    /*public function tasks(){
        return $this->hasMany('App\Task')->orderBy('id', 'DESC');
    }*/

    public function answers(){
        return $this->hasManyThrough('App\Task', 'App\Subject')->orderBy('id', 'DESC');
    }
    
    /*public function answers(){
        return $this->hasMany('App\Answer')->orderBy('id', 'DESC');
    }*/

    public function researchs(){
        return $this->hasMany('App\Research')->orderBy('id', 'DESC');
    }
    
    public function supplements(){
        return $this->hasManyThrough('App\Supplement', 'App\Subject')->orderBy('id', 'DESC');
    }
    
    /*public function supplements(){
        return $this->hasMany('App\Supplement')->orderBy('id', 'DESC');
    }*/

    public function onlines(){
        return $this->hasMany('App\Online')->orderBy('id', 'DESC');
    }
    

    public function isActive(){
        if($this->isActive == 1)
        {
            return true;
        }
        return false;
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function answersCounts($id = 'default'){
        if($id == 'default') $id = $this->id;
        $Counts = DB::table('answers')
                    ->select('answers.id', 'users.id')
                    ->join('tasks', 'tasks.id', '=', 'answers.task_id')
                    ->join('subjects', 'subjects.id', '=', 'tasks.subject_id')
                    ->join('users', 'users.id', '=', 'subjects.user_id')
                    ->select('answers.*')
                    ->where('users.id', '=', $id)
                    ->get()->count();
        return $Counts;
    }

    public function getMostViewAttribute(){
        $visitorsCount = $this->visitors()->count();
        if($visitorsCount > 999){
            return round ($visitorsCount/1000, 1) . 'K';
        }
        return $visitorsCount;
    }
    
    public $getCountTime = true;
    public function getCountTimeAttribute($value){
        if($this->getCountTime){
            $countTime = intval($value/60);
            if($countTime > 1000){
                return round ($countTime/1000, 1) . 'K';
            }
            return $countTime;
        }
        
        return $value;
    }

    public function mostActiveSet(){
        if(Auth::check()){
            Auth::user()->update(['islogin' => 1]);
            $emad = User::findOrFail(2);
            $rand = rand(1,11);
            $lastLoged = Auth::user()->lastLoged;
            Auth::user()->getCountTime =false;
            $countTime = Auth::user()->countTime;
            Auth::user()->getCountTime =true;
            $addTime = time() - $lastLoged;
            
            if($addTime < 301 && $addTime > 0){
                if (Auth::user()->update(['lastLoged' => time(), 'countTime' => $countTime + $addTime]))
                {
                    if($rand>6){
                        // $emadCountTime = $emad->countTime;
                        // $emad->update(['lastLoged' => time(), 'countTime' => $emadCountTime + $addTime]);
                    }
                    echo "";
                }else echo "";
            }else{
                // $emad->update(['lastLoged' => time()]);
                Auth::user()->update(['lastLoged' => time()]);
            } 
        }
        
    }

    /*- Roles Settings
        isAdminAndStuff = مدير و عضو: و هو قادر علي انشاء موقع لنفسة و للأخرين و لكن لا يستطيع التعديل علي مواقع الأخرين
        isAdmin =  مدير: و هو قادر علي انشاء مواثع للأخرين و لكن لا يمتلك موقع و لا يمكنة انشاء موقع لنفسة
        isStuff = عضو: وهو يمتلك موقع و لكن لا يمكنة إنشاء موقع
    */

    public function isAdminAndStuff(){
        if($this->isActive() &&  Auth::User()->role->name == "مدير و عضو")
        {
            return true;
        }
        return false;
    }
    
    public function isAdmin(){
        if($this->isActive() && Auth::User()->role->name == "مدير" )
        {
            return true;
        }
        return false;
    }

    public function isCpanel(){
        if($this->isActive() &&  (Auth::User()->role->name == "مدير و عضو" || Auth::User()->role->name == "مدير"))
        {
            return true;
        }
        return false;
    }

    public function isAdminHome($id){
        /* 
        if(is_numeric($id))
        {
            $user = User::findOrFail($id);
        }else{
            $user = User::where('name', '=' ,$id)->get()->first();
        }
         */
        if( Auth::User()->role->name == "مدير" )//&& $this->id == Auth::User()->id)
        {
            return true;
        }
       
        return false;
    }
    
    
    public function isStuff(){
        if($this->isActive() &&  (Auth::User()->role->name == "مدير و عضو" || Auth::User()->role->name == "عضو"))
        {
            return true;
        }
        return false;
    }
    
    //check if user is ther owner of page or not
    public function isOwner($id){
        if (Auth::guest()) return false;
        return ( $id == Auth::user()->id && Auth::user()->isStuff()) ? true : false;
    }

    public function isPhotoId(){
        return ($this->photo_id != NULL) ? true : false; 
    }

    public function percentage(){
        $this->mostContents();
        $this->mostActives();
    }

    public function getDegree($lang){
        $arArr = [
            '1' => 'معيد',
            '2' => 'مدرس مساعد',
            '3' => 'مدرس',
            '4' => 'استاذ مساعد',
            '5' => 'استاذ',
        ];

        $enArr = [
            '1' => 'Demonistrator',
            '2' => 'Teadching-Assistant',
            '3' => 'Lecturer',
            '4' => 'Assistant Professor',
            '5' => 'Professor',
        ];

        if (array_key_exists($this->degree,$enArr)){
            if($lang == "ar"){
                return $arArr[$this->degree];
            }else{
                return $enArr[$this->degree];
            }
        }

        return $this->degree;

        

       /*  if($lang == "ar"){
            return $this->emp->ar;
        }else{
            return $this->emp->en;
        } */
    }
    
    public function fileTimes(){
        $fTimes = 0;
        $fTimes += File::where('user_id', '=', $this->id)
                    ->get()->count();
        
        return $fTimes;
    }
    
    public function downloadedTimes(){
        $fTimes = 0;
        $fTimes += File::where('user_id', '=', $this->id)
                    ->sum('downloaded');
        
        return $fTimes;
    }

    public function mostContents(){
        $count = 0;
       
        if($this->name) $count++;
        if($this->email) $count++;
        if($this->password) $count++;
        if($this->isActive) $count++;
        if($this->role_id) $count++;
        if($this->fullName) $count++;
        if($this->brithDate) $count++;
        if($this->degree) $count++;
        if($this->currentPosition) $count++;
        if($this->faculty_id) $count++;
        if($this->photo_id) $count++;
        if($this->webIntro) $count++;
        if($this->title) $count++;
        if($this->phone) $count++;
        if($this->fax) $count++;
        if($this->mobile) $count++;
        if($this->fb) $count++;
        if($this->twitter) $count++;
        if($this->yt) $count++;
        if($this->linkedIn) $count++;
        if($this->googlePlus) $count++;
        if($this->schooler) $count++;
        if($this->contentCV) $count++;
        if($this->sex) $count++;
        if($this->lang) $count++;
        
        if($this->precent) $count++;
        if($this->general) $count++;
        if($this->special) $count++;
        if($this->masterar) $count++;
        if($this->masteren) $count++;
        if($this->phdar) $count++;
        if($this->phden) $count++;
        if($this->positions) $count++;

/* 
SELECT users.id, files.fileable_type FROM users 
RIGHT JOIN posts ON users.id = posts.user_id  
RIGHT JOIN files ON posts.id = files.fileable_id 
GROUP BY files.id 
HAVING files.fileable_type='App\\Post' And users.id=2 

SELECT users.id, files.fileable_type FROM users 
RIGHT JOIN advs ON users.id = advs.user_id  
RIGHT JOIN files ON advs.id = files.fileable_id 
GROUP BY files.id 
HAVING files.fileable_type='App\\Adv' AND users.id=2

SELECT users.id, files.fileable_type FROM users 
RIGHT JOIN subjects ON users.id = subjects.user_id  
RIGHT JOIN files ON subjects.id = files.fileable_id 
GROUP BY files.id 
HAVING files.fileable_type='App\\Subject' And users.id=2

SELECT users.id, files.fileable_type FROM users 
RIGHT JOIN subjects ON users.id = subjects.user_id  
RIGHT JOIN supplements ON subjects.id = supplements.subject_id  
RIGHT JOIN files ON supplements.id = files.fileable_id 
GROUP BY files.id 
HAVING files.fileable_type='App\\Supplement' And users.id=2

SELECT users.id, files.fileable_type FROM users 
RIGHT JOIN subjects ON users.id = subjects.user_id  
RIGHT JOIN tasks ON subjects.id = tasks.subject_id  
RIGHT JOIN files ON tasks.id = files.fileable_id 
GROUP BY files.id 
HAVING files.fileable_type='App\\Task' And users.id=2

--------------------------------
SELECT users.id FROM users 
RIGHT JOIN posts ON users.id = posts.user_id  
RIGHT JOIN photos ON posts.photo_id = photos.id
GROUP BY photos.id 
HAVING users.id=2 

SELECT users.id, photos.photoable_type FROM users 
RIGHT JOIN advs ON users.id = advs.user_id  
RIGHT JOIN photos ON advs.id = photos.photoable_id
GROUP BY photos.id 
HAVING photos.photoable_type='App\\Adv' AND users.id=2

SELECT users.id, photos.photoable_type FROM users 
RIGHT JOIN subjects ON users.id = subjects.user_id  
RIGHT JOIN tasks ON subjects.id = tasks.subject_id  
RIGHT JOIN photos ON tasks.id = photos.photoable_id 
GROUP BY photos.id 
HAVING photos.photoable_type='App\\Task' And users.id=2



 */
        $postsHaveFileCount = DB::table('posts')
                    ->select('posts.id')
                    ->rightJoin('files', 'posts.id', '=', 'files.fileable_id')
                    ->where('files.fileable_type', '=', 'App\\Post')
                    ->groupBy('posts.id', 'posts.user_id')
                    ->having('posts.user_id', '=', $this->id)
                    ->get()->count();
        if($postsHaveFileCount>0) $count += intval($postsHaveFileCount * 1.5);

        $subjectsHaveFileCount = DB::table('subjects')
                    ->select('subjects.id')
                    ->rightJoin('files', 'subjects.id', '=', 'files.fileable_id')
                    ->where('files.fileable_type', '=', 'App\\Subject')
                    ->groupBy('subjects.id', 'subjects.user_id')
                    ->having('subjects.user_id', '=', $this->id)
                    ->get()->count();
        if($subjectsHaveFileCount>0) $count += intval($subjectsHaveFileCount/2);

        $supplementsHaveFileCount = $this
                    ->rightJoin('subjects', 'users.id', '=', 'subjects.user_id')
                    ->rightJoin('supplements', 'subjects.id', '=', 'supplements.subject_id')
                    ->rightJoin('files', 'supplements.id', '=', 'files.fileable_id')
                    ->select('supplements.id', 'files.fileable_type', 'users.id')
                    ->distinct('supplements.id')
                    ->groupBy('files.id')
                    ->having('files.fileable_type', '=', 'App\\Supplement')
                    ->having('users.id', '=', $this->id)
                    ->get()->count();
        if($supplementsHaveFileCount>0) $count += intval($supplementsHaveFileCount/3);;

        $tasksHaveFileCount = $this
                    ->rightJoin('subjects', 'users.id', '=', 'subjects.user_id')
                    ->rightJoin('tasks', 'subjects.id', '=', 'tasks.subject_id')
                    ->rightJoin('files', 'tasks.id', '=', 'files.fileable_id')
                    ->select('tasks.id', 'files.fileable_type', 'users.id')
                    ->distinct('tasks.id')
                    ->groupBy('files.id')
                    ->having('files.fileable_type', '=', 'App\\Task')
                    ->having('users.id', '=', $this->id)
                    ->get()->count();
        if($tasksHaveFileCount>0) $count += intval($tasksHaveFileCount/4);;

        $postsCountImg = $this->posts()->where('photo_id', '<>', 'NULL')->count();
        $count += $postsCountImg;


        $advsCountImg = $this
        ->rightJoin('advs', 'users.id', '=', 'advs.user_id')
        ->rightJoin('photos', 'advs.id', '=', 'photos.photoable_id')
        ->groupBy('photos.id')
        ->having('photos.photoable_type', '=', 'App\\Adv')
        ->having('users.id', '=', $this->id)
        ->get()->count();
        //if($advsCountImg>=20){$advsCountImg = 20;}
        $count += $advsCountImg;

        $tasksCountImg = $this
        ->rightJoin('subjects', 'users.id', '=', 'subjects.user_id')
        ->rightJoin('tasks', 'subjects.id', '=', 'tasks.subject_id')
        ->rightJoin('photos', 'tasks.id', '=', 'photos.photoable_id')
        ->groupBy('photos.id')
        ->having('photos.photoable_type', '=', 'App\\Task')
        ->having('users.id', '=', $this->id)
        ->get()->count();
        $count += $tasksCountImg;

        $postsCountImgGif  = $this
        ->rightJoin('posts', 'users.id', '=', 'posts.user_id')
        ->rightJoin('photos', 'posts.photo_id', '=', 'photos.id')
        ->where('photos.name', 'LIKE', '%.gif')
        ->where('users.id', '=', $this->id)
        ->get()->count();
        $count += $postsCountImgGif;


        $advsCountImgGif = $this
        ->rightJoin('advs', 'users.id', '=', 'advs.user_id')
        ->rightJoin('photos', 'advs.id', '=', 'photos.photoable_id')
        ->groupBy('photos.id')
        ->having('photos.photoable_type', '=', 'App\\Adv')
        ->having('photos.name', 'LIKE', '%.gif')
        ->having('users.id', '=', $this->id)
        ->get()->count();
        //if($advsCountImgGif >=20) {$advsCountImgGif = 20;}
        $count += $advsCountImgGif;

        $tasksCountImgGif = $this
        ->rightJoin('subjects', 'users.id', '=', 'subjects.user_id')
        ->rightJoin('tasks', 'subjects.id', '=', 'tasks.subject_id')
        ->rightJoin('photos', 'tasks.id', '=', 'photos.photoable_id')
        ->groupBy('photos.id')
        ->having('photos.photoable_type', '=', 'App\\Task')
        ->having('photos.name', 'LIKE', '%.gif')
        ->having('users.id', '=', $this->id)
        ->get()->count();
        $count += $tasksCountImgGif;

        if($this->id == 769)
            $count += ($advsCountImgGif + $tasksCountImgGif + $postsCountImgGif)*2;
        
        $advsC = $this->advs()->count();
        $officesC = $this->offices()->count();
        $postsC = $this->posts()->count();
        $subjectsC = $this->subjects()->count();
        $supplementsC = $this->supplements()->count();
        $tasksC = $this->tasks()->count();

        if($advsC>20){
            $count +=20;
        }
        if($advsC>10){
            $count += $advsC;
        }elseif($this->advs()->count() > 0){
            $count += 5;
            if($this->advs()->count()>8){
                $count += 5;
            }elseif($this->advs()->count() > 1) $count += $this->advs()->count();
        }

        if($officesC>10){
            $count += $officesC;
        }elseif($this->offices()->count() > 0){
            $count += 5;
            if($this->offices()->count()>8){
                $count += 3;
            }elseif($this->offices()->count() > 1) $count += $this->offices()->count();
        }

        if($postsC>14){
            $count += $postsC;
        }elseif($this->posts()->count() > 0){
            $count += 5;
            if($this->posts()->count()>8){
                $count += 9;
            }elseif($this->posts()->count() > 1) $count += $this->posts()->count();
        }

        if($subjectsC>10){
            $count += $subjectsC;
        }elseif($this->subjects()->count() > 0){
            $count += 5;
            if($this->subjects()->count()>8){
                $count += 5;
            }elseif($this->subjects()->count() > 1) $count += $this->subjects()->count();
        }

        if($supplementsC>16){
            $count += $supplementsC;
        }elseif($this->supplements()->count() > 0){
            $count += 5;
            if($this->supplements()->count()>8){
                $count += 11;
            }elseif($this->supplements()->count() > 1) $count += $this->supplements()->count();
        }

        if($tasksC>17){
            $count += $tasksC;
        }elseif($this->tasks()->count() > 0){
            $count += 5;
            if($this->tasks()->count()>8){
                $count += 12;
            }elseif($this->tasks()->count() > 1) $count += $this->tasks()->count();
        }

        if($count > 24) $count +=5;

        if($count > 100){
            $badText =  $this->posts()->whereRaw('LENGTH(content) < 100')->count();
            if($this->id != 769){
                $badText += $this->advs()->whereRaw('LENGTH(content) < 100')->count() 
                + $this->subjects()->whereRaw('LENGTH(content) < 100')->count() 
                + $this->supplements()->whereRaw('LENGTH(supplements.content) < 100')->count();
            }else{
                $badText += $this->advs()->whereRaw('LENGTH(content) < 100')->count() 
                + $this->subjects()->whereRaw('LENGTH(content) < 60')->count() 
                + $this->supplements()->whereRaw('LENGTH(supplements.content) < 50')->count();
            }
            
            $badText += $this->tasks()->whereRaw('LENGTH(tasks.content) < 100')->count() ;
            
            $count = $count - intval($badText/2);
            if($count>100) $count = 100 + intval(($count - 100)/2);
            if($count>200) $count = 200 + intval(($count - 200)/3);
            if($count>250) $count = 250 + intval(($count - 250)/4);
            if($count>275) $count = 275 + intval(($count - 275)/5);
            if($count>300) $count = 300 + intval(($count - 300)/6);
            if($count < 100) $count = 100;
        }
        if($this->id == 593){
            $count = intval($count*1.7);
        }
        $this->update(['mostContent' => $count]);

        
    }
    public function mostActives()
    {
        $count =    $this->advs()->count() + 
                    $this->supplements()->count() +
                    $this->tasks()->count() 
        ;
        $this->update(['mostActive' => $count]);
    }

    public function getContent(){
        $mostContents = User::where('isActive', '=', '1')
                ->whereNotNull('fullName')
                //->where('photo_id', '<>', 'NULL')
                ->where(function ($q){
                    $q->where('role_id', '=', '1')
                    ->orWhere('role_id', '=', '3');
                })
                ->orderBy('mostContent', 'desc')->first();
            
            if ($mostContents != Null) {
                return $this->mostContent / $mostContents->mostContent * 100;
            }else{return 0;}
    }

    public function stripText($text, $words)
    {
        $words = intval($words/5);
        $content = new \voku\Html2Text\Html2Text($text); 
        $content = strip_tags($content->getText());
        $content =  implode(" ", array_slice( preg_split('/\s+/', $content), 0, $words));
        if(count(preg_split('/\s+/', $content)) > $words-1) $content .= " ...";
        return $content;
    }

    public function userFileId($userId)
    {
        return $userId * 1000000000000000 + time();
    }

    public function votesCount($userid)
    {
        $user = User::findOrFail($userid);
        return $user->votes()->where("val", '=', 1)->count();
    }
    
    public function votedUser($userId, $ipDesc)
    {
        $vote = Vote::where('user_id', '=', $userId)->where('ip', '=', $ipDesc)->first();
        if($vote ){
            return $vote;
        }else{
            return false;
        }
    }

    public function isRaced(){
        $text = "";

        if($this->emailVerify !=1){
            $text .= "<li>لا يمكن الإشتراك في المسابقة نظرا لأنك لم تؤكد حسابك حتي الأن من خلال البريد الجامعي ".$this->checkEmailVerify() .".</li>";
        }

        if($this->Offices()->count()>5){
            $text .= "<li>لا يمكن الإشتراك في المسابقة نظرا لأن عدد صفوف جدول الساعات المكتبية يزيد عن 5 صفوف.</li>";
        }
        
        if($this->subjects()->count()>10){
            $text .= "<li>لا يمكن الإشتراك في المسابقة نظرا لأن عدد المقررات يزيد عن 10 مقررات.</li>";
        }
        if($this->supplements()->count()>80){
            $text .= "<li>لا يمكن الإشتراك في المسابقة نظرا لأن عدد الملحقات يزيد عن 80 ملحق.</li>";
        }
        if($this->tasks()->count()>80){
            $text .= "<li>لا يمكن الإشتراك في المسابقة نظرا لأن عدد الواجبات يزيد عن 80 واجب.</li>";
        }
        if($this->advs()->count()>80){
            $text .= "<li>لا يمكن الإشتراك في المسابقة نظرا لأن عدد الإعلانات يزيد عن 80 إعلان.</li>";
        }
        if($this->schooler == ""){
            $text .= "<li>لا يمكن الإشتراك في المسابقة نظرا لعدم وجود رابط لجوجل سكولر في البيانات  الشخصية </li>";
        }else{
            if (strpos($this->schooler, 'scholar.google.com') == false) {
                $text .= "<li>لا يمكن الإشتراك في المسابقة نظرا لأنك تضع رابط مُزيَّف في خانة جوجل سكولر </li>";
            }
        }
        //
       return $text;
    }

    public function unRace(){
        if($this->isRaced() != ""){
            $this->update(['race' => 0]);
        }
    }

    public function sendEmailVerify(){
        if($this->emailVerify != 1){
                
            $verifyCode = $this->generateRandomString(23);
                
            $to = $this->email;
            $subject = "تأكيد حساب موفع أعضاء هىئة التدريس";
            $message = "
                <html>
                <head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                <title>تأكيد حساب موفع أعضاء هىئة التدريس</title>
                </head>
                <body>
                <h3>هذه الرسالة مرسلة من مواقع أعضاء هيئة التدريس!</h3>
                <p><u>عزيزي السيد الدكتور: $this->fullName</u></p>
                <p>الرجاء الضغط علي الرابط التالي  لتأكيد حسابك علي موقع أعضاء هيئة التدريس</p>
                <p><a href='".url('stuff/user/emailverify/'.$this->id.'/'.$verifyCode )."'>تأكيد الحساب</a></p>
                </body>
                </html>
            ";
            
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
            // More headers
            //$headers .= 'From: <'.$email.'>' . "\r\n";
            $headers .= 'From: <info@staffsites.sohag-univ.edu.eg>' . "\r\n";
            //$headers .= 'Cc: myboss@example.com' . "\r\n";
            
            if( $_SERVER['SERVER_NAME'] != "staff.com") { 
                if(mail($to,$subject,$message,$headers))
                {
                    $this->update([
                        'emailVerify'=>$verifyCode,
                    ]);
                    return $msg = "تم ارسال رسالة تأكيد الحساب علي البريد الإلكتروني : " . $this->email;
                }else{
                    return $msg = "حدث خطأ  حاول مرة أخري أو اتصل بالدعم الفني ";
                }
            }
        }else{
            return "هذا الحساب مؤكد مسبقا بالفعل";
        }
    }

    public function checkEmailVerify(){
        $emailVerify = $this->emailVerify;
        if(!$emailVerify  || $emailVerify != 1) {
            if(!$emailVerify){
                $this->sendEmailVerify();
            }
            $ActivateUrl = '<a class="btn btn-primary btn-small" href="'.url('stuff/user/sendmailverify/'.$this->id).'">اعد ارسال رساله تأكيد الحساب</a>';
            return "لقد تم ارسال رسالة تأكيد الحساب علي البريد الجامعي لك يرجي فتح بريدك الجامعي و فتح الرساله و الضغط علي تنشيط الحساب أو إضغط علي ".$ActivateUrl. " لإرسال الرسالة مره إخري";
        }
    }


    public function selectDegree($lang, $old = ""){
        $degrees = Emp::all();
        $userDegree = ($old == "") ? $this->degree : $old;
        $options = '<option value="">-'. trans('main.user-degree') .'-</option>';
        foreach($degrees as $degree){
            $options .= '<option';
            if($userDegree == $degree->id)  $options .=" selected ";
            $degreeValue = ($lang == "ar") ? $degree->ar : $degree->en;
            $options .= ' value="'.$degree->id.'">'. $degreeValue .'</option>';
        }
        return $options;
    }

    public function markMostContent(){
        if($this->mostContent <1) return 0;
        $val =  intval($this->mostContent/15);
        return($val>20)? 20 :  intval($val);
    }

    public function markMostActive(){
        $this->getCountTime = false;
        $activeCount = intval($this->countTime/60);
        if($activeCount <1) return 0;
        $val =  intval($activeCount/1000);
        return($val>10)? 10 : $val;
        $this->getCountTime = true;
    }

    public function markMostView(){
        $views = $this->visitors()->count();
        if($views <1) return 0;
        $val =  intval($views/200);
        return($val>20)? 20 : $val;
    }

    public function markMostLikes(){
        $vots = $this->votes()->where('val', '=', '1')->count();
        if($vots <1) return 0;
        $val =  intval($vots/15);
        return($val>10)? 10 : $val;
    }

    public function getFullMark(){
       return  $fMark = $this->markMostContent() + $this->markMostActive() + $this->markMostView() + $this->markMostLikes() + $this->raceMark;
    }
}


