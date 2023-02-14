{{App::setLocale(Auth()->user()->lang)}}
@extends('layouts.backend')


@section('content')
<div class="panel panel-default">
    <div class="panel-heading"> <a class="btn btn-primary pull-left" href="{{ url('backend/users/'.$user->id.'/passIndex') }}">{{ trans('main.user-passChange') }}</a>{{ trans('main.user-EditData') }}: {{ $user->name }}</div>
    <div class="panel-body panelBodyForm">
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action='{{ route('users.update', [$user->id] )}}'>
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='PUT'>
    
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">{{ trans('main.user-name') }}</label>
                <input value="@if ( old('name') ){{ old('name') }}@else{{ $user->name }}@endif" type="text" name="name" required min="6" max="70" class="form-control name" id="name" placeholder="{{ trans('main.user-name') }}">
                <p class="inputNotes">
                    {{ trans('main.webUserNameHint') }}: 
                    <span class="mainUrl">{{ URL::to('/') }}/<span id="urlUserName">{{ $user->name }}</span></span>
                </p>
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
    
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email">{{ trans('main.user-email') }}</label>
                <input value="@if ( old('email') ){{ old('email') }}@else{{ $user->email }}@endif" type="email" required  name="email" class="form-control email" id="email" placeholder="{{ trans('main.user-email') }}">

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('userID') ? ' has-error' : '' }}">
                <label for="userID">{{ trans('main.user-userID') }} *</label>
                <input value="@if(old('userID') ){{ old('userID') }}@else{{ $user->userID }}@endif" type="number" required  name="userID" class="form-control userID" id="userID" placeholder="{{ trans('main.user-userID') }}">
                @if ($errors->has('userID'))
                    <span class="help-block">
                        <strong>{{ $errors->first('userID') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('fullName') ? ' has-error' : '' }}">
                <label for="fullName">{{ trans('main.user-fullName') }} *</label>
                <input value="@if ( old('fullName') ){{ old('fullName') }}@else{{ $user->fullName }}@endif" type="text" name="fullName" required class="form-control name" id="fullName" placeholder="{{ trans('main.user-fullName') }}">
                
                @if ($errors->has('fullName'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fullName') }}</strong>
                    </span>
                @endif
            </div>

            <div class="sexRadio form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
                <label>{{ trans('main.user-sex') }} *</label> 
                <label class="radio-inline">
                    <input type="radio" 
                    @if ( old('sex') ) 
                        {{ old('sex') == 2  ? "checked" : "" }}

                    @else 
                        {{ $user->sex ==  2  ? "checked" : "" }} 
                    @endif
                    name="sex" id="sex1" value="2"> {{ trans('main.user-Male') }}
                </label>
                <label class="radio-inline">
                    <input type="radio" 
                    @if ( old('sex') ) 
                        {{ old('sex') == 1 ? "checked" : "" }}

                    @else 
                        {{ $user->sex ==  1 ? "checked" : "" }} 
                    @endif
                    name="sex" id="sex2" value="1"> {{ trans('main.user-female') }}
                </label>

                @if ($errors->has('sex'))
                    <span class="help-block">
                        <strong>{{ $errors->first('sex') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('faculty_id') ? ' has-error' : '' }}">
                <label for="faculty">{{ trans('main.user-faculty_id') }} *</label>
                <select required class="form-control" id="faculty" name="faculty_id" >
                    <option value="">- {{ trans('main.user-faculty_id') }} -</option>
                    @foreach($faculties as $faculty)
                        <?php $select = 1; ?>
                        <option
                             @if(old('faculty_id') == $faculty->id) 
                                selected    <?php $select = 0; ?>
                            @elseif ($user->faculty_id == $faculty->id && $select == 1) 
                                selected
                            @endif 
                        value="{{ $faculty->id }}">
                        @if(app()->getLocale() == "en")
                            @if($faculty->nameEn)
                                {{ $faculty->nameEn }}
                            @else
                               {{ $faculty->name }}
                            @endif
                        @else
                           {{ $faculty->name }}
                        @endif
                        </option>
                    @endforeach
                </select>
                
                @if ($errors->has('faculty_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('faculty_id') }}</strong>
                    </span>
                @endif    
            </div>

            <div class="form-group{{ $errors->has('lang') ? ' has-error' : '' }}">
                <label for="lang">{{ trans('main.webLang') }}</label>
                <select required class="form-control" id="lang" name="lang" >
                    <option value="">- {{ trans('main.webLang') }} -</option>
                    <option 
                            @if(old('lang') == "ar") 
                                selected
                            @elseif ($user->lang =="ar") 
                                selected
                            @endif 
                    value="ar">ar</option>  

                    <option 
                            @if(old('lang') == "en") 
                                selected
                            @elseif ($user->lang =="en") 
                                selected
                            @endif 
                    value="en">en</option>                    
                                         
                </select>
                
                @if ($errors->has('lang'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lang') }}</strong>
                    </span>
                @endif    
            </div>
    
            @if(Auth()->user()->id != $user->id)
            <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}">
                <select required class="form-control" id="role" name="role_id" >
                    <option value="">- {{ trans('main.user-role_id') }} -</option>
                    @foreach($roles as $role)
                        @if(app()->getLocale() == "en"))
                            <option {{ $user->role_id == $role->id ? "selected" : "" }} value="{{ $role->id }}">{{ Auth::user()->ruleArr[$role->name] }}</option>
                        @else
                            <option {{ $user->role_id == $role->id ? "selected" : "" }} value="{{ $role->id }}">{{ $role->name }}</option>
                        @endif
                    @endforeach
                </select>
                
                @if ($errors->has('role_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('role_id') }}</strong>
                    </span>
                @endif    
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label for="isActive">
                        <input {{ $user->isActive == 1 ? "checked" : "" }} type="checkbox" name="isActive" class="isActive" id="isActive" value="1">{{ trans('main.user-activate') }}
                    </label>
                </div>
            </div>
            @else

            <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}">
                <label> {{ trans('main.user-role_id') }}: 
                    @if(app()->getLocale() == "en"))
                        <td>{{ Auth::user()->ruleArr[$user->role->name] }}</td>
                    @else
                        <td>{{ $user->role->name }}</td>
                    @endif
                </label>
                
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input disabled {{ $user->isActive == 1 ? "checked" : "" }} type="checkbox"class="isActive" id="isActive" >{{ trans('main.user-activate') }}
                    </label>
                </div>
            </div>
            @endif
            
            <button type="submit" class="btn btn-primary">{{ trans('main.user-InfoUpdate') }}</button>
            
        </form><!--#CreateUser .CreateUser -->   
    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->

    
@endsection

@section('jqScript')
$("#name").keyup(function() {
    $('#urlUserName').text($("#name").val());
});
@endsection