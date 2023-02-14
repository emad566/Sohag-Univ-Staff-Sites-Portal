<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Photo;
use App\Adv;
use App\File;
use Illuminate\Http\Request;
use App\Http\Requests\AdvCreateRequest;

class AdvController extends Controller
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
        return view('stuff/advs/index', compact(['user', 'isOwner']));
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
        return view('stuff/advs/create', compact(['user', 'isOwner']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdvCreateRequest $request)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();
        $inputs['faculty_id'] = $user->faculty_id;
        $file_id= $request->fileId;
        $photo_id= $request->photo_id;
        unset($inputs['file_id']);
        unset($inputs['photo_id']);

        $adv = $user->advs()->create($inputs);

        
        if ($adv){ 
            $user->percentage();
            //create Photoable photo
            if($file = $request->file('photo_id'))
            {
                $photoName = time() . $file->getClientOriginalName();
                $file->move($user->uploads(), $photoName);
                $adv->photos()->create(['name' => $photoName]);
            }

            File::where('fileable_id', $file_id)->update(['fileable_id'=> $adv->id ]);
            return redirect('stuff/advs')->with('success',   trans('main.saveMsg')  );
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
        $adv = Adv::findOrFail($id);
        $user = User::findOrFail($adv->user->id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/advs/show', compact(['user', 'isOwner', 'adv']));
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
        $isOwner = $user->isOwner($user->id);
        $adv = Adv::findOrFail($id);
        return view('stuff/advs/edit', compact(['user', 'isOwner','adv']));
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
        $messages = [
            'required' => trans('main.required'),
            'title.unique' => trans('main.titleUniqueMsg'),
            'mimes' => trans('main.photoMsg'),
            'image' => trans('main.photoMsg'),
            'photo_id.max' => trans('main.photoMsg'),
        ];

        $this->validate($request, [
            'title' => 'required|unique:advs,title,'.$id.',id,user_id,'. Auth::user()->id,
            'content' => 'required',
            'photo_id' => 'file|image|mimes:jpeg,jpg,png,gif,webp|max:200',
        ], $messages);

        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();
        $inputs['faculty_id'] = $user->faculty_id;

        $adv = Adv::findOrFail($id);
        $advtTitle = $adv->title;
                
        $delIds = $request->delId;

        $advUpdated = $user->advs()->whereId($id)->first()->update($inputs);
        if ($advUpdated){ 
            $user->percentage();

            //Update Photoable photo
            if($file = $request->file('photo_id'))
            {
                $photoName = time() . $file->getClientOriginalName();
                $file->move($user->uploads(), $photoName);
                
                if($adv->photos()->first() != null)
                {
                    if ($adv->photos()->first() != null && file_exists($user->uploads() . $adv->photos()->first()->name)){
                        unlink($user->uploads() . $adv->photos()->first()->name);
                    }
                    $adv->photos()->first()->update(['name' => $photoName]);
                }else{
                    $adv->photos()->create(['name' => $photoName]);
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
              
            return redirect('stuff/advs')->with('success',   trans('main.saveMsg')  );
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
        
        $adv = Adv::findOrFail($id);
       
        if ($adv->photos()->first() != null && file_exists($user->uploads() . $adv->photos()->first()->name)){
            unlink($user->uploads() . $adv->photos()->first()->name);
            $adv->photos()->first()->delete();
        }

        if(isset ($adv->files))
        {
            foreach ($adv->files as $file) 
            {
                if (file_exists($user->uploads() . $file->name))
                {
                    unlink($user->uploads() . $file->name);
                }
            }
            $adv->files()->delete();
        }

        if ( $user->advs()->whereId($id)->first()->delete())
        {
            $user->percentage();
            return redirect('stuff/advs')->with('success',   trans('main.deleteMsg')  );
        }
        return back()->withInput()->with('errors', trans('main.deleteMsgError'));
        
    }
}
