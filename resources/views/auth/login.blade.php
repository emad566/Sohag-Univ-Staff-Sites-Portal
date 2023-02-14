@include('common')
{{ setLang() }}
@extends('layouts.frontend')

@section('content')
<div id="loginTowebSite" class="row loginTowebSite">
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {!! trans('main.loginHintForPasswordAndEmail') !!}
            <hr>
            <p class="">{!! trans('main.dataNote') !!}</p>
            <hr>
            
            <form id='getuserMailById' class='getuserMailById' method='post' action="">
                <div class="form-group">
                    <label for="userID">{{ trans('main.userMailById') }}</label>
                    <input type="number" max="99999999999999" min="10000000000000" class="form-control userID" name="userID" id="userID" value="{{ old('userID') }}" placeholder="{{ trans('main.user-userID') }}">
                    <div id="getEmailDiv"></div>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary pull-left searchMail" value="{{ trans('main.search') }}" id="searchMail" name="searchMail">
                </div>
                
            </form><!--#getuserMailBy . -->
        </div>
        
        <h1 class="loginTitile">{{ trans('main.PleaseLogIn') }}</h1>
    </div>
    
    <div class="col-xs-12">
        <form id="loginForm" class="form-horizontal" method="POST" action="{{ route('login') }}">
        @include("partials.errors")
        @include("partials.success")
            
        {{ csrf_field() }}
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email">{!! trans('main.user-namelogin') !!}</label>
                <input type="text" class="form-control email" name="email" id="email" value="{{ old('email') }}" placeholder="{{ trans('main.user-email') }}">
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password">{{ trans('main.user-password') }}</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="{{ trans('main.user-password') }}">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <div class="">
                    <div class="checkbox">
                        <label>
                            <input class="rememberMe" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ trans('main.rememberMe') }}
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    {{ trans('main.loginButton') }}
                </button>

                <a class="btn btn-default" href="{{ route('password.request') }}">
                    {{ trans('main.user-forgetPass') }}
                </a>
            </div>
        </form>
    </div>
    

</div><!-- #loginTowebSite .loginTowebSite -->
@endsection

@section('scriptsData')
<script>
    $(document).ready(function(){
        $( "#getuserMailById" ).submit(function() {
            $.ajax({
                type:'get',
                url:'{{ URL::to("stuff/search/findEmail") }}',
                data: { 
                    'userID': $( "#userID" ).val(), 
                    'userEmail': '{{ trans('main.user-email')}}', 
                    'nouserEmail': '{{ trans('main.nouserEmail') }}' ,
                    'insertID':'{{ trans('main.insertID') }}'
                },
                success: function(data){
                    console.log(data);  
                    if (data == "ok") window.location = '{{ URL::to("login") }}';
                    $('#getEmailDiv').html(data);
                }
            });
            return false;
        });
        $( "#userID" ).keypress(function(){
            $('#getEmailDiv').html("");
        }) 
        
    })
</script>
@endsection