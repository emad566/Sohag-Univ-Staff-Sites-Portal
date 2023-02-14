<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\File;
use App\Photo;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\PostCreatRequest;

class PostController extends Controller
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
        return view('stuff/posts/index', compact(['user', 'isOwner']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $tags = Tag::all();
        $tagsItems = "[";
        foreach ($tags as $tag) {
            $tagsItems .= '"' . str_replace('"', ' ', $tag->name) . '",' ."\r\n";
            $tagsItems = str_replace("\\" , ' ', $tagsItems);
            $tagsItems = str_replace("/" , '', $tagsItems);
        }
        rtrim($tagsItems, ",");
        $tagsItems .= "]";
        
        $isOwner = $user->isOwner($user->id);
        return view('stuff/posts/create', compact(['user', 'isOwner', 'tagsItems']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostCreatRequest $request)
    {
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $inputs = $request->all();
        $file_id= $request->fileId;
        unset($inputs['search']);
        unset($inputs['file_id']);
        $inputs['faculty_id'] = $user->faculty_id;
        if($file = $request->file('photo_id'))
        {
            $photoName = time() . $file->getClientOriginalName();
            $file->move($user->uploads(), $photoName);

            $photo = Photo::create(['name' => $photoName]);
            $inputs['photo_id'] = $photo->id;

        }else{
            $inputs['photo_id'] = null;
        }
        
        $tags = $inputs['tags'];
        unset($inputs['tags']);

        
        $post = $user->posts()->create($inputs);
        if ($post)
        {   
            $user->percentage();
            if ($tags !="")
            {
                $tags = explode(",",$tags);
                foreach ($tags as $tag) {
                    $isTaged = Tag::where('name', $tag)->first();
                    if($isTaged)
                        $post->tags()->attach($isTaged->id);
                    else $post->tags()->create(['name'=> $tag]);
                }
            } 
            File::where('fileable_id', $file_id)->update(['fileable_id'=> $post->id ]);
            return redirect('stuff/posts')->with('success',   trans('main.saveMsg')  );
        }else return "bad store";
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
        $post = Post::findOrFail($id);
        $user = User::findOrFail($post->user->id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/posts/show', compact(['user', 'isOwner', 'post']));
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
        $post = Post::findOrFail($id);

        //emplod all tags fot tagIt
        $tags = Tag::all();
        $tagsItems = "[";
        foreach ($tags as $tag) {
            $tagsItems .= '"' . str_replace('"', ' ', $tag->name) . '",' ."\r\n";
            $tagsItems = str_replace("\\" , ' ', $tagsItems);
            $tagsItems = str_replace("/" , '', $tagsItems);
        }
        
        rtrim($tagsItems, ",");
        $tagsItems .= "]";

        //Emplod choosed Tags
        $tagsChoosed ="";
        if(isset($post->tags)){
            foreach ($post->tags as $tag) {
                $tagsChoosed .= $tag->name . ",";
            }
            $tagsChoosed = rtrim($tagsChoosed, ',');
        }
        
        return view('stuff/posts/edit', compact(['user', 'isOwner','post', 'tagsChoosed', 'tagsItems']));
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
            'required' => trans('main.required'),
            'title.unique' => trans('main.titleUniqueMsg'),
            'title.max' => trans('main.char400'),
            'mimes' => trans('main.photoMsg'),
            'image' => trans('main.photoMsg'),
            'photo_id.max' => trans('main.photoMsg'),

            'year.max' => trans('main.num4'),
            'auther.max' => trans('main.char400'),
            'url.max' => trans('main.char400'),
            'urlTitle.max' => trans('main.char400'),
            'journal.max' => trans('main.char400'),
            'num.max' => trans('main.char400'),
            'yearNum.max' => trans('main.char400'),
        ];

        $this->validate($request, [
            'title' => 'required|max:400|unique:posts,title,'.$id.',id,user_id,'. Auth::user()->id,
            'content' => 'required',
            'photo_id' => 'file|image|mimes:jpeg,jpg,png,gif,webp|max:200',

          
            'year' => 'max:4',
            'auther' => 'max:400',
            'url' => 'max:400',
            'urlTitle' => 'max:400',
            'journal' => 'max:400',
            'num' => 'max:14',
            'yearNum' => 'max:4',

        ], $messages);
        
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $post = Post::findOrFail($id);

        $inputs = $request->all();
        
        unset($inputs['search']);
        unset($inputs['photo_id']);
        
        if($file = $request->file('photo_id'))
        {
            $photoName = time() . $file->getClientOriginalName();
            $file->move($user->uploads(), $photoName);
            if(isset($post->photo))
            {
                $post->photo()->update(['name' => $photoName]);
                $inputs['photo_id'] = $post->photo_id;
            }else{
                $photo = Photo::create(['name' => $photoName]);
                $inputs['photo_id'] = $photo->id;
            }
            
        }

        $tags = $inputs['tags'];
        unset($inputs['tags']);

        $delIds = $request->delId;
        
        
        $postIsUpdated = $post->update($inputs);

        if ($postIsUpdated)
        {   
            $user->percentage();
            //File Updates
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

            //tags update -- detach all tags
            if(isset($post->tags))
            {
                foreach ($post->tags as $tag) {
                    $post->tags()->detach($tag->id);
                }
            }

            //attach or create new attach
            if ($tags !="")
            {
                $tags = explode(",",$tags);
                foreach ($tags as $tag) {
                    $isTaged = Tag::where('name', $tag)->first();
                    if($isTaged)
                        $post->tags()->attach($isTaged->id);
                    else $post->tags()->create(['name'=> $tag]);
                }
            }

            return redirect('stuff/posts')->with('success',   trans('main.saveMsg')  );
        }else return "bad store";
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
        
        $post = Post::findOrFail($id);
      
        //Delete post photo
        if (isset ($post->photo) && file_exists($user->uploads() . $post->photo->name)){
            unlink($user->uploads() . $post->photo->name);
            $post->photo()->delete();
        }

        //Delete post files
        if(isset ($post->files))
        {
            foreach ($post->files as $file) 
            {
                if (file_exists($user->uploads() . $file->name))
                {
                    unlink($user->uploads() . $file->name);
                }
            }
            $post->files()->delete();
        }

        //Detach all tags
        if(isset($post->tags))
        {
            foreach ($post->tags as $tag) {
                $post->tags()->detach($tag->id);
            }
        }

        if ( $user->posts()->whereId($id)->first()->delete())
        {
            $user->percentage();
            return redirect('stuff/posts')->with('success',   trans('main.deleteMsg')  );
        }
        return back()->withInput()->with('errors', trans('main.deleteMsgError'));
        
    }
}
