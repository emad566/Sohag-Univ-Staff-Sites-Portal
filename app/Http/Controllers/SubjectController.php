<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Subject;
use Illuminate\Http\Request;
use App\Http\Requests\SubjectCreateRequest;
use App\User;
use App\File;

class SubjectController extends Controller
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
        return view('stuff/subjects/index', compact(['user', 'isOwner']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $isOwner = $user->isOwner($user->id);
        return view('stuff/subjects/create', compact(['user', 'isOwner']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectCreateRequest $request)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();
        $inputs['faculty_id'] = $user->faculty_id;
        
        $file_id= $request->fileId;
        
        $subjects = $user->subjects()->create($inputs);
        if ($subjects){ 
            $user->percentage();
            File::where('fileable_id', $file_id)->update(['fileable_id'=> $subjects->id ]);
            return redirect('stuff/subjects')->with('success',   trans('main.saveMsg')  );
        }else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $subject = Subject::findOrFail($id);
        $user = User::findOrFail($subject->user->id);
        $isOwner = $user->isOwner($user->id);
        
        return view('stuff/subjects/show', compact(['user', 'isOwner', 'subject']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $isOwner = $user->isOwner($user->id);
        $subject = Subject::findOrFail($id);
        return view('stuff/subjects/edit', compact(['user', 'isOwner','subject']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'required' => trans('main.required'),
            'required' => trans('main.required'),
            'title.unique' => trans('main.titleUniqueMsg'),
            'title.max' => trans('main.char250'),
        ];

        $this->validate($request, [
            'title' => 'required|max:250|unique:subjects,title,'.$id.',id,user_id,'. Auth::user()->id,
            'content' => 'required',
        ], $messages);

        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();

        $subject = Subject::findOrFail($id);
        $sujectTitle = $subject->title;
                
        $delIds = $request->delId;

        $subjectUpdated = $user->subjects()->whereId($id)->first()->update($inputs);
        if ($subjectUpdated){ 
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
              
            return redirect('stuff/subjects')->with('success',   trans('main.saveMsg')  );
        }else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $subject = Subject::findOrFail($id);
      
       /*  
        if (isset ($subject->file) && file_exists($subject->file->uploads . $subject->file->name)){
            unlink($subject->file->uploads . $subject->file->name);
        }
 */
        if(isset ($subject->files))
        {
            foreach ($subject->files as $file) 
            {
                if (file_exists($user->uploads() . $file->name))
                {
                    unlink($user->uploads() . $file->name);
                }
            }
            $subject->files()->delete();
        }
       

        //delete related supplement files
        $supplements = $subject->supplements;

        foreach ($supplements as $supplement) {
            if(isset ($supplement->files) && $supplement->files->count() > 0)
            {
                foreach ($supplement->files as $file) 
                {
                    if (file_exists($user->uploads() . $file->name))
                    {
                        unlink($user->uploads() . $file->name);
                    }
                }
                
                $supplement->files()->delete();
            }
        }
        $subject->supplements()->delete();

        //delete related task files
        $tasks = $subject->tasks;

        foreach ($tasks as $task) {
            if(isset ($task->files) && $task->files->count() > 0)
            {
                foreach ($task->files as $file) 
                {
                    if (file_exists($user->uploads() . $file->name))
                    {
                        unlink($user->uploads() . $file->name);
                    }
                }
                
                $task->files()->delete();
            }
        }
        $subject->tasks()->delete();

        if ( $user->subjects()->whereId($id)->first()->delete())
        {
            $user->percentage();
            return redirect('stuff/subjects')->with('success',   trans('main.deleteMsg')  );
        }
        return back()->withInput()->with('errors', trans('main.deleteMsgError'));
        
    }

    public function sheetPrint($subject_id="all"){
        $user = Auth::user();
        $isOwner = $user->isOwner($user->id);
        $subjects = $user->subjects;
        $subjectPrint = null;
        if($subject_id == "all"){
            $students = null;
        }else{
            $subjectPrint = Subject::findOrFail($subject_id);
            
            $students = $subjectPrint->answers()->orderBy('name', 'ASC')->distinct()->get(['name', 'stuId']);
            // $students = $subjectPrint->answers()->orderBy('stuId', 'ASC')->distinct('stuId')->get(['stuId', 'name']);
            /* foreach($answers as $answer){
                echo $answer->name."<br>";
            } */
        }
        return view('stuff/subjects/sheetprint', compact(['user', 'isOwner', 'students', 'subjects', 'subjectPrint', 'subject_id']));
    }
}

