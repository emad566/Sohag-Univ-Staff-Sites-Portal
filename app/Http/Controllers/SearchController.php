<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Faculty;
use App\Emp;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('isActive', '=', '1')->where('role_id', '=', '1')->orWhere('role_id', '=', '3')->whereNotNull('fullName')->orderBy('mostContent', 'desc');//->paginate(60);
        $users = $users->paginate(30);
        $degrees = Emp::all();
        $faculties = Faculty::orderBy('name', 'DESC')->get();
        $count = $users->total();


        $degreeHelper = "'1', '2'";
        $degreeStaff = "'3', '4', '5'";



        $tr = "";

        foreach($faculties as $faculty){
            $helper = DB::select("select id from users where faculty_id =" . $faculty->id . " AND mostContent >= 30 AND degree IN (".$degreeHelper.")");
            $staff = DB::select("select id from users where faculty_id =" . $faculty->id . " AND mostContent >= 50 AND degree IN (".$degreeStaff.")");
            $tr .="<tr>
                <td>".str_replace('كلية', ' ' ,$faculty->name)."</td>
                <td>".count($helper)."</td>
                <td>".count($staff)."</td>
                <td>". intval(count($staff) + count($helper)) ." \ ".count($faculty->users)."</td>
            </tr>";
        }

        $mostContents = User::where('isActive', '=', '1')
                ->whereNotNull('fullName')
                ->where('photo_id', '<>', 'NULL')
                ->where(function ($q){
                    $q->where('role_id', '=', '1')
                    ->orWhere('role_id', '=', '3');
                })
                ->orderBy('mostContent', 'desc')->first();
            
            $maxValue = $mostContents->mostContent;

        return view('frontend.search', compact(['users', 'faculties', $faculties, 'count', 'tr', 'maxValue', 'degrees']));

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
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

    public function findEmail(Request $request)
    {
        if($request->ajax())
        {
            if($request->userID == ""){
                return "";
            }

            if( !is_numeric($request->userID)) {
                return '<p class="getEmail">'.$request->insertID.'</p>';
            }
            
            $user = User::where('userID', '=', $request->userID)->first();
           
            
            if($user){
                
                $userdata = array(
                    'email'     => $user->email,
                    'password'  => $request->userID
                );
            
                // attempt to do the login
                if (Auth::attempt($userdata)) {
                    //Redirect::to('login');
                    return "ok";
                } 
                return  '<p class="getEmail getEmailTrue">'.$request->userEmail.': <span id="spanEmail">'.$user->email.'</span></p>';
            }else{
                return  '<p class="getEmail">'.$request->userEmail.': <span id="spanEmail">'.$request->nouserEmail.'</span></p>';
            }

        }

    }

    public function search(Request $request)
    {
        if($request->ajax())
        {

            

            $mostContents = User::where('isActive', '=', '1')
                ->whereNotNull('fullName')
                ->where('photo_id', '<>', 'NULL')
                ->where(function ($q){
                    $q->where('role_id', '=', '1')
                    ->orWhere('role_id', '=', '3');
                })
                ->orderBy('mostContent', 'desc')->first();
            
            $maxValue = $mostContents->mostContent;

            $output = "";
            $countParg = "";
                
            $request->precentVal = ($request->precentVal != "") ? $request->precentVal : 0; 
            $sName = '%'.$request->name.'%';
            if($request->fac != "all"){
                $getFacName = Faculty::find($request->fac)->name;
            }

            
            
            //$output .= $request->fac ." -- " . $request->name;\
            if($request->fac == "all" && $request->name == ""){
                $users = User::where('mostContent', $request->operator,  $request->precentVal)
                                ->where('isActive', '=', '1')
                                ->whereNotNull('fullName')
                                ->where(function($q) {
                                    $q->where('role_id', '=', '1')
                                    ->orWhere('role_id', '=', '3');
                                })
                                ->orderBy($request->orderBy, $request->orderType);
                                
                if($request->degree){
                    $users = $users->whereIn('degree', $request->degree)->paginate(100);
                }else{
                    $users = $users->paginate(100);
                }

                $count =  $users->total();
                $countParg .= "<p class='alert alert-dismissable alert-success'>" .$request->stafffounds . " <span class='boldstyle'> " . $count . " </span> " .$request->member. " " . $request->inside . " " ." <span class='boldstyle'> " . $request->facAll . "</span>" . $request->applyedSearch . "</p>" ;
                
            }elseif($request->fac == "all" && $request->name != ""){
                $users = User::where('mostContent', $request->operator,  $request->precentVal)
                            ->where('isActive', '=', '1')
                            ->whereNotNull('fullName')
                            ->where(function($q) {
                                $q->where('role_id', '=', '1')
                                ->orWhere('role_id', '=', '3');
                            })
                            ->where(function($q) use($sName) {
                                $q->where('fullName', 'LIKE', $sName)
                                ->orWhere('name', 'LIKE', $sName);
                            })
                            ->orderBy($request->orderBy, $request->orderType);
                
                if($request->degree){
                    $users = $users->whereIn('degree', $request->degree)->paginate(100);
                }else{
                    $users = $users->paginate(100);
                }

                $count =  $users->total();
                $countParg .= "<p class='alert alert-dismissable alert-success'>" .$request->stafffounds . " <span class='boldstyle'> " . $count . " </span> " .$request->member. " " . $request->hasName . " <span class='boldstyle'>" . $request->name . "</span> " . $request->inside . " " ." <span class='boldstyle'> " . $request->facAll . "</span>" . $request->applyedSearch . "</p>" ;
                
            }elseif($request->fac != "All" && $request->name == "" ){
                $users = User::where('faculty_id', '=', $request->fac)
                            ->where('mostContent', $request->operator,  $request->precentVal)
                            ->where('isActive', '=', '1')
                            ->whereNotNull('fullName')
                            ->where(function($q) {
                                $q->where('role_id', '=', '1')
                                ->orWhere('role_id', '=', '3');
                            })
                            ->orderBy($request->orderBy, $request->orderType);
                
                if($request->degree){
                    $users = $users->whereIn('degree', $request->degree)->paginate(1200);
                }else{
                    $users = $users->paginate(1200);
                }

                $count =  $users->total();
                $countParg .= "<p class='alert alert-dismissable alert-success'>" .$request->stafffounds . " <span class='boldstyle'> " . $count . " </span> " .$request->member. " " . $request->inside . " " ." <span class='boldstyle'> " . $getFacName . "</span>" . $request->applyedSearch . "</p>" ;

            }else{
                $users = User::where('faculty_id', '=', $request->fac)
                            ->where('mostContent', $request->operator,  $request->precentVal)
                            ->where('isActive', '=', '1')
                            ->whereNotNull('fullName')
                            ->where(function($q) {
                                $q->where('role_id', '=', '1')
                                ->orWhere('role_id', '=', '3');
                            })
                            ->where(function($q) use($sName) {
                                $q->where('fullName', 'LIKE', $sName)
                                ->orWhere('name', 'LIKE', $sName);
                            })
                            ->orderBy($request->orderBy, $request->orderType);
                
                if($request->degree){
                    $users = $users->whereIn('degree', $request->degree)->paginate(100);
                }else{
                    $users = $users->paginate(100);
                }
                
                $count =  $users->total();
                $countParg .= "<p class='alert alert-dismissable alert-success'>" .$request->stafffounds . " <span class='boldstyle'> " . $count . " </span> " .$request->member. " " . $request->hasName . " <span class='boldstyle'>" . $request->name . "</span> " . $request->inside . " " ." <span class='boldstyle'> " . $getFacName . "</span>" . $request->applyedSearch . "</p>" ;

            }

            

            
            if($users)
            {
                $output .= $countParg;
                foreach ($users as $user) 
                {
                    $width = $user->mostContent / $maxValue * 100;
                    
                    if(isset($user->faculty_id) && isset($user->faculty))
                    {
                        if($user->lang == "ar") 
                            $fac = $user->faculty->name;
                        elseif($user->lang == "en")
                            $fac = $user->faculty->nameEn;
                        else 
                            $fac = "";    
                       
                    } else 
                            $fac = "";   
                    
                    if(isset($user->photo))
                        $img = '<img class="circle" src="'.url($user->uploads() . $user->photo->name).'" alt="'.$user->name.'">';
                    else
                        $img = '<img class="circle" src="'.url('images/fac_photo.png').'" alt="'.$user->name.'">';
                     
                    $output .= '
                    <div class="col-xs-12  col-md-4 blockItemMain">
                        <div class="blockItem">
                            <a href="'.url('/'.$user->name).'">
                                '.$img.'
                            </a>
                            <div class="blockItemData">
                                <h3><a href="'.url('/'.$user->name).'">'.$user->fullName.'</a></h3>
                                <p>' . $user->getDegree($user->lang) . ' - ' .$fac.'</p>
                            </div><!-- .blockItemData -->
                            <span class="mostViewCount"> 
                                <span class="icoHover">'.$user->subjects()->count().'<i data-toggle="tooltip" data-placement="bottom" title="'.trans('main.manageSubjects').'" class="fas fa-book"></i></span> 
                                <span class="icoHover">'.$user->supplements()->count().'<i data-toggle="tooltip" data-placement="bottom" title="'.trans('main.manageSupplements').'" class="fas fa-paperclip"></i></span> 
                                <span class="icoHover">'.$user->tasks()->count().'<i data-toggle="tooltip" data-placement="bottom" title="'.trans('main.manageTasks').'" class="fas fa-tasks"></i></span> 
                                <span class="icoHover">'.$user->posts()->count().'<i data-toggle="tooltip" data-placement="bottom" title="'.trans('main.managePosts').'" class="fas fa-clipboard"></i></span> 
                                <span class="icoHover">'.$user->offices()->count().'<i data-toggle="tooltip" data-placement="bottom" title="'.trans('main.manageOffices').'" class="fas fa-clock"></i></span> 
                                <span class="icoHover">'.$user->advs()->count().'<i data-toggle="tooltip" data-placement="bottom" title="'.trans('main.manageAdvs').'" class="fas fa-newspaper"></i></span> 
                                <span class="icoHover">'.$user->mostView.'<i data-toggle="tooltip" data-placement="bottom" title="'.trans('main.VisitNum').'" class="fas fa-eye"></i></span> 
                                <span class="icoHover">'.$user->countTime.'<i data-toggle="tooltip" data-placement="bottom" title="'.trans('main.countTime').'" class="fas fa-plug"></i></span> 

                            </span>
                            <div class="userProgress">
                                <div <i data-toggle="tooltip" data-placement="top" title="'.trans('main.contentComplete').'" class="progress">
                                    <div <i data-toggle="tooltip" data-placement="top" title="'.trans('main.contentComplete').'" class="progress-bar" role="progressbar" aria-valuenow="'.$user->mostContent.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$width.'%;">
                                    '.$user->mostContent.'
                                    </div>
                                </div>
                            </div>
                        </div><!-- .blockItem -->
                    </div>
                    ';
                }
            }
            if($output == "") $output .= $request->orderType . " - " . $request->orderBy . '<h3 class="noResults">'.$request->noserchFound.'</h3>';
            return $output;
        }
    }
}
