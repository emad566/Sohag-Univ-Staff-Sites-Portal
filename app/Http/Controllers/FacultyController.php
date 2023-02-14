<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UserCreateRequest;
use App\Faculty;
use Illuminate\Support\Facades\Auth;
class FacultyController extends Controller
{
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculties = Faculty::all();
        return view('backend/faculties/index', compact(['faculties']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend/faculties/create');
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
        
        $messages =[
            'name.required' => trans('main.required'),
            'name.unique' => trans('main.nameUnique'),
            'name.min' => trans('main.nameMinMsg'),
            'name.max' => trans('main.nameMinMsg'),
            
            'nameEn.required' => trans('main.required'),
            'nameEn.unique' => trans('main.nameUnique'),
            'nameEn.min' => trans('main.nameMinMsg'),
            'nameEn.max' => trans('main.nameMinMsg'),
        ];
        $this->validate($request, [
            'name' => 'required|unique:faculties,name,',            
            'nameEn' => 'required|unique:faculties,nameEn,',            
        ], $messages);

        $inputs = $request->all();

        $faculty = Faculty::create($inputs);

        
        if ($faculty)
            return redirect()->route('faculties.index')->with('success',   trans('main.saveMsg')  );
        else
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
        $faculty = Faculty::findOrFail($id);
        return view('backend/faculties/edit', compact(['faculty']));
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
        
        $messages =[
            'name.required' => trans('main.required'),
            'name.unique' => trans('main.nameUnique'),
            'name.min' => trans('main.nameMinMsg'),
            'name.max' => trans('main.nameMinMsg'),
            
            'nameEn.required' => trans('main.required'),
            'nameEn.unique' => trans('main.nameUnique'),
            'nameEn.min' => trans('main.nameMinMsg'),
            'nameEn.max' => trans('main.nameMinMsg'),
        ];
        $this->validate($request, [
            'name' => 'required|unique:faculties,name,'.$id,            
            'nameEn' => 'required|unique:faculties,nameEn,'.$id,          
        ], $messages);

        $faculty = Faculty::findOrFail($id);
        $inputs = $request->all();

        $oldName = $faculty->name;
        $faculty = $faculty->update($inputs);


        if ($faculty)
            return redirect()->route('faculties.index')->with('success',   trans('main.saveMsg')  );
        else
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
        $faculty = Faculty::findOrFail($id);

        if ($faculty->delete())
        {
            return redirect()->route('faculties.index')->with('success', trans('main.deleteMsg'));
        }
        return back()->withInput()->with('errors', trans('main.deleteMsgError'));
    }
}
