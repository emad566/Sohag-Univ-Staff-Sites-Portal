<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Photo;
use App\Task;
use App\Subject;
use App\Faculty;
use App\File;
use Illuminate\Http\Request;
use App\Http\Requests\TaskCreatRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($subject_id="all")
    {        
        $user = Auth::user();
        $subjects = $user->subjects;
        $isOwner = $user->isOwner($user->id);

        if($subject_id == "all"){
            $tasks = $user->tasks()->orderBy('created_at', 'DESC')->get();
        }else{
            $tasks = $user->tasks()->where('subject_id', '=', $subject_id)->orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('stuff/tasks/index', compact(['user', 'isOwner', 'subjects', 'tasks', 'subject_id']));
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
        return view('stuff/tasks/create', compact(['user', 'isOwner']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskCreatRequest $request)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();
        $inputs['faculty_id'] = $user->faculty_id;    
        $inputs['user_id'] = $user->id;
        
        $file_id= $request->fileId;
        $photo_id= $request->photo_id;
        unset($inputs['file_id']);
        unset($inputs['photo_id']);

        $subject = Subject::findOrFail($request->subject_id);
        foreach ($subject->tasks as $tasksCheck) {
            if($request->title == $tasksCheck->title){
                return back()->withInput()->withErrors(['title' => trans('main.titleUniqueMsg')] );
            }
            
        }

        $task = Task::create($inputs);
        if ($task){ 
            $user->percentage();
            //create Photoable photo
            if($file = $request->file('photo_id'))
            {
                $photoName = time() . $file->getClientOriginalName();
                $file->move($user->uploads(), $photoName);
                $task->photos()->create(['name' => $photoName]);
            }

            File::where('fileable_id', $file_id)->update(['fileable_id'=> $task->id ]);
            return redirect('stuff/tasks')->with('success',   trans('main.saveMsg')  );
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
        $task = Task::findOrFail($id);
        $user = User::findOrFail($task->user->id);
        $faculties = Faculty::all();

        $answers = $task->answers()->orderBy('name', 'ASC')->distinct('stuId')->get();
        
        $isOwner = $user->isOwner($user->id);
        return view('stuff/tasks/show', compact(['user', 'isOwner', 'task', 'faculties', 'answers']));
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
        $task = Task::FindOrFail($id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/tasks/edit', compact(['user', 'isOwner', 'task']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskCreatRequest $request, $id)
    {        
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();

        $task = task::findOrFail($id);
        $taskTitle = $task->title;
                
        $delIds = $request->delId;

        $subject = Subject::findOrFail($request->subject_id);
        foreach ($subject->tasks as $tasksCheck) {
            if($request->title == $tasksCheck->title && $tasksCheck->id != $task->id){
                return back()->withInput()->withErrors(['title' => trans('main.titleUniqueMsg')] );
            }
        }

        $taskUpdated = $task->update($inputs);
        if ($taskUpdated){ 
            $user->percentage();
            //Update Photoable photo
            if($file = $request->file('photo_id'))
            {
                $photoName = time() . $file->getClientOriginalName();
                $file->move($user->uploads(), $photoName);
                
                if($task->photos()->first() != null)
                {
                    if ($task->photos()->first() != null && file_exists($user->uploads() . $task->photos()->first()->name)){
                        unlink($user->uploads() . $task->photos()->first()->name);
                    }
                    $task->photos()->first()->update(['name' => $photoName]);
                }else{
                    $task->photos()->create(['name' => $photoName]);
                }
            }

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
              
            return redirect('stuff/tasks')->with('success',   trans('main.saveMsg')  );
        }else
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
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $task = Task::findOrFail($id);
       
        if ($task->photos()->first() != null && file_exists($user->uploads() . $task->photos()->first()->name)){
            unlink($user->uploads() . $task->photos()->first()->name);
            $task->photos()->first()->delete();
        }

        if(isset ($task->files))
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

        if ( $task->delete())
        {
            $user->percentage();
            return redirect('stuff/tasks')->with('success',   trans('main.deleteMsg')  );
        }
        return back()->withInput()->with('errors', trans('main.deleteMsgError'));
        
    }
}
