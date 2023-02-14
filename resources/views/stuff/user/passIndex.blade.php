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
    <div class="panel-heading">{{ trans('main.user-passChange') }}</div>
    <div class="panel-body panelBodyForm">
    
        <form id='passIndex' class='passIndex form-horizontal panelForm' method='POST' action='{{ url('stuff/user/passUpdate') }}'>
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='PUT'>
    
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="pasword">{{ trans('main.user-password') }}</label>
                <input type="password" name="password" required class="form-control password" id="pasword" placeholder="{{ trans('main.user-password') }}">
                
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
    
            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password_confirmation">{{ trans('main.user-passConfirm') }}</label>
                <input type="password" required name="password_confirmation" class="form-control password_confirmation" id="password_confirmation" placeholder="{{ trans('main.user-passConfirm') }}">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">{{ trans('main.user-InfoUpdate') }}</button>
        </form>
    </div>
</div><!-- .panel panel-default  -->

@endsection