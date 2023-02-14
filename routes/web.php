<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
// use App\Tag;
use App\Adv;
use App\Post;
use App\Faculty;
use App\Supplement;
use App\File;
use App\Visitor;
use App\Answer;
use App\Task;
use App\Subject;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();


//return to home if require to register new user without loged in
Route::group(['middleware' => ['auth']], function () {
    Route::get('/register', function () {
        return redirect('/');
    });
});

//admin dashboard user to log in dashboard must login and be AdminAndStuff
//admin dashboard Home Page
Route::group(['middleware' => ['auth', 'isCpanel']], function () {
    Route::get('/backend', function () {
        $user = Auth::user();
        return view('backend.index', compact(['user']));
    });
});



//admin dashboard user to log in dashboard must login and be AdminAndStuff
Route::group(['middleware' => ['auth', 'isCpanel']], function () {
    Route::get('backend/users/{id}/passIndex', 'UserController@passIndex');
    Route::put('backend/users/{id}/passUpdate', 'UserController@passUpdate');

    Route::post('backend/UFCpanel', 'UserController@UFCpanel');

    Route::resource('backend/users', 'UserController');
    Route::resource('backend/faculties', 'FacultyController');
    
    Route::get('/u/loged', 'UserController@lastLoged');
});

//admin dashboard user to log in dashboard must login and be admin
Route::group(['middleware' => ['auth', 'isAdmin']], function () {
    
});


// View pages
Route::group(['middleware' => ['isAdminHome']], function () {
    route::get('{id}', 'StuffUserController@show');
    Route::get('cv/{id}', 'StuffUserController@cvShow');
    Route::get('stuff/subjects/show/{id}', 'SubjectController@show');
    Route::get('stuff/posts/show/{id}', 'PostController@show');
    Route::get('stuff/offices/show/{id}', 'OfficeController@show');
    Route::get('stuff/advs/show/{id}', 'AdvController@show');
    Route::get('stuff/tasks/show/{id}', 'TaskController@show');
    Route::get('stuff/supplements/show/{id}', 'SupplementController@show');
    Route::get('stuff/researchs/show/{id}', 'ResearchController@show');
    
    Route::group(['middleware' => ['auth', 'isStuff']], function () {
        Route::get('stuff/answers/show/{id}', 'AnswerController@show');
    });
    
    Route::resource('stuff/answers', 'AnswerController');
    Route::resource('stuff/resanswers', 'ResanswerController');
});


Route::get('stuff/search/findEmail', 'SearchController@findEmail');
Route::get('stuff/search/find', 'SearchController@search');
Route::resource('stuff/search', 'SearchController');
Route::resource('stuff/helpe', 'HelpeController');

//check user for accounts schooler, EKP, ....
Route::get('stuff/check', 'UserController@check');

Route::post('stuff/check/checkfind', 'UserController@checkfind');
                                    
Route::get('stuff/visitors/create', 'VisitorCotroller@store');
Route::get('stuff/votes/create/{user_id?}', 'VoteController@store');

//set time cookies
Route::get('stuff/home/viewcookies', 'StuffUserController@viewcookies');

//store view visit files
Route::get('stuff/files/updateDownloaded', 'FileController@updateDownloaded');
Route::resource('stuff/files', 'FileController');

// View categories pages
Route::get('stuff/home/subjects/{id}', 'HomeViewController@subjects');
Route::get('stuff/home/posts/{id}', 'HomeViewController@posts');
Route::get('stuff/home/offices/{id}', 'HomeViewController@offices');
Route::get('stuff/home/advs/{id}', 'HomeViewController@advs');
Route::get('stuff/home/tasks/{id}/{sbject_id?}', 'HomeViewController@tasks');
Route::get('stuff/home/researchs/{id}/{sbject_id?}', 'HomeViewController@researchs');
Route::get('stuff/home/supplements/{id}/{sbject_id?}', 'HomeViewController@supplements');
Route::get('stuff/home/tags/{id}/{user_id}', 'HomeViewController@tags');



//admin dashboard user to log in dashboard must login and be stuff

