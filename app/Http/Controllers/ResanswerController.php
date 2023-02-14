<?php

namespace App\Http\Controllers;

use App\Resanswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\File;
use App\Research;

class ResanswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $user = Auth::user();
		// \App::setLocale($user->lang);
        
        $inputs = $request->all();
        session_start();   
        $research = Research::find($request->research_id);
        
        $resanswer = Resanswer::where('research_id', '=', $request->research_id)->where('stuId', '=', $request->stuId)->first();

        if (isset($resanswer->id)) {
            return back()->withInput()->withErrors(['backError' => '
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        هذا الرقم القومي قام بالإجابة علي هذا السؤال سابقا 
                    </div>
                '
            ]);
        }else if ($request->resanswer == "") {
            return back()->withInput()->withErrors(['backError' => '
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     من فضلك أجب علي السؤال الاجباري قبل الضغط علي ارسال.
                </div>
                '
            ]);
        }elseif($request->resanswer != $_SESSION['resanswer']){
            return back()->withInput()->withErrors(['resanswer' => '
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     إجابة السؤال الإجبار ي خطأ حاول مرة إخري   
                </div>
                '
            ]);
        }

        if($file = $request->file('file_id'))
        {
            $fileName = time() . $file->getClientOriginalName();
            $file->move($research->user->uploads('researchs'), $fileName);

            $file = File::create(['name' => $fileName, 'fileable_id'=>NULL, 'fileable_type'=>'App\Resanswer', 'user_id'=> $research->user->id, 'faculty_id'=>$research->user->faculty->faculty_id]);
            $inputs['file_id'] = $file->id;
        }

        $inputs = $request->all();
        
        $file_id= $request->fileId;
        unset($inputs['file_id']);

        $resanswer = Resanswer::create($inputs);
        if ($resanswer){ 
            if($file){
                $file->update(['fileable_id'=>$resanswer->id]);
            }
            File::where('fileable_id', $file_id)->update(['fileable_id'=> $resanswer->id ]);
            return redirect('stuff/researchs/show/'.$inputs['research_id'].'?p=researchs')->with('success',   trans('main.saveMsg')  );
        }else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Resanswer  $resanswer
     * @return \Illuminate\Http\Response
     */
    public function show(Resanswer $resanswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resanswer  $resanswer
     * @return \Illuminate\Http\Response
     */
    public function edit(Resanswer $resanswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resanswer  $resanswer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resanswer $resanswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resanswer  $resanswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resanswer $resanswer)
    {
        //
    }

    public function respass(Request $request){
        $answer = Resanswer::findOrFail($request->id);
        $answer->update(['pass' => $request->raceMark]);
        return $answer->pass;
    }
    
    public function uploadResearch(Request $request){ 
        $user = Auth::user();
        // \App::setLocale($user->lang);
        $msg = "";

        if ($request->name == "") {
            $msg .= '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     من فضلك أكتب أسمك.
                </div>
                ';
        }
        if ($request->stuId == "") {
            $msg .= '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        من فضلك أدخل الرقم القومي.
                </div>
                ';
        }

        if(!$request->file('file_id'))  {
            $msg .= '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        من فضلك أرفق ملف بصيغة PDF.
                </div>
                ';
        } 
        
        if ($msg){
            return $msg;
        }

        $inputs = $request->all();
        session_start();

        $research = Research::find($request->research_id);
        
        $resanswer = Resanswer::where('research_id', '=', $request->research_id)->where('stuId', '=', $request->stuId)->first();

        if (isset($resanswer->id)) {
             return '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        هذا الرقم القومي قام بالإجابة علي هذا السؤال سابقا 
                    </div>
                ';
        }else if ($request->resanswer == "") {
            return '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     من فضلك أجب علي السؤال الاجباري قبل الضغط علي حفظ.
                </div>
                ';
        }elseif($request->resanswer != $_SESSION['resanswer']){
            return '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     إجابة السؤال الإجبار ي خطأ حاول مرة إخري   
                </div>
                ';
        }

        if($file = $request->file('file_id'))
        {
            $fileName = time() . $file->getClientOriginalName();
            $targetFilePath = $research->user->uploads('researchs') .$fileName;

            $allowTypes = array('pdf');
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if(in_array($fileType, $allowTypes)){ 
                $file->move($research->user->uploads('researchs'), $fileName);
            }else{
                return "صيغة ملف خطأ: من فضلك اختر ملف بصيغة PDF";
            }

            $file = File::create(['name' => $fileName, 'fileable_type'=>'App\Resanswer', 'user_id'=> $research->user->id, 'faculty_id'=>$research->user->faculty->faculty_id]);
            $inputs['file_id'] = $file->id;
           
            $resanswer = Resanswer::create($inputs);
            if ($resanswer){    
                if($file){
                    $file->update(['fileable_id'=>$resanswer->id]);
                }
                return 'تم الحفظ بنجاح، شكرا لك.';
            }

        }

        return '<div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        حدث خطأ غير مفهوم: من فضلك اتصل بالدعم الفني من صفحة الدعم الفني.
        </div>
        ';
    }
}