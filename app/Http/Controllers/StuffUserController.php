<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\userStuffRequest;
use App\Http\Requests\userPassRequest;
use Illuminate\Support\Facades\Auth;
use App\Faculty;
use App\Photo;
use App\File;

class StuffUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $isOwner = $user->isOwner($user->id);
        return view('stuff/user/index', compact(['user', 'isOwner']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  
        if(is_numeric($id))
        {
            $user = User::findOrFail($id);
        }else{
            $user = User::where('name', '=' ,$id)->get()->first();
        }
        
        if($user == null) return "show null user";
        if($user == null) return redirect('/errors/404');

        $isOwner = $user->isOwner($user->id);
        
        return view('stuff/user/show', compact(['user', 'isOwner'])); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $isOwner = $user->isOwner($id);
        $faculties = Faculty::all();
        return view('stuff/user/edit', compact(['user', 'isOwner', 'faculties']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(userStuffRequest $request, $id)
    {
        $user = Auth::user();
        \App::setLocale($user->lang);

        $inputs = $request->all();
        if($file = $request->file('photo_id'))
        {
            if (isset ($user->photo) && file_exists($user->uploads() . $user->photo->name)){
                unlink($user->uploads() . $user->photo->name);
            }
            
            $photoName = time() . $file->getClientOriginalName();
            $file->move($user->uploads(), $photoName);

            $photo = Photo::create(['name' => $photoName]);
            $inputs['photo_id'] = $photo->id;

        }else{
            $inputs['photo_id'] = $user->photo_id;
        }

        if ($user->update($inputs))
        {
            $t = $user->percentage();
            return redirect('stuff/user')->with('success',   trans('main.saveMsg')  );
        }else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);

    }

    public function passIndex()
    {
        $user = Auth::user();
        $isOwner = $user->isOwner($user->id);
        return view('stuff/user/passIndex', compact(['user', 'isOwner']));        
    }

    public function passUpdate(userPassRequest $request)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();

        if ($user->update($inputs))
            return redirect('stuff/user')->with('success',   trans('main.saveMsg')  );
        else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function editCV()
    {
        $user = Auth::user();
        $isOwner = $user->isOwner($user->id);
        return view('stuff/user/cv', compact(['user','isOwner']));
    }

    public function updateCV(Request $request)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();
    
        $delIds = $request->delId;
        
        $userUpdated = $user->update($inputs);
        if ($userUpdated){ 
            $user->percentage();
            if(count ($delIds) > 0)
            {
                foreach($delIds as $delId)
                { 
                    $file = File::find($delId);
                    if($file != null) {
                        if (file_exists($user->uploads() . $file->name))
                        {
                            unlink($user->uploads() . $file->name);
                        }
                        $file->delete();
                    }

                } 
            }
              
            return redirect('cv/'.$user->id.'/edit')->with('success',   trans('main.saveMsg')  );
        }else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
  
    }

    public function cvShow($id){
        $user = User::findOrFail($id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/user/cvshow', compact(['user', 'isOwner']));
    }
    
    public function activate($id, $verify){
        $user = User::find($id);
        if($user == null) return "<h1>لا يوجد حساب لهذا المستخدم قد يكون تم حذفه من قبل الاداره</h1 <br> <a href='". url('/') ."'>اذهب الي مواقع أعضاء هيئة التدريس</a>";

        if($verify === $user->verify){
            $user->update([
                'verify'=>$user->generateRandomString(23),
                'isActive'=>1,
            ]);


            $to = $user->email;
            $subject = "مبروك - تم تنشيط موقع لك علي مواقع أعضاء هيئة التدريس";
            $message = "
                <html>
                <head>
                <title>مبروك - تم تنشيط موقع لك علي مواقع أعضاء هيئة التدريس</title>
                </head>
                <body>
                <h3>هذه الرسالة مرسلة من مواقع أعضاء هيئة التدريس!</h3>
                <p><u>عزيزي السيد الدكتور: $user->fullName</u></p>
                <p>نود علم سيادتكم انه تم تنشيط موقع خاص بك علي مواقع أعضاء هيئة التدريس و بيانات تسجيل الدخول كالتالي</p>
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
            $headers .= 'From: <stafsites.sohag>' . "\r\n";
            //$headers .= 'Cc: myboss@example.com' . "\r\n";
            
            if(mail($to,$subject,$message,$headers))
            {

                $msg = "تم ارسال رساله للعضو بنجاح تبين له كيفيه تسجبل الدخول الي موقعه ";
            }else{
                $msg = "عذرا لم يتم ارسال رساله للعضو تعلمه انه تم تنشيط موقع له الرجاء ارساله رساله له او الاتصال به لكي تعلمة ببيانات تسجيل الدخول الي موقعه";
            }

            return redirect('/'.$user->name)->with('success', 'تم تنشيط الحساب بنجاح - ' . $msg );
        }else{
            return "<h1>إنتهت صلاحية الرابط!!، ادخل الي لوحة التحكم من حسابك و افعل ما تشاء</h1> <br> <a href='". url('/') ."'>اذهب الي مواقع أعضاء هيئة التدريس</a>";
        }
    }
    
    public function passreset($id, $verify, $userId = ""){
        $user = User::find($id);
        if($user == null) return "<h1>لا يوجد حساب لهذا المستخدم قد يكون تم حذفه من قبل الاداره</h1 <br> <a href='". url('/') ."'>اذهب الي مواقع أعضاء هيئة التدريس</a>";
        $userId = ($userId == "") ? $user->userID : $userId;
        if($verify === $user->verify){
            $user->update([
                'verify'=>$user->generateRandomString(23),
                'password'=>$userId,
                'userID'=>$userId,
            ]);

            $to = $user->email;
            $subject = "مبروك -تم إعاده ضبط كلمة المرور الي الرقم القومي لحسابك علي مواقع أعضاء هيئة التدريس";
            $message = "
                <html>
                <head>
                <title>مبروك -تم إعاده ضبط كلمة المرور الي الرقم القومي لحسابك علي مواقع أعضاء هيئة التدريس</title>
                </head>
                <body>
                <h3>هذه الرسالة مرسلة من مواقع أعضاء هيئة التدريس!</h3>
                <p><u>عزيزي السيد الدكتور: $user->fullName</u></p>
                <p>تحيط علم سيادتكم انه تم إعاده ضبط كلمة المرور الي الرقم القومي بناء علي طلبك او  من قبل إداره الموقع و شكرا</p>
                <p>اسم المستخدم: " .$user->email." </p>
                <p>كلمة المرور: هي الرقم القومي الخاص بك </p>
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

                $msg = "تم ارسال رساله للعضو بنجاح تبين له انه تم اعاده ضبط كلمه المرور له ";
            }else{
                $msg = "عذرا لم يتم ارسال رساله للعضو تعلمه انه تم إعاده ضبط كلمة المرور  له الرجاء ارساله رساله له او الاتصال به لكي تعلمة ببيانات تسجيل الدخول الي موقعه";
            }


            return redirect('/'.$user->name)->with('success', 'تم إعاده ضبط كلمة المرور الي الرقم القومي بنجاح - ' . $msg);
        }else{
            return "<h1>إنتهت صلاحية الرابط!!، ادخل الي لوحة التحكم من حسابك و افعل ما تشاء</h1> <br> <a href='". url('/') ."'>اذهب الي مواقع أعضاء هيئة التدريس</a>";
        }
    }
    
    public function delUser($id, $verify){
        $user = User::find($id);
        if($user == null) return "<h1>لا يوجد حساب لهذا المستخدم قد يكون تم حذفه من قبل الاداره</h1 <br> <a href='". url('/') ."'>اذهب الي مواقع أعضاء هيئة التدريس</a>";
        $name = $user->name;
        if($verify === $user->verify){
            $user->delete();
            return "<h1>تم حذف العضو: ". $name ." بنجاح</h1> <br> <a href='". url('/') ."'>اذهب الي مواقع أعضاء هيئة التدريس</a>";

        }else{
            return "<h1>إنتهت صلاحية الرابط!!، ادخل الي لوحة التحكم من حسابك و افعل ما تشاء</h1> <br> <a href='". url('/') ."'>اذهب الي مواقع أعضاء هيئة التدريس</a>";
        }
    }
    
    public function emailpass(Request $request){

        $messages = [
            //'g-recaptcha-response.required' => 'من فضلك إضغط علي انا لست روبوت! قبل الارسال',
        ];

        $this->validate($request, [
            //'g-recaptcha-response' =>  'required|recaptcha',
        ], $messages);

        $user = User::where('email', $request->email)->first();
        if($user == null){
            return back()->withInput()->withErrors(['backError' => 'عذرالا يوجد حساب او موقع مرتبط بهذا البريد الالكتروني حاول مرة أخري']);
        }else{

            $to =  $user->email;
            $verify = $user->generateRandomString(25);
            $user->update(['verify' => $verify]);

            $subject = "طلب إعاده ضبط كلمة المرور - " . $user->fullName;
            $message = "
                <html>
                <head>
                <title>هذه الرسالة مرسلة من مواقع أعضاء هيئة التدريس!</title>
                </head>
                <body dir='rtl'>
                    <div style='direction:rtl;'>
                        <h3>$subject</h3>
                        <p><u>عزيزي السيد الدكتور: ".$user->fullName."</u></p>
                        <p>لقد وصلنا طلب منك لإعاده ضبط كلمة المرور الخاصة بحسابكم علي مواقع أعضاء هيئة التدرس الي الرقم القومي، إذا لم تكم انت من أرسل الطلب تجاهل الرسالة و لا تفعل شئ</p>
                        <p><a href='".url('stuff/user/pass/' . $user->id .'/'.$user->verify)."'>إضغط هنا لإعاده ضبط كلمة المرور الي الرقم القومي</a></p>
                    </div>
                </body>
                </html>
            ";
            
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
            // More headers
            //$headers .= 'From: <'.$email.'>' . "\r\n";
            $headers .= 'From: <reset@staffsites.sohag-univ.edu.eg>' . "\r\n";
            //$headers .= 'Cc: myboss@example.com' . "\r\n";
            
            //return $message;

            if(mail($to,$subject,$message,$headers))
            {
                return redirect('/password/reset')->with('success', 'تم ارسال رابط الي بريدك الجامعي لاعاده ضبط كلمة المرور الي الرقم القومي  يرجي فتح البريد الجامعي و الضغط علي الرابط حتي بتم إعاده ضبط كلةالمرور<span style="color:blue; font-size:18px;"> <br> - ان لم تجد الرسالة ابحث عنها في مجلد  بريد إلكتروني غير هام أو sapm or junk mail!   </span> <img class="junk" src="https://www.emadeldeen.com/123/01junk.png"><img class="junk"src="https://www.emadeldeen.com/123/02junk.png">');
            }else{
                return back()->withInput()->withErrors(['backError' => 'عذرالا يوجد حساب او موقع مرتبط بهذا البريد الالكتروني حاول مرة أخري']);
            }
        }
    }

    public function viewcookies(Request $request)
    {
        if($request->ajax())
        {
            if($request->time > 3600 || $request->isFirst == 1)
            {
                $user = User::findOrFail($request->id);
                $userMostViews = $user->mostView;
                $user->update(['mostView' => ++$userMostViews]);
                return Response($userMostViews);
            }else return Response("no");
        }
    }

    public function raceCheck(Request $request)
    {
        if($request->ajax())
        {
            $authUser = Auth::user();
            
            if(Auth::check()){
                if($authUser->id == $request->user_id){
                    if($authUser->isRaced() != "") {
                        $updatedUser = $authUser->update(['race'=> 0]);
                        return $authUser->isRaced();
                    }
                    $updatedUser = $authUser->update(['race'=> $request->isChack]);
                    if($updatedUser)
                    {
                        //return Auth::user()->race;
                        if($request->isChack)
                            return "تم الاشتراك في المسابقة بنجاح";
                        else return "تم الغاء الاشتراك في المسابقة بنجاح";
                        
                    }else{
                        return 'عذرا لم يتم الاشتراك اتصل بالدعم الفني';
                    }
                }else{
                    return "عذرا لم يتم الاشتراك ، احتيال.";
                }
            }else{
                return "عذرا لم يتم الاشتراك ، لم تسجل دخول.";
            }
        }        
    }

    public function emailVerifyActivate($id, $verifyCode){
        $user = User::findOrFail($id);
        if($user->emailVerify == $verifyCode){
            $user->update(['emailVerify' => 1]);
            $url = url('/'.$user->id);
            $msg = "تم تنشيط الحساب بنجاح جاري التحويل الي موقك خلال خمس ثواني";
            return view('showmsg', compact(['url', 'msg']));
        }else{
            $url = url('/'.$user->id);
            $msg = "لقد انتهت صلاحية الرابط جاري التحويل الي الموقع خلال خمس ثواني";
            return view('showmsg', compact(['url', 'msg']));
        }
    }

    public function sendmailverify($id){
        $user = User::findOrFail($id);
        $url = url('/'.$user->id);
        $msg =  $user->sendEmailVerify() . " - جاري التحويل الي الموقع خلال خمس ثواني";
        return view('showmsg', compact(['url', 'msg']));
    }
}
