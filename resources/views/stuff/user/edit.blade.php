{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }}</title>

    <!-- facebook meta -->
    @if(isset($user->photo))
        <meta property="og:image" content="{{ url($user->uploads()) }}/{{ $user->photo->name }}" />

        <link rel="icon" href="{{ url($user->uploads()) }}/{{ $user->photo->name }}" sizes="32x32" />
        <link rel="icon" href="{{ url($user->uploads()) }}/{{ $user->photo->name }}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{ url($user->uploads()) }}/{{ $user->photo->name }}" />
        <meta name="msapplication-TileImage" content="{{ url($user->uploads()) }}/{{ $user->photo->name }}" />
    @else
        <meta property="og:image" content="{{ url('images/fac_photo.png') }}" />
    @endif

    <meta property="og:title" content="{{ $user->name }}"/> 
    <?php 
        $webIntro = new \voku\Html2Text\Html2Text($user->webIntro); 
        $webIntro = strip_tags($webIntro->getText())
    ?>
    <meta property="og:description" content="{{ substr($webIntro, 0, 300) . '...' }}" />

@endsection

@section('stuffContent')
<div class="panel panel-default">
    <div class="panel-heading"> <a class="btn btn-primary pull-left" href="{{ url('stuff/user/passIndex') }}">{{ trans('main.user-passChange') }}</a> {{ trans('main.user-EditData') }}: {{ $user->name }}</div>
    <div class="panel-body panelBodyForm">
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action='{{ url('stuff/user/'.$user->id) }}'enctype="multipart/form-data">
            @include("partials.errors")
            @include("partials.success")
            
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='PUT'>
            <!--======================================
            priew image before uploaded
            =====================================-->
            <script type="text/javascript">
                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
        
                        reader.onload = function (e) {
                            $('#userPhoto').attr('src', e.target.result);
                        }
        
                        reader.readAsDataURL(input.files[0]);
                    }
                }
            </script>

            <div class="form-group{{ $errors->has('photo_id') ? ' has-error' : '' }}">
                <label for="photo_id">{{ trans('main.user-photo_id') }} 
                    @if(!$user->photo_id == 0)
                        <img id="userPhoto" class="userPhoto circle" src="{{ url($user->uploads()) }}/{{ $user->photo->name }}" alt="{{ $user->name }}" alt="{{ trans('main.user-photo_id') }}">
                    @else
                        <img id="userPhoto" class="userPhoto circle" src="#" alt="{{ trans('main.user-photo_id') }}">
                    @endif
                </label>
                <input type="file" name="photo_id" id="photo_id" onchange="readURL(this);" class="form-control photo_id">
                @if ($errors->has('photo_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('photo_id') }}</strong>
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
                    value="ar">{{ trans('main.lang-ar') }}</option>  

                    <option 
                            @if(old('lang') == "en") 
                                selected
                            @elseif ($user->lang =="en") 
                                selected
                            @endif 
                    value="en">{{ trans('main.lang-en') }}</option>                    
                                         
                </select>
                
                @if ($errors->has('lang'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lang') }}</strong>
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

            <div class="form-group{{ $errors->has('brithDate') ? ' has-error' : '' }}">
                <label for="brithDate">{{ trans('main.user-brithDate') }}</label>
                <input type="date" value="@if ( old('brithDate') ){{ old('brithDate') }}@else{{ $user->brithDate }}@endif"  name="brithDate" class="form-control brithDate datetime" id="brithDate">
                
                @if ($errors->has('brithDate'))
                    <span class="help-block">
                        <strong>{{ $errors->first('brithDate') }}</strong>
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

            <div class="form-group{{ $errors->has('degree') ? ' has-error' : '' }}">
                    
                <label for="degree">{{ trans('main.user-degree') }} *</label>
                <select required class="form-control" id="degree" name="degree" >
                    {!! $user->selectDegree(App::getLocale(), old('degree')) !!}  
                </select>
                @if ($errors->has('degree'))
                    <span class="help-block">
                        <strong>{{ $errors->first('degree') }}</strong>
                    </span>
                @endif    
            </div>

            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title">{{ trans('main.address') }} *</label>
                <input value="@if ( old('title') ){{ old('title') }}@else{{ $user->title }}@endif" type="text" name="title" required class="form-control title" id="title" placeholder="{{ trans('main.address') }}">
                
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('currentPosition') ? ' has-error' : '' }}">
                <label for="currentPosition">{{ trans('main.user-currentPosition') }}</label>
                <input value="@if ( old('currentPosition') ){{ old('currentPosition') }}@else{{ $user->currentPosition }}@endif" type="text" name="currentPosition"  class="form-control currentPosition" id="currentPosition" placeholder="{{ trans('main.user-currentPosition') }}">
                
                @if ($errors->has('currentPosition'))
                    <span class="help-block">
                        <strong>{{ $errors->first('currentPosition') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="panel panel-info">
                <div class="panel-heading">{{ trans('main.academy') }}</div>
                <div class="panel-body panelBodyForm">
                    <div class="form-group{{ $errors->has('general') ? ' has-error' : '' }}">
                        <label for="general">{{ trans('main.general') }}</label>
                        <input value="@if ( old('general') ){{ old('general') }}@else{{ $user->general }}@endif" type="text" name="general"  class="form-control general" id="general" placeholder="{{ trans('main.general') }}">
                        
                        @if ($errors->has('general'))
                            <span class="help-block">
                                <strong>{{ $errors->first('general') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('special') ? ' has-error' : '' }}">
                        <label for="special">{{ trans('main.special') }}</label>
                        <input value="@if ( old('special') ){{ old('special') }}@else{{ $user->special }}@endif" type="text" name="special"  class="form-control special" id="special" placeholder="{{ trans('main.special') }}">
                        
                        @if ($errors->has('special'))
                            <span class="help-block">
                                <strong>{{ $errors->first('special') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('masterar') ? ' has-error' : '' }}">
                        <label for="masterar">{{ trans('main.masterar') }}</label>
                        <input value="@if ( old('masterar') ){{ old('masterar') }}@else{{ $user->masterar }}@endif" type="text" name="masterar"  class="form-control masterar" id="masterar" placeholder="{{ trans('main.masterar') }}">
                        
                        @if ($errors->has('masterar'))
                            <span class="help-block">
                                <strong>{{ $errors->first('masterar') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('masteren') ? ' has-error' : '' }}">
                        <label for="masteren">{{ trans('main.masteren') }}</label>
                        <input value="@if ( old('masteren') ){{ old('masteren') }}@else{{ $user->masteren }}@endif" type="text" name="masteren"  class="form-control masteren" id="masteren" placeholder="{{ trans('main.masteren') }}">
                        
                        @if ($errors->has('masteren'))
                            <span class="help-block">
                                <strong>{{ $errors->first('masteren') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('phdar') ? ' has-error' : '' }}">
                        <label for="phdar">{{ trans('main.phdar') }}</label>
                        <input value="@if ( old('phdar') ){{ old('phdar') }}@else{{ $user->phdar }}@endif" type="text" name="phdar"  class="form-control phdar" id="phdar" placeholder="{{ trans('main.phdar') }}">
                        
                        @if ($errors->has('phdar'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phdar') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('phden') ? ' has-error' : '' }}">
                        <label for="phden">{{ trans('main.phden') }}</label>
                        <input value="@if ( old('phden') ){{ old('phden') }}@else{{ $user->phden }}@endif" type="text" name="phden"  class="form-control phden" id="phden" placeholder="{{ trans('main.phden') }}">
                        
                        @if ($errors->has('phden'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phden') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('positions') ? ' has-error' : '' }}">
                        <label for="positions">{{ trans('main.positions') }}</label>
                        <input value="@if ( old('positions') ){{ old('positions') }}@else{{ $user->positions }}@endif" type="text" name="positions"  class="form-control positions" id="positions" placeholder="{{ trans('main.positions') }}">
                        
                        @if ($errors->has('positions'))
                            <span class="help-block">
                                <strong>{{ $errors->first('positions') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            
            
            <div class="form-group{{ $errors->has('webIntro') ? ' has-error' : '' }}">
                <label for="webIntro">{{ trans('main.user-webIntro') }} *</label>
                <textarea required name="webIntro" id="webIntro" class="form-control webIntro editor">@if ( old('webIntro') ){{ old('webIntro') }}@else{{ $user->webIntro }}@endif</textarea>
                @if ($errors->has('webIntro'))
                    <span class="help-block">
                        <strong>{{ $errors->first('webIntro') }}</strong>
                    </span>
                @endif
            </div>
                        
            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                <label for="phone">{{ trans('main.user-phone') }}</label>
                <input value="@if ( old('phone') ){{ old('phone') }}@else{{ $user->phone }}@endif" type="number" name="phone" class="form-control name" id="phone" placeholder="{{ trans('main.user-phone') }}">
                
                @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('fax') ? ' has-error' : '' }}">
                <label for="fax">{{ trans('main.user-fax') }}</label>
                <input value="@if ( old('fax') ){{ old('fax') }}@else{{ $user->fax }}@endif" type="number" name="fax" class="form-control name" id="fax" placeholder="{{ trans('main.user-fax') }}">
                
                @if ($errors->has('fax'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fax') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                <label for="mobile">{{ trans('main.user-mobile') }}</label>
                <input value="@if ( old('mobile') ){{ old('mobile') }}@else{{ $user->mobile }}@endif" type="number" name="mobile" class="form-control name" id="mobile" placeholder="{{ trans('main.user-mobile') }}">
                
                @if ($errors->has('mobile'))
                    <span class="help-block">
                        <strong>{{ $errors->first('mobile') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('fb') ? ' has-error' : '' }}">
                <label for="fb">{{ trans('main.user-fb') }}</label>
                <input value="@if ( old('fb') ){{ old('fb') }}@else{{ $user->fb }}@endif" type="url" name="fb" class="form-control name" id="fb" placeholder="{{ trans('main.user-fb') }}">
                
                @if ($errors->has('fb'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fb') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('twitter') ? ' has-error' : '' }}">
                <label for="twitter">{{ trans('main.user-twitter') }}</label>
                <input value="@if ( old('twitter') ){{ old('twitter') }}@else{{ $user->twitter }}@endif" type="url" name="twitter" class="form-control name" id="twitter" placeholder="{{ trans('main.user-twitter') }}">
                
                @if ($errors->has('twitter'))
                    <span class="help-block">
                        <strong>{{ $errors->first('twitter') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('yt') ? ' has-error' : '' }}">
                <label for="yt">{{ trans('main.user-yt') }}</label>
                <input value="@if ( old('yt') ){{ old('yt') }}@else{{ $user->yt }}@endif" type="url" name="yt" class="form-control name" id="yt" placeholder="{{ trans('main.user-yt') }}">
                
                @if ($errors->has('yt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('yt') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('linkedIn') ? ' has-error' : '' }}">
                <label for="linkedIn">{{ trans('main.user-linkedIn') }}</label>
                <input value="@if ( old('linkedIn') ){{ old('linkedIn') }}@else{{ $user->linkedIn }}@endif" type="url" name="linkedIn" class="form-control name" id="linkedIn" placeholder="{{ trans('main.user-linkedIn') }}">
                
                @if ($errors->has('linkedIn'))
                    <span class="help-block">
                        <strong>{{ $errors->first('linkedIn') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('schooler') ? ' has-error' : ''}}">
                <label for="googlePlus">{{ trans('main.user-googlePlus') }}</label>
                <input value="@if ( old('googlePlus') ){{ old('googlePlus') }}@else{{ $user->googlePlus }}@endif" type="url" name="googlePlus" class="form-control name" id="googlePlus" placeholder="{{ trans('main.user-googlePlus') }}">
                
                @if ($errors->has('googlePlus'))
                    <span class="help-block">
                        <strong>{{ $errors->first('googlePlus') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('schooler') ? ' has-error' : ''}}">
                <label for="schooler">{{ trans('main.user-schooler') }}</label>
                <input value="@if ( old('schooler') ){{ old('schooler') }}@else{{ $user->schooler }}@endif" type="url" name="schooler" class="form-control name" id="schooler" placeholder="{{ trans('main.user-schooler') }}">
                
                @if ($errors->has('schooler'))
                    <span class="help-block">
                        <strong>{{ $errors->first('schooler') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('EKP') ? ' has-error' : ''}}">
                <label for="EKP">EKB بنك المعرفة المصري</label>
                <input value="@if ( old('EKP') ){{ old('EKP') }}@else{{ $user->EKP }}@endif" type="url" name="EKP" class="form-control name" id="EKP" placeholder="EKB بنك المعرفة المصري ">
                
                @if ($errors->has('EKP'))
                    <span class="help-block">
                        <strong>{{ $errors->first('EKP') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('researchGate') ? ' has-error' : ''}}">
                <label for="researchGate">Research Gate</label>
                <input value="@if ( old('researchGate') ){{ old('researchGate') }}@else{{ $user->researchGate }}@endif" type="url" name="researchGate" class="form-control name" id="researchGate" placeholder="Research Gate URL">
                
                @if ($errors->has('researchGate'))
                    <span class="help-block">
                        <strong>{{ $errors->first('researchGate') }}</strong>
                    </span>
                @endif
            </div>
            
            <button id="submitFormCreate" type="submit" form="CreateUser" class="btn btn-primary">{{ trans('main.user-InfoUpdate') }}</button>
            
        </form><!--#CreateUser .CreateUser -->   
    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->
@endsection

@section('scriptsData')
<!-- web datepicker CSS -->
	  {{-- <link rel="stylesheet" href="{{ asset('date/jquery.datetimepicker.min.css')  }}">
      <script src="{{ asset('date/jquery.datetimepicker.full.js') }}" ></script>   --}}
@endsection

@section('jqScript')
    {{-- $('#brithDate').datetimepicker({
        format:'Y-m-d',
    }); --}}
@endsection