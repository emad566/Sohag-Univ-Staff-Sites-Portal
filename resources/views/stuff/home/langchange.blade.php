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
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action='{{ url('suff/langUpdate') }}'enctype="multipart/form-data">
            @include("partials.errors")
            @include("partials.success")
            
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='PUT'>
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

            
            <button type="submit" class="btn btn-primary">{{ trans('main.user-InfoUpdate') }}</button>
            
        </form><!--#CreateUser .CreateUser -->   
    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->
@endsection
