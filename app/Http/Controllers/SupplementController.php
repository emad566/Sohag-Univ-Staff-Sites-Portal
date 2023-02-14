<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\SupplementCreateRequest;
Use App\Supplement;
Use App\User;
Use App\Subject;
Use App\File;

class SupplementController extends Controller
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
            $supplements = $user->supplements()->orderBy('updated_at', 'DESC')->get();
        }else{
            $supplements = $user->supplements()->where('subject_id', '=', $subject_id)->orderBy('updated_at', 'DESC')->get();
        }

        return view('stuff/supplements/index', compact(['user', 'isOwner', 'subjects', 'supplements', 'subject_id']));
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
        return view('stuff/supplements/create', compact(['user', 'isOwner']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplementCreateRequest $request)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();
        $inputs['faculty_id'] = $user->faculty_id;    
        $inputs['user_id'] = $user->id;    
        
        $file_id= $request->fileId;

        $subject = Subject::findOrFail($request->subject_id);
        foreach ($subject->supplements as $supplementsCheck) {
            if($request->title == $supplementsCheck->title){
                return back()->withInput()->withErrors(['title' => trans('main.titleUniqueMsg')] );
            }
            
        }

        $supplement = Supplement::create($inputs);
        if ($supplement)
        { 
            $user->percentage();
            File::where('fileable_id', $file_id)->update(['fileable_id'=> $supplement->id ]);
            return redirect('stuff/supplements')->with('success',   trans('main.saveMsg')  );
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
        $supplement = Supplement::findOrFail($id);
        $user = User::findOrFail($supplement->user->id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/supplements/show', compact(['user', 'isOwner', 'supplement']));
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
        $supplement = Supplement::FindOrFail($id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/supplements/edit', compact(['user', 'isOwner', 'supplement']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplementCreateRequest $request, $id)
    {
        
        
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $supplement = Supplement::findOrFail($id);
        $supplementTitle = $supplement->title;
        $inputs = $request->all();
        $delIds = $request->delId;

        $subject = Subject::findOrFail($request->subject_id);
        foreach ($subject->supplements as $supplementsCheck) {
            if($request->title == $supplementsCheck->title && $supplementsCheck->id != $supplement->id){
                return back()->withInput()->withErrors(['title' => trans('main.titleUniqueMsg')] );
            }
        }
        
        $supplementUpdated = $supplement->update($inputs);

        if ($supplementUpdated)
        { 
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

            return redirect('stuff/supplements')->with('success',   trans('main.saveMsg')  );
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
        
        $supplement = Supplement::findOrFail($id);
        
        if(isset ($supplement->files))
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
        
        if ($supplement->delete())
        {
            $user->percentage();
            return redirect('stuff/supplements')->with('success',   trans('main.deleteMsg')  );
        }
        return back()->withInput()->with('errors', trans('main.deleteMsgError'));
    }
}