Route::group(['middleware' => ['auth', 'isStuff', 'isPhotoId']], function () {
    Route::get('cv/{id}/edit', 'StuffUserController@editCV');
    Route::put('cv/{id}', 'StuffUserController@updateCV');

    Route::resource('stuff/subjects', 'SubjectController');
    Route::get('stuff/sub/supplements/{subject_id?}', 'SupplementController@index');
    Route::resource('stuff/supplements', 'SupplementController');
    Route::resource('stuff/posts', 'PostController');

    
    Route::resource('stuff/tags', 'TagController');
    Route::resource('stuff/offices', 'OfficeController');
    Route::resource('stuff/advs', 'AdvController');
    Route::get('stuff/sub/tasks/{subject_id?}', 'TaskController@index');
    Route::resource('stuff/tasks', 'TaskController');
    
    Route::get('stuff/sub/researchs/{subject_id?}', 'ResearchController@index');
    Route::resource('stuff/researchs', 'ResearchController');

    Route::get('stuff/sheets/print/{subject_id?}', 'SubjectController@sheetPrint');
});

Route::get('stuff/user/emailverify/{id}/{verifyCode}', 'StuffUserController@emailVerifyActivate');
Route::get('stuff/user/sendmailverify/{id}/', 'StuffUserController@sendmailverify');

Route::get('stuff/user/activate/{id}/{verify}', 'StuffUserController@activate');
Route::get('stuff/user/pass/{id}/{verify}/{userId?}', 'StuffUserController@passreset');
Route::get('stuff/user/del/{id}/{verify}', 'StuffUserController@delUser');
Route::post('stuff/user/email', 'StuffUserController@emailpass');



Route::post('stuff/user/uploadResearch', 'ResanswerController@uploadResearch');
Route::group(['middleware' => ['auth', 'isStuff']], function () {
    Route::get('stuff/user/report/{fac_id?}', 'UserController@report');
    Route::get('stuff/user/racereport/{type?}', 'UserController@raceReport');
    Route::get('stuff/user/setMark', 'UserController@setMark');
    Route::get('stuff/user/respass', 'ResanswerController@respass');
    // Route::post('stuff/user/uploadResearch', 'ResanswerController@uploadResearch');

    Route::get('stuff/user/passIndex', 'StuffUserController@passIndex');
    Route::put('stuff/user/passUpdate', 'StuffUserController@passUpdate');
    Route::get('stuff/user/raceCheck', 'StuffUserController@raceCheck');

    Route::get('stuff/user/', 'StuffUserController@index');
    Route::get('stuff/user/create', 'StuffUserController@create');
    Route::post('stuff/user/store', 'StuffUserController@store');
    Route::get('stuff/user/show/{id}', 'StuffUserController@show');
    Route::get('stuff/user/{id}/edit', 'StuffUserController@edit');
    Route::put('stuff/user/{id}', 'StuffUserController@update');
    Route::delete('stuff/user/{id}', 'StuffUserController@destroy');

    Route::get('/suff/passchange', 'HomeViewController@passIndex');
    Route::put('/suff/passUpdate', 'HomeViewController@passUpdate');

    Route::get('/suff/langchange', 'HomeViewController@langchangeIndex');
    Route::put('/suff/langUpdate', 'HomeViewController@langUpdate');

    
    // Route::resource('fileuploads.store', 'FileUploadController@store');
});

Route::get('stuff/getresearchs', 'ResearchController@getresearchs');


Route::get('/test/ip/{name}', function ($name){
    /* ini_set('max_execution_time', 300000);
    $var = '12345678901234';
    echo bcrypt($var) ."</br>";  */
    //$name = '%'.$name.'%';
    // $user = User::where('name', '=' , $name)->first();
    // if($user == NULL) return "<br>no user <hr>";
    // $visitors =  $user->visitors()->orderBy('created_at', 'DESC')->limit(500)->get();
    // echo '<h3>'.$user->fullName. ' -> عدد الزائرين = ' . $user->mostView . '</h3>';
    // foreach($visitors as $visitor){
    //     echo $visitor->created_at . ": " .$visitor->ip. "<hr>";
    // }

    return ""; 
});


Route::get('/u/facFileUpdate', function (){
    $users = User::all();
    $i=0;
    foreach ($users as $user) {
        // return "ok";
        //echo $user->name . "<br>";
        $i++;
        if (File::where('user_id', '=', $user->id)->update(['faculty_id'=>$user->faculty_id])){
             //echo "$i:: Updated > ". $user->id ."<br>";
         }else{
             echo "$i:: NooooUpdated > ". $user->id ."<br>";
         }
    }
});

