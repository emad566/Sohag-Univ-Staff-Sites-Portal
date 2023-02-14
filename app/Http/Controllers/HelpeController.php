<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\HelpeSendRequest;
use App\User;
use App\Faculty;

class HelpeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculties = Faculty::all();
        return view('stuff/helpe/index', compact(['faculties']));
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
    public function store(HelpeSendRequest $request)
    {
        
        $inputs = $request->all();
        session_start();        

        if ($request->answer == "") {
            return back()->withInput()->withErrors(['backError' => '
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     من فضلك أجب علي السؤال الاجباري قبل الضغط علي ارسال.
                </div>
                '
            ]);
        }elseif($request->answer != $_SESSION['answer']){
            return back()->withInput()->withErrors(['answer' => '
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     إجابة السؤال الإجبار خطأ حاول مرة إخري   
                </div>
                '
            ]);
        }

        $contactName = $inputs['sName'];
        $phone = $inputs['user-phone'];
        $email = $inputs['user-email'];
        $web = $inputs['sWeb'];
        $userId = $inputs['userId'];
        $comments = $inputs['sMessage'];
        $faculty_id = $inputs['faculty_id'];
        $browser = $inputs['getBrowser'];
        $getIP = $request->getIP;

        $name =  explode("@", $email);
        $name =  $name[0].rand(1, 2000);

        $fac = Faculty::find($faculty_id);
        $facName = $fac->name;

        $creatUser = [
            'name' => $name,
            'email' => $email,
            'password' => $userId,
            'isActive' => 0,
            'role_id' => 3,
            'fullName' => $contactName,
            'faculty_id' => $faculty_id,
            'mobile' => $phone,
            'sex' => '2',
            'lang' => 'ar',
            'mostContent' => 9,
            'mostView' => 0,
            'userID' => $userId
        ];


        $user = User::where('email' , '=', $email)->first();
        if($user === null) $user = User::where('name' , '=', $name)->first();
        $url = "";
        $ActiveUrl = "";
        $isEmail = "";
        $deletUser = "";
        $isNewUser = false;
        if($user != null){
            
            $verify = $user->generateRandomString(25);
            $user->update(['verify' => $verify]);
            $isEmail = "نعم <br>";
            
            if(Hash::check($userId, $user->password)){
                $isEmail .="و كلمة المرو هي الرقم القومي";
            }else{
                $isEmail .="و قد تم تغيير كلمة المرور ";
                $url = "<a target='_blank' style='font-size:26px;' href='".url('stuff/user/pass/' . $user->id .'/'.$user->verify.'/'.$userId)."'>إضغط هنا اذا كنت تريد إعاده ضبط كلمه المرور الي الرقم القومي لهذا العضو</a>";
            }
        }else{
            $user = User::where('userID' , '=', $userId)->first();
            if($user === null){
                $isEmail = "لا <br>";
                $user = User::create($creatUser);
                $verify = $user->generateRandomString(25);
                if($user != null)
                {
                    $isNewUser = true;
                    $user->update(['verify' => $verify]);
                    $isEmail .="و قد تم انشاء حساب بالبيانات  السابقة و لكن لم يتم تفعيل الحساب حتي الان ";
                }
            }else{
                $isEmail = "نعم <br>";
                $verify = $user->generateRandomString(25);
                $isEmail .="يوجد حساب مسجل مسبقا بهذا الرقم القومي و لكن يمتلك بريد مختلف عن المرفق مع الرسالة و هو <br> <a href='".url('/'.$user->name)."'>".$user->email."</a>";
            }
            
            
        }

        $isActive = "";
        

        if($user->isActive == 1){
            $isActive = "نشط";
        }else{
            $isActive = "غير نشط";
            $deletUser = "<a target='_blank' style='font-size:26px;' href='".url('stuff/user/del/' . $user->id .'/'.$user->verify)."'>إضغط هنا اذا كنت تريد حذف هذا الحساب </a>";
            $ActiveUrl = "<a target='_blank' style='font-size:26px;' href='".url('stuff/user/activate/' . $user->id .'/'.$user->verify)."'>إضغط هنا إذا كنت تريد تفعيل الحساب</a>";
        }


        if($file = $request->file('userIdPhoto1'))
        {
            $userIdPhoto1 = time() . $file->getClientOriginalName();
            $file->move($user->uploads(), $userIdPhoto1);
            $userIdPhoto1 = "uploads/" .$userIdPhoto1;
        }
        
        if($file = $request->file('userIdPhoto2'))
        {
            $userIdPhoto2 = time() . $file->getClientOriginalName();
            $file->move($user->uploads(), $userIdPhoto2);
            $userIdPhoto2 = "uploads/" .$userIdPhoto2;
        }
        
        $to = "heshmat72@gmail.com, emade09@gmail.com, mh@science.sohag.edu.eg, taha@sohag.edu.eg";
        $subject = $contactName . " رسالة من دعم  - صفحة الدعم الفني";
        date_default_timezone_set('Africa/Cairo');
        $time = date("Y-m-d h:i:sa");
        $message = "
        <html>
        <head>
        <title>$subject</title>
        </head>
        <body dir='trl'>
        <div style='direction:rtl'>
        <h1>هذه الرسالة مرسلة من صفحة الدعم الفني لمواقع أعضاء هيئة التدريس!</h1>
        <h2>معلومات الدعم:</h2>
        <table border='1' width='100%'>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>وقت الارسال</p></th>
                <td><p style='font-size:18 color:#999;'>$time</p></td>
            </tr>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>متصفح العضو</p></th>
                <td><p style='font-size:18 color:#999;'>$browser</p></td>
            </tr>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>عنوان جهاز العضو</p></th>
                <td><p style='font-size:18 color:#999; text-align:left;'>$getIP</p></td>
            </tr>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>الاسم</p></th>
                <td><p style='font-size:18 color:#999;'>$contactName</p></td>
            </tr>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>الكليه</p></th>
                <td><p style='font-size:18 color:#999;'>$facName</p></td>
            </tr>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>رقم الجوال</p></th>
                <td><p style='font-size:18 color:#999;'>$phone</p></td>
            </tr>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>البريد الإلكتروني</p></th>
                <td><p style='font-size:18 color:#999;'>$email</p></td>
            </tr>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>الرقم القومي</p></th>
                <td><p style='font-size:18 color:#999;'>$userId</p></td>
            </tr>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>الموقع الإلكتروني</p></th>
                <td><p style='font-size:18 color:#999;'>$web</p></td>
            </tr>
            ";

            if($file = $request->file('userIdPhoto1'))
            $message.=" 
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>وجه البطاقة</p></th>
                <td><p style='font-size:18 color:#999;'><img src='".url($userIdPhoto1)."' alt='وجه البطاقة'></p></td>
            </tr>
            ";

            if($file = $request->file('userIdPhoto2'))
            $message.="
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>ظهر البطاقة</p></th>
                <td><p style='font-size:18 color:#999;'><img src='".url($userIdPhoto2)."' alt='ظهر البطاقة'></p></td>
            </tr>
            ";

            $message.="
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>الرسالة</p></th>
                <td><p style='font-size:18 color:#999;'>$comments</p></td>
            </tr>
            
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'>هل كان يوجد موقع لهذا البريد</p></th>
                <td><p style='font-size:18 color:#999;'><span style='font-size:24px; color:#000;'>$isEmail</span> <br> $url</p></td>
            </tr>
            <tr>
                <th><p style='font-size:18 color:blue; font-weight: bold;'> حالة الحساب الأن</p></th>
                <td><p style='font-size:18 color:#000;'>$isActive <br>$ActiveUrl <br> $deletUser</p></td>
            </tr>
        </table>
        </div>
        </body>
        </html>
        ";
        
        
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        // More headers
        //$headers .= 'From: <'.$email.'>' . "\r\n";
        $headers .= 'From: <staffsites.sohag>' . "\r\n";
        //$headers .= 'Cc: myboss@example.com' . "\r\n";

        //return $message;
        
        if(mail($to,$subject,$message,$headers))
        {
            if($isNewUser) $isNewUser = "سيتم  انشاء حساب لك بالبيانات التي ارسلتها خلال 6 ساعات بحد اقصي من الأن يمكنك بعدها تسجيل الدخول بالبريد الالكتروني الذي ارسلته و كلمه المرور هي الرقم القومي الخاص بك";
            return redirect('stuff/helpe')->with('success', '
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                         تم إرسال الرسالة بنجاح <span id="isNewUser">'. $isNewUser .' <br> الرجاء من سيادتك تفعيل البريد الجامعي الخاص بك و متابعته - سيقوم الموقع بارسال رساله اليه تؤكد عليك تفعيل الموقع وشكرا</span>
                    </div>
                ');	
        }else{
            return back()->withInput()->withErrors(['backError' => '
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     عذرا لم يتم الارسال حاول مرة أخري
                </div>
                '
            ]);
        }

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
        //
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
        //
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
}
