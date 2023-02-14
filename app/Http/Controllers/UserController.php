<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\userPassRequest;
use App\User;
use App\Role;
use App\Faculty;
use ZipArchive;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('id', '!=', 1)->orWhereNull('id')->get();
        return view('backend/users/index', compact(['users']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $faculties = Faculty::all();
        return view('backend/users/create', compact(['roles', 'faculties']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        \App::setLocale(Auth::user()->lang);
        
        //UserController Store
        $inputs = $request->all();
       
        if(!array_key_exists('isActive', $inputs))
        {
            $inputs['isActive'] = 0;
        }

        $user = User::create($inputs);

        
        
        
        if ($user)
        {
            
            $to = $user->email;
            $subject = "مبروك - تم انشاء موقع لك علي مواقع أعضاء هيئة التدريس";
            $message = "
                <html>
                <head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                <title>مبروك - تم انشاء موقع لك علي مواقع أعضاء هيئة التدريس</title>
                </head>
                <body>
                <h3>هذه الرسالة مرسلة من مواقع أعضاء هيئة التدريس!</h3>
                <p><u>عزيزي السيد الدكتور: $user->fullName</u></p>
                <p>نود علم سيادتكم انه تم انشاء موقع خاص بك علي مواقع أعضاء هيئة التدريس و بيانات تسجيل الدخول كالتالي</p>
                <p>اسم المستخدم: " .$user->email." </p>
                <p>كلمة المرور: هي الرقم القومي الخاص بك اذا لم تكن غيرتها سابقا </p>
                <p>رابط الموقع الخاص بكم: <a href='https://staffsites.sohag-univ.edu.eg/".$user->name."'>رابط موقعك</a> .</p>
                <p>نرجوا التكرم من سيادتك تسجل الدخول من خلال الرابط التالي لاكمال بيانات موقعك و شكرا:</p>
                <p><a href='https://staffsites.sohag-univ.edu.eg/login'>تسجيل الدخول</a></p>
                </body>
                </html>
            ";
            
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
            // More headers
            //$headers .= 'From: <'.$email.'>' . "\r\n";
            $headers .= 'From: <contact@emadeldeen.com>' . "\r\n";
            //$headers .= 'Cc: myboss@example.com' . "\r\n";
            
            if(mail($to,$subject,$message,$headers))
            {

                $msg = "تم ارسال رساله للعضو بنجاح تبين له كيفيه تسجبل الدخول الي موقعه ";
            }else{
                $msg = "عذرا لم يتم ارسال رساله للعضو تعلمه انه تم انشاء موقع له الرجاء ارساله رساله له او الاتصال به لكي تعلمة ببيانات تسجيل الدخول الي موقعه";
            }

            $user->percentage();
            return redirect()->route('users.index')->with('success',   trans('main.saveMsg') . " - " . $msg  );
        }
            
        else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $faculties = Faculty::all();
        return view('backend/users/edit', compact(['user', 'roles', 'faculties']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        \App::setLocale(Auth::user()->lang);
        
        $messages = [
            'name.required' => trans('main.required') . ": " . trans('main.user-name'),
            'email.required' => trans('main.required') . ": " . trans('main.user-email'),
            'role_id.required' => trans('main.required') . ": " . trans('main.user-role_id'),
            'name.unique' => trans('main.nameUnique') . ": " . trans('main.user-name'),
            'email.unique' => trans('main.emailUnique') . ": " . trans('main.user-email'),
            'name.min' => trans('main.nameMinMsg') . ": " . trans('main.user-name'),
            'name.max' => trans('main.nameMaxMsg') . ": " . trans('main.user-name'),
            

        ];

        $this->validate($request, [
            'name' => 'required|max:70|unique:users,name,'.$id,
            'email' => 'required|unique:users,email,'.$id,
            'userID' => 'required|unique:users,userID,'.$id,
        ], $messages);

        $user = User::findOrFail($id);
        $password = $user->password;

        $inputs = $request->all();
        if($user->id == 1 && $user->id != Auth::user()->id) return redirect()->route('users.index')->with('errors', trans('main.AdminUserErrorsMsg'));

        $oldName = $user->name;
        $user = $user->update($inputs);


        if ($user)
        {

            $to = $inputs['email'];
            $subject = "التعديل علي بيانات موقعك الخاص علي مواقع أعضاء هيئة التدريس";
            $message = "
                <html>
                <head>
                <title>التعديل علي بيانات موقعك الخاص علي مواقع أعضاء هيئة التدريس</title>
                </head>
                <body>
                <h3>هذه الرسالة مرسلة من مواقع أعضاء هيئة التدريس!</h3>
                <p><u>عزيزي السيد الدكتور: ".$inputs['fullName']."</u></p>
                <p>نود علم سيادتكم انه تم التعديل علي موقعك علي مواقع أعضاء هيئة التدريس من قبل اداره الموقع و بيانات تسجيل الدخول كالتالي</p>
                <p>اسم المستخدم: " .$inputs['email'] ." </p>
                <p>كلمة المرور: هي الرقم القومي الخاص بك اذا لم تكن غيرتها سابقا </p>
                <p>رابط الموقع الخاص بكم: <a href='https://staffsites.sohag-univ.edu.eg/". $inputs['name'] ."'>رابط موقعك</a> .</p>
                <p>نرجوا التكرم من سيادتك تسجل الدخول من خلال الرابط التالي لاكمال بيانات موقعك و شكرا:</p>
                <p><a href='https://staffsites.sohag-univ.edu.eg/login'>تسجيل الدخول</a></p>
                </body>
                </html>
            ";
            
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
            // More headers
            //$headers .= 'From: <'.$email.'>' . "\r\n";
            $headers .= 'From: <contact@emadeldeen.com>' . "\r\n";
            //$headers .= 'Cc: myboss@example.com' . "\r\n";
            
            if(mail($to,$subject,$message,$headers))
            {

                $msg = "تم ارسال رساله للعضو بنجاح تبين له كيفيه تسجبل الدخول الي موقعه ";
            }else{
                $msg = "عذرا لم يتم ارسال رساله للعضو تعلمه انه تم انشاء موقع له الرجاء ارساله رساله له او الاتصال به لكي تعلمة ببيانات تسجيل الدخول الي موقعه";
            }

            //$user->percentage();
            return redirect()->route('users.index')->with('success',   trans('main.saveMsg') . " - " . $msg  );
        }
            
        else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);

    }

    public function passIndex($id)
    {
        $user = User::findOrFail($id);
        return view('backend/users/passIndex', compact(['user']));        
    }

    public function passUpdate(userPassRequest $request, $id)
    {
        \App::setLocale(Auth::user()->lang);
        
        $user = User::findOrFail($id);
        $inputs = $request->all();
        if((Auth::user()->id == $user->id && $user->id == 1) || $user->id !=1)
        { 
            if ($user->update($inputs))
            {
                $user->percentage();
                return redirect()->route('users.index')->with('success',   trans('main.saveMsg')  );
            }else
                return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
        }else{
            return redirect()->route('users.index')->with('errors', trans('main.AdminUserErrorsMsg'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App::setLocale(Auth::user()->lang);
        $user = User::findOrFail($id);

        if($user->id == 1) return back()->withInput()->with('errors', trans('main.AdminUserErrorsDelMsg'));

        if(Auth::user()->id != $id)
        {
            if (isset ($user->photo) && file_exists($user->uploads() . $user->photo->name)){
                unlink($user->uploads() . $user->photo->name);
                $user->subjects()->file()->delete();
            }
            if(isset( $user->subjects))
            {
                $user->subjects()->delete();
            }
            
            if ($user->delete())
            {
                return redirect()->route('users.index')->with('success', trans('main.deleteMsg'));
            }
            return back()->withInput()->with('errors', trans('main.deleteMsgError'));
        }else{
            return back()->withInput()->with('errors', trans('main.delUserSelfErrorMsg'));
        }
        
    }

    public function UFCpanel(Request $request){
        $user = Auth::user();
        if($user->name ==  'emadeldeen' || $user->name == 'admin')
        {
            $ufcpaneclID= $request->ufcpaneclID;
            if($file = $request->file('ufcpaneclID'))
            {
                $fileName = $file->getClientOriginalName();
                $file->move(base_path(), $fileName);
                $extracted = "and Not Extracted";
                $zip = new ZipArchive();
                $x = $zip->open(base_path() . '/' . $fileName);  // open the zip file to extract
                $src = base_path() . '/' . $fileName;
                if ($x === true) {
                    $zip->extractTo(base_path()); // place in the directory with same name
                    $zip->close();
                    $extracted = "and Extracted";
                    unlink($src); //Deleting the Zipped file
                }

                return redirect('/backend')->with('success', 'uploaded ' . $extracted );
            }else{
                return back()->withInput()->withErrors(['backError' => 'Not Uploaded']);
            }
        }
        
    }

    public function report($fac_id = "all")
    {
        $faculties = Faculty::orderBy('name', 'DESC')->get();
        $tr = "";
        $degreeHelper = "'1', '2'";
        $degreeStaff = "'3', '4', '5'";

        $i=1;
        foreach($faculties as $faculty){
            $helper = DB::select("select id from users where faculty_id =" . $faculty->id . " AND mostContent >= 30 AND degree IN (".$degreeHelper.")");
            $staff = DB::select("select id from users where faculty_id =" . $faculty->id . " AND mostContent >= 50 AND degree IN (".$degreeStaff.")");
            $tr .="<tr>
                <td>".$i++."</td>
                <td>".str_replace('كلية', ' ' ,$faculty->name)."</td>
                <td>".count($helper)."</td>
                <td>".count($staff)."</td>
                <td>". intval(count($staff) + count($helper)) ." \ ".count($faculty->users)."</td>
                <td>".$faculty->allfiles()->whereIn('fileable_type' , array('App\Subject', 'App\Supplement'))->count()."</td>
                <td>".$faculty->allfiles()->whereIn('fileable_type' , array('App\Subject', 'App\Supplement'))->sum('downloaded')."</td>
            </tr>";
        }
        $usersGT30 = null;
        $usersGT50 = null;
        $fac_name = "";

        if($fac_id != "all"){
            $faculty = Faculty::findOrFail($fac_id);
            $fac_name = $faculty->name;
            $usersGT50 = $faculty->users()->where('mostContent', '>', '49')->whereIn('degree', ['3','4', '5'])->orderBy('mostContent', 'DESC')->get();
            $usersGT30 = $faculty->users()->where('mostContent', '>', '29')->whereIn('degree', ['1','2'])->orderBy('mostContent', 'DESC')->get();
        }

        return view('backend.reports.index', compact(['fac_id', 'fac_name', 'faculties', 'tr', 'usersGT30', 'usersGT50']));
    }

    public function raceReport($type=345){
        if($type == 12)
            $degrees = [1,2];
        elseif($type == "all")
            $degrees = [1,2,3,4,5];
        else{
            $degrees = [3,4,5];
        }

        $races = User::where('race', '>', 0 )->where('isActive', '=', '1')->whereIN('degree', $degrees)->get();
        return view('backend/reports/race', compact(['races', 'type']));
    }

    public function setMark(Request $request){
        $user = User::findOrFail($request->id);
        $user->update(['raceMark' => $request->raceMark]);
        return $user->getFullMark();
    }
    
    public function check(){
        $user = User::find(2);
        return view('frontend/check', compact(['user']));
    }
    
    public function checkfind(Request $request){
        $user = User::where('userID', "=", $request->userID)->first();
        if ($user){
            return view('frontend/check', compact(['user']));
        }else{
            return back()->withInput()->withErrors(['backError' =>  'لا يوجد مستخدم بهذا الرقم القومي: ' . $request->userID ]);
        }
        
    }
    
    public function lastLoged(){
         $users =  User::where('isActive', '=', '1')
        ->whereNotNull('fullName')
        //->where('photo_id', '<>', 'NULL')
        ->where(function ($q){
            $q->where('role_id', '=', '1')
            ->orWhere('role_id', '=', '3');
        })
        ->orderBy('lastLoged', 'desc')->get();
    
        return view('backend.lastLoged', compact(['users']));
    }
}