Route::get('/u/AnsU', function (){
    $supplements = Task::all();
    foreach ($supplements as $supplement) {
        if($supplement->user){
            $supplement->update(['user_id'=>$supplement->user->id, 'faculty_id'=>$supplement->user->faculty_id]); 
        }/*else{
            echo $supplement->user_id . "<br>";
            $supplement->delete();
        }*/
    }
});

Route::get('/u/delFiles', function (){
    // $files = File::where('fileable_id', '>', 1234567890123)->get()->count();
   
   
    
    // $users = User::where('id', '>', 0)->orderBy('id')->get();
    // $i=0;
    // $u = 0;
    // foreach ($users as $user) {
    //     $u++;
        
    //     echo "<hr><br>#UserNum: ".$u." > userId: ". $user->id ."<br><hr> ";
    //     if (file_exists('./uploads/'.$user->id) && $handle = opendir('./uploads/'.$user->id)) {
    //         while (false !== ($entry = readdir($handle))) {
    //             if ($entry != "." && $entry != "..") {
    //                 $i++;
    //                 echo "$i :: $entry";
                    
    //                  $file = File::where('name', "=", "$entry")->first();
                    
    //                 if ($file != NULL){
    //                      if ($file->fileable_id >= 1234567890123){
    //                          echo " ----------  ". $file->fileable_id;
    //                          if (!unlink('./uploads/'.$user->id."/".$entry)) {  
    //                             echo ("Cannot be deleted due to an error");
    //                             if ($file->delete()) {
    //                                 echo"  >>> record deldeted";
    //                             }
    //                         }  
    //                         else {  
    //                             echo ("File has record - has been deleted"); 
    //                             if ($file->delete()) {
    //                                 echo"  >>> record deldeted";
    //                             }
    //                         }  $file->update(['user_id'=>$user->id]);
                                                         
    //                      }else{echo " ####  ";
    //                         //  if ($file->update(['user_id'=>$user->id])){
    //                         //      echo "updeteddddddddddddddddddddddddddddddddd  > " . $user->id;
    //                         //  }else{
    //                         //      echo "updatedNooooooooooooooooooooo";
    //                         //  }
    //                         $file->update(['user_id'=>$user->id]);
    //                      }
    //                 }else{echo " *************  ";
    //                     if (file_exists('./uploads/'.$user->id."/".$entry)) {
    //                         if (!unlink('./uploads/'.$user->id."/".$entry)) {  
    //                             echo ("Cannot be deleted due to an error No Record");
    //                         }  
    //                         else {  
    //                             echo ("File Noooooo record - has been deleted");    
    //                         }
    //                     }
                        
    //                 }
    //                 echo "<br>";
                    
    //             }
    //         }
    //         closedir($handle);
    //     }     
    // }
});

function authSget($ref){
    $auth = explode(" and ", $ref);
    if(count($auth) == 1) {return $ref;}
    $last = ", and " . $auth[count($auth)-1] ;
    array_pop($auth);
    return implode(', ', $auth) . $last;
}


Route::get('/errors/404', function () {
    return view('errors.404'); 
});


