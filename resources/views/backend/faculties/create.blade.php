{{App::setLocale(Auth()->user()->lang)}}
@extends('layouts.backend')


@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ trans('main.addNewFaculty') }}</div>
    <div class="panel-body panelBodyForm">
        <form id='CreateFaculty' class='CreateFaculty form-horizontal panelForm' method='POST' action='{{ route('faculties.store', [])}}'>
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='POST'>
    
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">{{ trans('main.user-name') }}</label>
                <input value="{{ old('name') }}" type="text" name="name" required min="6" max="70" class="form-control name" id="name" placeholder="{{ trans('main.user-name') }}">
                
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('nameEn') ? ' has-error' : '' }}">
                <label for="nameEn">{{ trans('main.user-NameEn') }}</label>
                <input value="{{ old('nameEn') }}" type="text" name="nameEn" required min="6" max="70" class="form-control nameEn" id="nameEn" placeholder="{{ trans('main.user-NameEn') }}">
                
                @if ($errors->has('nameEn'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nameEn') }}</strong>
                    </span>
                @endif
            </div>
            
            <button type="submit" class="btn btn-primary">{{ trans('main.gSave') }}</button>
            
        </form><!--#CreateFaculty .CreateFaculty -->   
    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->

    
@endsection