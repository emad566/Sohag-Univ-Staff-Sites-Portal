<?php

namespace App\Http\Controllers;

use App\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ResearchCreatRequest;
use App\Faculty;
use App\Subject;
use App\File;
use App\User;
use App\Level;

class ResearchController extends Controller
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
            $researchs = $user->researchs()->orderBy('updated_at', 'DESC')->get();
        }else{
            $researchs = $user->researchs()->where('subject_id', '=', $subject_id)->orderBy('updated_at', 'DESC')->paginate(10);
        }
        return $researchs;

        return view('stuff/researchs/index', compact(['user', 'isOwner', 'subjects', 'researchs', 'subject_id']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $levels = Level::all();
        $user = Auth::user();
        $faculties = Faculty::all();
        $isOwner = $user->isOwner($user->id);
        return view('stuff/researchs/create', compact(['user', 'isOwner', 'faculties', 'levels']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResearchCreatRequest $request)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();
        $inputs['faculty_id'] = $user->faculty_id;    
        $inputs['user_id'] = $user->id;
        
        $file_id= $request->fileId;
        $photo_id= $request->photo_id;
        unset($inputs['file_id']);

        $subject = Subject::findOrFail($request->subject_id);
        // return $request->subject_id;
        foreach ($subject->researchs as $researchCheck) {
            if($request->title == $researchCheck->title){
                return back()->withInput()->withErrors(['title' => trans('main.titleUniqueMsg')] );
            }
            
        }

        $research = Research::create($inputs);
        if ($research){ 
            $user->percentage();
            //create Photoable photo
            
            File::where('fileable_id', $file_id)->update(['fileable_id'=> $research->id ]);
            return redirect('stuff/researchs')->with('success',   trans('main.saveMsg')  );
        }else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $research = Research::findOrFail($id);
        $user = User::findOrFail($research->user->id);
        $faculties = Faculty::all();

        $answers = $research->answers()->orderBy('name', 'ASC')->distinct('stuId')->get();
        
        $isOwner = $user->isOwner($user->id);
        return view('stuff/researchs/show', compact(['user', 'isOwner', 'research', 'faculties', 'answers']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $levels = Level::all();
        $user = Auth::user();
        $faculties = Faculty::all();
        $research = Research::FindOrFail($id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/researchs/edit', compact(['user', 'isOwner', 'research', 'faculties', 'levels']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function update(ResearchCreatRequest $request, $id)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();

        $research = research::findOrFail($id);
        $researchTitle = $research->title;
                
        $delIds = $request->delId;

        $subject = Subject::findOrFail($request->subject_id);
        foreach ($subject->researchs as $researchsCheck) {
            if($request->title == $researchsCheck->title && $researchsCheck->id != $research->id){
                return back()->withInput()->withErrors(['title' => trans('main.titleUniqueMsg')] );
            }
        }

        $researchUpdated = $research->update($inputs);
        if ($researchUpdated){ 
            $user->percentage();
            //Update Photoable photo

            if(count ($delIds) > 0)
            {
                foreach($delIds as $delId)
                { 
                    $file = File::find($delId);
                    if($file != null) {
                        if (file_exists($user->uploads('researchs') . $file->name))
                        {
                            unlink($user->uploads('researchs') . $file->name);
                        }
                        $file->delete();
                    }

                } 
            }
              
            return redirect('stuff/researchs')->with('success',   trans('main.saveMsg')  );
        }else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        // $research = research::findOrFail($id);

        if(isset ($research->files))
        {
            foreach ($research->files as $file) 
            {
                if (file_exists($user->uploads('researchs') . $file->name))
                {
                    unlink($user->uploads('researchs') . $file->name);
                }
            }
            $research->files()->delete();
        }

        if ( $research->delete())
        {
            $user->percentage();
            return redirect('stuff/researchs')->with('success',   trans('main.deleteMsg')  );
        }
        return back()->withInput()->with('errors', trans('main.deleteMsgError'));
    }

    public function getresearchs(Request $request)
    {
        $faculties = Faculty::all();
        $levels = Level::all();

        $stuId = NULL;
        $fac_id = NULL;
        $level_id = NULL;
        $researchs = Null;
        $user = User::find(2);
        if($request->facStu_id){
            $fac_id = $request->facStu_id;
            $level_id = $request->level_id;
            $stuId = $request->stuId;

            $researchs = Research::where('facStu_id', '=', $fac_id)->where('level_id', '=', $level_id)->orderBy("updated_at", "DESC")->get();
        }
        return view('frontend/researchs', compact(['user', 'faculties', 'levels', 'researchs', 'fac_id', 'level_id', 'stuId']));
    }
}
