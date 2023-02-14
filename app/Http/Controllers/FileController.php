<?php

namespace App\Http\Controllers;
use App\File;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\FileUploadRequest;

class FileController extends Controller
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
    public function store(FileUploadRequest $request)
    {
        
        if($file = $request->file('file'))
        {
            
            $photoName = time() . ' - ' . $file->getClientOriginalName();
            $file->move($request->uploads_id, $photoName);
            $userid  = ($request->fUserId) ? $request->fUserId : 0;
            $user = User::find($userid);
            $faculty_id = ($user)? $user->faculty_id : Null;
            $inputs = [
                'name' =>  $photoName,
                'fileable_id' => $request->fileable_id,
                'fileable_type' => $request->fileable_type,
                'user_id' => $userid,
                'faculty_id'=>$faculty_id,
            ];
            
            $file = File::create($inputs);
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
    
    public function updateDownloaded(Request $request)
    {
        if($request->ajax())
        {
            $file = File::find($request->downloaded);
            if($file){
                $times =  $file->downloaded + 1;
                $file->update(['downloaded' => $times]);
                return $times;
            }
            
            return $request->downloaded;
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
        //
    }
}
