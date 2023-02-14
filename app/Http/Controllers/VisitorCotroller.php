<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Visitor;

class VisitorCotroller extends Controller
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
    public function store(Request $request)
    {
        if($request->ajax())
        {
            $emad = User::findOrFail(2);
            $rand = rand ( 1, 11 );

            $inputs = $request->all();
            $inputs['lastVisit'] = time();

            if(!isset($_COOKIE['ipDesc']))
                setcookie('ipDesc', $request->ip, time() + (60 * 60  *25), "/");

            
            $user = User::find($request->user_id);
            $userNoUpdate = $user;

            if (strpos($request->ip, 'ip=') === false) {
                return 'fake: ' . $request->ip;
            }

            if($user->visitors()->count() > 0){
                
                $LastVisit = $user->visitors()->where('ip', $request->ip)->orderBy('lastVisit', 'DESC')->first();

                if($LastVisit){
                    $LastVisit->update(['times' => $LastVisit->times + 1]);
                }

                if($LastVisit)
                {
                    $sepTmime = ($user->id == 769 || $user->id == 3070) ? 992800 : 10800;
                    if((time() - $LastVisit->lastVisit) > $sepTmime){                        
                        if ($user->visitors()->create($inputs)) {
                            $user->update(['mostView'=> $user->visitors()->count()]);
                            return $user->id . " new visit after 10800 ip= " . $request->ip ;
                        }
                    }
                    return $user->id . " Still OLd Visit ip= " . $request->ip ;
                }
                
            }

            if ($user->visitors()->create($inputs)) {
                $user->update(['mostView'=> $user->visitors()->count()]);
                return $user->id . " new visit first Time ip= " . $request->ip;
            }

            
            return "nothing";
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
