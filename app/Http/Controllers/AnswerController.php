<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\User;
use App\Answer;
use App\File;
use Illuminate\Http\Request;
use App\Http\Requests\answerCreateRequest;

class AnswerController extends Controller
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
    public function store(answerCreateRequest $request)
    {

        $user = Auth::user();
		// \App::setLocale($user->lang);
        
        $inputs = $request->all();
        session_start();   
        
        $answer = Answer::where('task_id', '=', $request->task_id)->where('stuId', '=', $request->stuId)->first();

        if (isset($answer->id)) {
            return back()->withInput()->withErrors(['backError' => '
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        هذا الرقم القومي قام بالإجابة علي هذا السؤال سابقا 
                    </div>
                '
            ]);
        }else if ($request->answer == "") {
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
                     إجابة السؤال الإجبار ي خطأ حاول مرة إخري   
                </div>
                '
            ]);
        }

        $inputs = $request->all();
        
        $file_id= $request->fileId;
        $photo_id= $request->photo_id;
        unset($inputs['file_id']);

        $answer = Answer::create($inputs);
        if ($answer){ 
            File::where('fileable_id', $file_id)->update(['fileable_id'=> $answer->id ]);
            return redirect('stuff/tasks/show/'.$inputs['task_id'].'?p=tasks')->with('success',   trans('main.saveMsg')  );
        }else
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
        $answer = answer::findOrFail($id);
        $task = Task::findOrFail($answer->task_id);
        $user = User::findOrFail($task->user->id);
        
        $isOwner = $user->isOwner($user->id);
        return view('stuff/answers/show', compact(['user', 'isOwner', 'answer', 'task']));
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
        $answer = Answer::find($id);
        if($answer) 
        {
            $answer = $answer->update(['stuDegree'=> $request->stuDegree]);
            if($answer){
                return back()->withInput()->with(['success' => trans('main.saveMsg')]);
            }else
                return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
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
        
        $user = Auth::user();
		\App::setLocale($user->lang);
        $answer = Answer::findOrFail($id);
        //return $answer->task->id;
        //return $id;   
        if(isset ($answer->files))
        {
            foreach ($answer->files as $file) 
            {
                if (file_exists($user->uploads() . $file->name))
                {
                    unlink($user->uploads() . $file->name);
                }
            }
            $answer->files()->delete();
        }
        
        if ($answer->delete())
        {
            $user->percentage();
            return redirect('stuff/tasks/show/'.$answer->task->id.'?p=tasks')->with('success',   trans('main.deleteMsg')  );
            //return back()->with('success',   trans('main.deleteMsg')  );
        }
        return back()->withInput()->with('errors', trans('main.deleteMsgError'));
    }
}