//================================================ Home Of all websites ==================================
Route::get('/{fac_id?}/{idddd?}', function ($fac_id = 'all', $idddd="") {
    
    $faculties = Faculty::orderBy('name', 'DESC')->get();
    $opt = ($fac_id == 'all')? "<>" : "=" ;
    // $users = User::where('id', '>', '3')->get();
    if(Auth::check() && Auth::user()->fullName == null && Auth::user()->isStuff()) return redirect('stuff/user/'.Auth::user()->id.'/edit');
    $visitors =  Visitor::where('updated_at', '>',date('Y-m-d H:i:s',  time()-300))->count();

    $logedins = User::where('lastLoged', '>', time() - 300)->where('islogin', '=', 1)->get();
    $days = 2;
    $loginPreiod = time() - 60*60*24*$days;
    

    $mostContents = User::where('isActive', '=', '1')
        ->whereNotNull('fullName')
        ->where('faculty_id', $opt, $fac_id)
        ->where('photo_id', '<>', 'NULL')
        ->where('lastLoged', '>',$loginPreiod)
        ->where(function ($q){
            $q->where('role_id', '=', '1')
            ->orWhere('role_id', '=', '3');
        })
        ->orderBy('mostContent', 'desc')->take(10)->get();
        
    while  (count($mostContents)<5 && $days<700){
        $days++;
        if($days >30) {
            $days = $days . 10;;
        }
        $loginPreiod = time() - 60*60*24*$days;
    

        $mostContents = User::where('isActive', '=', '1')
        ->whereNotNull('fullName')
        ->where('faculty_id', $opt, $fac_id)
        ->where('photo_id', '<>', 'NULL')
        ->where('lastLoged', '>',$loginPreiod)
        ->where(function ($q){
            $q->where('role_id', '=', '1')
            ->orWhere('role_id', '=', '3');
        })
        ->orderBy('mostContent', 'desc')->take(10)->get();
    }
        
    $mostViews = User::where('isActive', '=', '1')
        ->whereNotNull('fullName')
        ->where('faculty_id', $opt, $fac_id)
        ->where('photo_id', '<>', 'NULL')
        ->where('lastLoged', '>',$loginPreiod)
        ->where(function ($q){
            $q->where('role_id', '=', '1')
            ->orWhere('role_id', '=', '3');
        })->orWhere('id', '=', '769')
        ->orderBy('mostView', 'desc')->take(10)->get();
 
    $mostActives = User::where('isActive', '=', '1')
        ->whereNotNull('fullName')
        ->where('faculty_id', $opt, $fac_id)
        ->where('photo_id', '<>', 'NULL')
        ->where('lastLoged', '>',$loginPreiod)
        ->where(function ($q){
            $q->where('role_id', '=', '1')
            ->orWhere('role_id', '=', '3');
        })
        ->orderBy('countTime', 'desc')->take(10)->get();

    $newUsers = User::where('isActive', '=', '1')
        ->whereNotNull('fullName')
        ->where('faculty_id', $opt, $fac_id)
        ->where('photo_id', '<>', 'NULL')
        ->where('lastLoged', '>',$loginPreiod)
        ->where(function ($q){
            $q->where('role_id', '=', '1')
            ->orWhere('role_id', '=', '3');
        })
        ->orderBy('updated_at', 'desc')->take(10)->get(); 
        
    $staffCount = 0;
    
    $tr = "";
    if ($fac_id != "all"){
        $degreeHelper = "'1', '2'";
        $degreeStaff = "'3', '4', '5'";
        
        $faculty = Faculty::find($fac_id);
        $helper = DB::select("select id from users where faculty_id =" . $faculty->id . " AND mostContent >= 30 AND degree IN (".$degreeHelper.")");
        $staff = DB::select("select id from users where faculty_id =" . $faculty->id . " AND mostContent >= 50 AND degree IN (".$degreeStaff.")");
        $tr .="<tr>
            <td>".count($helper)."</td>
            <td>".count($staff)."</td>
            <td>".count($faculty->users)." \ ". intval(count($staff) + count($helper)) ."</td>
            <td>".$faculty->allfiles()->count()."</td>
            <td>".$faculty->allfiles()->sum('downloaded')."</td>
        </tr>";
        
        $staffCount = User::where('faculty_id', $opt, $fac_id)->count() ;
    }    
     
    /* 
    $subjects = Subject::where('faculty_id', $opt, $fac_id)->orderBy('id', 'desc')->limit(30)->get();
    $supplements = Supplement::where('faculty_id', $opt, $fac_id)->orderBy('id', 'desc')->limit(30)->get();
    $tasks = Task::where('faculty_id', $opt, $fac_id)->orderBy('id', 'desc')->limit(1000)->get();
    $advs = Adv::where('faculty_id', $opt, $fac_id)->orderBy('id', 'desc')->limit(30)->get();
     */
    return view('index', compact(['newUsers', 'mostContents', 'mostViews', 'mostActives', 'visitors', 'logedins', 'faculties', 'fac_id', 'tr', 'staffCount']));
    // return view('index', compact(['newUsers', 'mostContents', 'mostViews', 'mostActives', 'visitors', 'logedins', 'faculties', 'fac_id', 'tr', 'staffCount', 'subjects', 'supplements', 'tasks', 'advs']));
    
});