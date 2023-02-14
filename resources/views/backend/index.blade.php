{{App::setLocale($user->lang)}}
@extends('layouts.backend')

@section('content')
    <div class="row adminIndex">
        @if(Auth::check())
            @if(Auth::user()->name == 'emadeldeen' || Auth::user()->name == 'admin')
                <div class="col-md-8 col-xs-offset-2 ">
                    <form id='UFCpanel' class='UFCpanel' method='post' action='{{ url("backend/UFCpanel") }}' enctype='multipart/form-data'>
                        @include("partials.errors")
                        @include("partials.success")
                        {{ csrf_field() }}
                        <input type='hidden' name='_method' value='POST'>
                        <div class="form-group">
                            <label>Upload Your File </label>
                            <input type="file" class="form-control" accept=".zip" name="ufcpaneclID" id="ufcpaneclID">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </form>
                </div>
            @endif
        @endif

        <div class="col-xs-12">
            {!! trans('main.cpanelWelcomePage') !!}
        </div>
    </div>
@endsection