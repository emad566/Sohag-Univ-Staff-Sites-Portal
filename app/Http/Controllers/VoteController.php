<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Vote;

class VoteController extends Controller
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
    public function store(Request $request, $user_id = "")
    {
        if($request->ajax())
        {
            $inputs = $request->all();
            $inputs['lastVote'] = time();

            if($user_id){
                $user = User::find($user_id);
            }else{
                $user = User::find($request->user_id);
            }
            
            $userNoUpdate = $user;
            $LastVoteIp = $user->votes()->where('ip', $request->ip)->orderBy('lastVote', 'DESC')->first();
            if($LastVoteIp && $user_id){
                return "not_active";
            }else if($user_id){
                return "active";
            }

            if(!$LastVoteIp)
            {
                if ($user->votes()->create($inputs)) {
                    return $user->votesCount($user->id);
                }else{
                    return $user->id . " failed to add to db ip= ";
                }
    
            }else{
                
                $votedUser = $user->votedUser($request->user_id, "$request->ip");
                if( $votedUser){
                    if ($votedUser->update($inputs)) {
                        return $user->votesCount($user->id);
                    }
                }
                return $user->id . " failed to update vote to db ip= ";
            }           
            
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
