<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\userPassRequest;

class HomeViewController extends Controller
{
    public function subjects($id)
    {
        $user = User::findOrFail($id);
        $subjects = $user->subjects()->orderBy('updated_at', 'DESC')->paginate(10);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/home/subjects', compact(['user', 'isOwner', 'subjects']));
    }

    public function posts($id)
    {
        $user = User::findOrFail($id);
        $isOwner = $user->isOwner($user->id);
        $posts = $user->posts()->orderBy('year', 'DESC')->paginate(10);
        return view('stuff/home/posts', compact(['user', 'isOwner', 'posts']));
    }

    public function offices($id)
    {
        $user = User::findOrFail($id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/home/offices', compact(['user', 'isOwner']));
    }

    public function advs($id)
    {
        $user = User::findOrFail($id);
        $advs = $user->advs()->orderBy('updated_at', 'DESC')->paginate(10);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/home/advs', compact(['user', 'isOwner', 'advs']));
    }

    public function supplements($id, $subject_id = "all")
    {
        $user = User::findOrFail($id);
        $isOwner = $user->isOwner($user->id);
        $subjects = $user->subjects;

        if($subject_id == "all"){
            $supplements = $user->supplements()->orderBy('updated_at', 'DESC')->paginate(10);
        }else{
            $supplements = $user->supplements()->where('subject_id', '=', $subject_id)->orderBy('updated_at', 'DESC')->paginate(10);
        }
        

        return view('stuff/home/supplements', compact(['user', 'isOwner', 'supplements', 'subject_id']));
    }
    
    public function tasks($id, $subject_id = "all")
    {
        $user = User::findOrFail($id);
        $isOwner = $user->isOwner($user->id);
        $subjects = $user->subjects;

        if($subject_id == "all"){
            $tasks = $user->tasks()->orderBy('updated_at', 'DESC')->paginate(10);
        }else{
            $tasks = $user->tasks()->where('subject_id', '=', $subject_id)->orderBy('updated_at', 'DESC')->paginate(10);
        }
        return view('stuff/home/tasks', compact(['user', 'isOwner', 'tasks', 'subjects', 'subject_id']));
        
    }

    public function researchs($id, $subject_id = "all")
    {
        $user = User::findOrFail($id);
        $isOwner = $user->isOwner($user->id);
        $subjects = $user->subjects;

        if($subject_id == "all"){
            $researchs = $user->researchs()->orderBy('updated_at', 'DESC')->paginate(10);
        }else{
            $researchs = $user->researchs()->where('subject_id', '=', $subject_id)->orderBy('updated_at', 'DESC')->paginate(10);
        }
        return view('stuff/home/researchs', compact(['user', 'isOwner', 'researchs', 'subjects', 'subject_id']));
        
    }

    public function tags($id, $user_id)
    {
        $tag = Tag::findOrFail($id);
        $posts = $tag->posts()->orderBy('updated_at', 'DESC')->paginate(10);

        $user = User::findOrFail($user_id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/home/tags', compact(['user', 'isOwner', 'tag', 'posts']));
    }

    public function langchangeIndex()
    {
        $id = Auth()->user()->id;
        if(is_numeric($id))
        {
            $user = User::findOrFail($id);
        }else{
            $user = User::where('name', '=' ,$id)->get()->first();
        }

        if($user == null) return "show null user";
        if($user == null) return redirect('/errors/404');

        $isOwner = $user->isOwner($user->id);
        return view('stuff/home/langchange', compact(['user', 'isOwner'])); 
    }

    public function langUpdate(Request $request)
    {
        $user = Auth::user();
        \App::setLocale($user->lang);

        $inputs = $request->all();
        

        if ($user->update($inputs))
        {
            return back()->with('success',   trans('main.saveMsg')  );
        }else
            return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);

    }

    public function passIndex()
    {
        $user = Auth()->user();
        $isOwner = $user->isOwner($user->id);
        return view('stuff/home/passIndex', compact(['user', 'isOwner']));        
    }

    public function passUpdate(userPassRequest $request)
    {
        \App::setLocale(Auth::user()->lang);
        
        $user = Auth()->user();
        $inputs = $request->all();
        if((Auth::user()->id == $user->id && $user->id == 1) || $user->id !=1)
        { 
            if ($user->update($inputs))
            {
                return back()->with('success',   trans('main.saveMsg')  );
            }else
                return back()->withInput()->withErrors(['backError' => trans('main.saveMsgError')]);
        }else{
            return back()->with('errors', trans('main.AdminUserErrorsMsg'));
        }
    }

}
