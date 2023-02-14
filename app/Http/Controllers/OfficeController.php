<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
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
        return view('stuff/offices/index', compact(['user', 'isOwner']));
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
        return view('stuff/offices/create', compact(['user', 'isOwner']));
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
		\App::setLocale($user->lang);
        
        $offics = $request->all();
        $officeName = $offics['office'];

        if($user->offices()->count()>0)
        {
            foreach ($user->offices as $officsCheck) {
                if(
                    $request->office == $officsCheck->office && 
                    $request->startTime == $officsCheck->startTime && 
                    $request->endTime == $officsCheck->endTime && 
                    $officsCheck->checkSetDaysAttribute($request->days) == $officsCheck->days 
                ){
                    return back()->withInput()->withErrors(['offoceMultiMsg' => trans('main.offoceMultiMsg')] );
                }
            }
        }
        

        $office = $user->offices()->create($request->all());

        if($office)
        {
            $user->percentage();
            return redirect('stuff/offices')->with('success',   trans('main.saveMsg')  );
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
        $office = Office::findOrFail($id);
        $user = User::findOrFail($office->user->id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/offices/show', compact(['user', 'isOwner', 'office']));
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
        $office = Office::findOrFail($id);
        $isOwner = $user->isOwner($user->id);
        return view('stuff/offices/edit', compact(['user', 'isOwner', 'office']));
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
        $user = Auth::user();
		\App::setLocale($user->lang);
        
        $office = Office::findOrFail($id);
        $officeName = $office->office;

        foreach ($user->offices as $officsCheck) {
            if(
                $request->office == $officsCheck->office && 
                $request->startTime == $officsCheck->startTime && 
                $request->endTime == $officsCheck->endTime && 
                $officsCheck->checkSetDaysAttribute($request->days) == $officsCheck->days &&
                $office->id != $officsCheck->id
            ){
                return back()->withInput()->withErrors(['offoceMultiMsg' => trans('main.offoceMultiMsg')] );
            }
        }

        $office = $office->update($request->all());

        if($office)
        {
            $user->percentage();
            return redirect('stuff/offices')->with('success',   trans('main.saveMsg')  );
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
        
        $office = Office::findOrFail($id);
        $officeName = $office->office;
        if($office->delete())
        {
            $user->percentage();
            return redirect('stuff/offices')->with('success',   trans('main.deleteMsg')  );
        }else
            return back()->withInput()->with('errors', trans('main.deleteMsgError'));

    }
}
