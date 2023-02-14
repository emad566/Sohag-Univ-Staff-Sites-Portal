{{App::setLocale(Auth()->user()->lang)}}
@extends('layouts.backend')


@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ trans('main.user-AddNewUser') }}</div>
    <div class="panel-body panelBodyForm">
        @include("partials.errors")
        @include("partials.success")
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action='{{ route('users.store', [])}}'>
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='POST'>
    
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">{{ trans('main.user-name') }} *</label>
                <input value="{{ old('name') }}" type="text" name="name" required min="6" max="70" class="form-control name" id="name" placeholder="{{ trans('main.user-name') }}">
                <p class="inputNotes">
                    {{ trans('main.webUserNameHint') }}: 
                    <span class="mainUrl">{{ URL::to('/') }}/<span id="urlUserName"></span></span>
                </p>
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
    
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email">{{ trans('main.user-email') }} *</label>
                <input value="{{ old('email') }}" type="email" required  name="email" class="form-control email" id="email" placeholder="{{ trans('main.user-email') }}">

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('userID') ? ' has-error' : '' }}">
                <label for="userID">{{ trans('main.user-userID') }} *</label>
                <input value="{{ old('userID') }}" type="number" required  name="userID" class="form-control userID" id="userID" placeholder="{{ trans('main.user-userID') }}">
                @if ($errors->has('userID'))
                    <span class="help-block">
                        <strong>{{ $errors->first('userID') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('fullName') ? ' has-error' : '' }}">
                <label for="fullName">{{ trans('main.user-fullName') }} *</label>
                <input value="{{ old('fullName') }}" type="text" name="fullName" required min="6" max="70" class="form-control fullName" id="fullName" placeholder="{{ trans('main.user-fullName') }}">
                @if ($errors->has('fullName'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fullName') }}</strong>
                    </span>
                @endif
            </div>

            
            <div class="sexRadio form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
                <label>{{ trans('main.user-sex') }} *</label> 
                <label  class="radio-inline">
                    <input type="radio" 
                    @if ( old('sex') ) 
                        {{ old('sex') == 2  ? "checked" : "" }}
                    @endif
                    name="sex" id="sex1" value="2"> {{ trans('main.user-Male') }}
                </label>
                <label class="radio-inline">
                    <input type="radio" 
                    @if ( old('sex') ) 
                        {{ old('sex') == 1 ? "checked" : "" }}
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
                            @endif 
                        value="{{ $faculty->id }}">
                        @if(Auth::user()->lang == "en")
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
                <label for="lang">{{ trans('main.webLang') }} *</label>
                <select required class="form-control" id="lang" name="lang" >
                    <option value="">- {{ trans('main.webLang') }} -</option>
                    <option 
                            @if(old('lang') == "ar") 
                                selected
                            @endif 
                    value="ar">ar</option>  

                    <option 
                            @if(old('lang') == "en") 
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
    
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="pasword">{{ trans('main.user-password') }} *</label>
                <input type="password" name="password" required class="form-control password" id="pasword" placeholder="{{ trans('main.user-password') }}">
                
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
    
            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password_confirmation">{{ trans('main.user-passConfirm') }} *</label>
                <input type="password" required name="password_confirmation" class="form-control password_confirmation" id="password_confirmation" placeholder="{{ trans('main.user-passConfirm') }}">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
    
            <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}">
                <select required class="form-control" id="role" name="role_id" >
                    <option value="">- {{ trans('main.user-role_id') }} * -</option>
                    @foreach($roles as $role)
                        @if(app()->getLocale() == "en"))
                            <option {{ old('role_id') == $role->id ? "selected" : "" }} value="{{ $role->id }}">{{ Auth::user()->ruleArr[$role->name] }}</option>
                        @else
                            <option {{ old('role_id') == $role->id ? "selected" : "" }} value="{{ $role->id }}">{{ $role->name }}</option>
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
                        <input {{ old('isActive') == 1 ? "checked" : "" }} type="checkbox" name="isActive" class="isActive" id="isActive" value="1">{{ trans('main.user-activate') }}
                    </label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">{{ trans('main.gSave') }}</button>
            
        </form><!--#CreateUser .CreateUser -->   
    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->

    
@endsection

@section('jqScript')
$("#name").keyup(function() {
    $('#urlUserName').text($("#name").val());
});
@endsection