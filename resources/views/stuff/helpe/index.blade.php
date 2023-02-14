<?php //$lang="ar"; ?>
@include('common')
{{ setLang() }}
@extends('layouts.frontend')

@section('content')

<div id="contactWrap" class="contactWrap">
    @include("partials.errors")
    @include("partials.success")
    <div class="row" id="publicQues">
        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 class="supportPageHead">{{ trans('main.helpe') }}</h1>
            <p class="supportP">{!! trans('main.supportP') !!}.</p>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2 id="" class="supportPageHead">{{ trans('main.publicQuesTitle') }}</h2>
<div class="panel-group" id="accordionQues" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="q1">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordionQues" href="#collapseQ1" aria-expanded="true" aria-controls="collapseQ1">
          {!! trans('main.q1') !!}
        </a>
      </h4>
    </div>
    <div id="collapseQ1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="q1">
      <div class="panel-body">
        <p class="qr">{{ trans('main.qR') }} {{ trans('main.q1r') }}</p>
        <p class="qa">{{ trans('main.qA') }} {!! trans('main.q1a') !!}</p>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="q2">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionQues" href="#collapseQ2" aria-expanded="false" aria-controls="collapseQ2">
            {{ trans('main.q2') }}
        </a>
      </h4>
    </div>
    <div id="collapseQ2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="q2">
      <div class="panel-body">
        <p class="qr">{{ trans('main.qR') }} {{ trans('main.q2r') }}</p>
        <p class="qa">{{ trans('main.qA') }} {{ trans('main.q2a') }}</p>
      </div>
    </div>
  </div>
  
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="q3">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionQues" href="#collapseQ3" aria-expanded="false" aria-controls="collapseQ3">
            {{ trans('main.q3') }}
        </a>
      </h4>
    </div>
    <div id="collapseQ3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="q3">
      <div class="panel-body">
        <p class="qr">{{ trans('main.qR') }} {{ trans('main.q3r') }}</p>
        <p class="qa">{{ trans('main.qA') }} {{ trans('main.q3a') }}</p>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="q4">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionQues" href="#collapseQ4" aria-expanded="false" aria-controls="collapseQ4">
            {{ trans('main.q4') }}
        </a>
      </h4>
    </div>
    <div id="collapseQ4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="q4">
      <div class="panel-body">
        <p class="qr">{{ trans('main.qR') }} {{ trans('main.q4r') }}</p>
        <p class="qa">{{ trans('main.qA') }} {!! trans('main.q4a') !!}</p>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="q5">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionQues" href="#collapseQ5" aria-expanded="false" aria-controls="collapseQ5">
            {{ trans('main.q5') }}
        </a>
      </h4>
    </div>
    <div id="collapseQ5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="q5">
      <div class="panel-body">
        <p class="qr">{{ trans('main.qR') }} {{ trans('main.q5r') }}</p>
        <p class="qa">{{ trans('main.qA') }} {{ trans('main.q5a') }}</p>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="q6">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionQues" href="#collapseQ6" aria-expanded="false" aria-controls="collapseQ6">
            {{ trans('main.q6') }}
        </a>
      </h4>
    </div>
    <div id="collapseQ6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="q6">
      <div class="panel-body">
        <p class="qr">{{ trans('main.qR') }} {{ trans('main.q6r') }}</p>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="q7">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionQues" href="#collapseQ7" aria-expanded="false" aria-controls="collapseQ7">
            {{ trans('main.q7') }}
        </a>
      </h4>
    </div>
    <div id="collapseQ7" class="panel-collapse collapse" role="tabpanel" aria-labelledby="q7">
      <div class="panel-body">
        
        <p class="qa">{{ trans('main.qA') }} {!! trans('main.q7a') !!}</p>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="q8">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionQues" href="#collapseQ8" aria-expanded="false" aria-controls="collapseQ8">
            {{ trans('main.q8') }}
        </a>
      </h4>
    </div>
    <div id="collapseQ8" class="panel-collapse collapse" role="tabpanel" aria-labelledby="q8">
      <div class="panel-body">
        <p class="qr">{{ trans('main.qR') }} {{ trans('main.q8r') }}</p>
        <p class="qa">{{ trans('main.qA') }} {{ trans('main.q8a') }}</p>
      </div>
    </div>
  </div>

</div>

        </div>
        
    </div><!-- #publicQues --> 

    <div class="row" id="ContactFormUs">
        <div class="panel panel-default">
            <div class="panel-heading">{{ trans('main.ContactFormUs') }}</div>
            <div class="panel-body">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 contact">
                    <h3>{{ trans('main.contactInfo') }}</h3>
                    <div>
                        <div>
                            <ul class="contactInfo">
                                <li><i class="fa fa-map-marker"></i> {{ trans('main.contactTitle') }}</li>
                                <li><i class="fa fa-envelope"></i> mh@science.sohag.edu.eg</li>
                                <li><i class="fa fa-envelope"></i> emadeldeen_Soliman@science.sohag.edu.eg</li>
                                <li><i class="fa fa-envelope"></i> emade09@gmail.com</li>
                                <li><i class="fa fa-envelope"></i> taha@sohag.edu.eg</li>
                                <!-- <li><i class="fas fa-mobile-alt"></i> 01065551321</li> -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <form id='contactus' class='contactus' method='POST' action='{{ url('stuff/helpe') }}' enctype='multipart/form-data'>
                        {{ csrf_field() }}
                        <input type='hidden' name='_method' value='POST'>

                        
                        <input type='hidden' name='getBrowser' id='getBrowser' value=''>
                        <input type='hidden' name='getIP' id='getIP' value=''>
                    
                        <h3>{{ trans('main.sendMsg') }}</h3>
                        <div class="form-group{{ $errors->has('sName') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <label for="sName"> {{ trans('main.sName') }} *</label>
                            <input value="{{ old('sName') }}" type="text" name="sName" required class="form-control name" id="sName" placeholder="{{ trans('main.sName') }}">
                            
                            @if ($errors->has('sName'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('sName') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('user-email') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <label for="user-email"> {{ trans('main.emailUniv') }} *</label>
                            <input value="{{ old('user-email') }}" type="email" name="user-email" required class="form-control name" id="user-email" placeholder="{{ trans('main.emailUniv') }}">
                            
                            @if ($errors->has('user-email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user-email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="alert alert-danger col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>{{ trans('main.important') }}!:</strong> {{ trans('main.IdNote') }}
                            <br>{{ trans('main.gPhotoHint') }} (jpg, png)
                        </div>

                        <script type="text/javascript">
                            function readURL1(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
                    
                                    reader.onload = function (e) {
                                        $('#userIdPhoto1').attr('src', e.target.result);
                                        
                                    }
                    
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }

                            function readURL2(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
                    
                                    reader.onload = function (e) {
                                        $('#userIdPhoto2').attr('src', e.target.result);
                                        
                                    }
                    
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }


                        </script>


                        <div class="form-group{{ $errors->has('userId') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <label for="userIdPhoto1">{{ trans('main.userIdPhoto1') }} 
                                <img id="userIdPhoto1" class="thumb-sm" src="" alt="">
                            </label>
                            <input type="file" accept="image/x-png, image/gif, image/jpeg" name="userIdPhoto1" id="userIdPhoto1" onchange="readURL1(this);" class="userIdPhoto1">
                            
                            @if ($errors->has('userIdPhoto1'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('userIdPhoto1') }}</strong>
                                </span>
                            @endif
                        </div>                        

                        <div class="form-group{{ $errors->has('userId') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <label for="userIdPhoto1">{{ trans('main.userIdPhoto2') }} 
                                <img id="userIdPhoto2" class="thumb-sm" src="" alt="">
                            </label>
                            <input type="file" accept="image/x-png, image/gif, image/jpeg" name="userIdPhoto2" id="userIdPhoto2" onchange="readURL2(this);" class="userIdPhoto2">
                            @if ($errors->has('userIdPhoto2'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('userIdPhoto2') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('userId') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <label for="userId"> {{ trans('main.user-userID') }} *</label>
                            <input value="{{ old('userId') }}" type="number" max="99999999999999" min="10000000000000" name="userId" required class="form-control userId" id="userId" placeholder="{{ trans('main.user-userID') }}">
                            
                            @if ($errors->has('userId'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('userId') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('user-phone') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <label for="user-phone"> {{ trans('main.user-phone') }} *</label>
                            <input value="{{ old('user-phone') }}" type="number" max="10000000000" name="user-phone" required class="form-control user-phone" id="user-phone" placeholder="{{ trans('main.user-phone') }}">
                            
                            @if ($errors->has('user-phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user-phone') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('sWeb') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <label for="sWeb"> {{ trans('main.sWeb') }} ({{ trans('main.optional') }})</label>
                            <input value="{{ old('sWeb') }}" type="url" name="sWeb" class="form-control name" id="sWeb" placeholder="{{ trans('main.sWeb') }}">
                            
                            @if ($errors->has('sWeb'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('sWeb') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('faculty_id') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
                                    @if(app()->getlocale() == "en")
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

                        <div class="form-group{{ $errors->has('sMessage') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="sMessage"> {{ trans('main.sMessage') }} *</label>
                            <textarea required name="sMessage" id="sMessage" class="form-control sMessage" placeholder="">{{ old('sMessage') }}</textarea>
                            @if ($errors->has('sMessage'))
                                <span class="help-block">
                                    <strong>{!! $errors->first('sMessage') !!}</strong>
                                </span>
                            @endif
                        </div>
                        
<?php 
    session_start();
    $fn = rand(rand(1,10),rand(1,10));
    $sn = rand(rand(1,10), rand(1,10));
    $ops = [ '+', '*'];
    $op = rand(0, 1);
    $op = $ops[$op];
    $answer = 0;
    switch($op){
        case "+":
            $answer = $fn + $sn;
            break;
        /* 
        case "-":
            $answer = $fn - $sn;
            break; */

        case "*":
            $answer = $fn * $sn;
            break;
    }

    $_SESSION['answer'] = $answer;

?>

                        <div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <label class="lightP" for="answer">سؤال اجباري: {{ $sn }} {{ $op }} {{ $fn }} =  </label>
                            <input type="number" name="answer" required class="form-control name" id="answer" placeholder="اكتب الإجابه هنا">
                            
                            @if ($errors->has('answer'))
                                <span class="help-block">
                                    <strong>{!! $errors->first('answer') !!}</strong>
                                </span>
                            @endif
                        </div>
                        
                        
                        <button type="submit" class="btn btn-primary col-xs-12">{{ trans('main.Send') }}</button>                
                    
                    </form>
                </div>
            </div>
        </div>

        
            
    </div>
    
</div><!-- #loginTowebSite .loginTowebSite -->
@endsection

@section('scriptsData')
    
    <script>
        $(document).ready(function(){
            $("#getBrowser").val($.browser.name + " V. " + $.browser.version)

            $ipDesc = false;
        
            <?php $close =false; $ipDesc = ""; ?>
            @if(!isset($_COOKIE['ipDesc']))
            $.get("https://ipinfo.io", function(response) {
                <?php $close=true; ?>
                    $ipDesc ="Ip: " + response.ip + ", Country: " + response.country + ", City: " + response.city + ", ISP: " + response.hostname + ", Location" + response.loc;
                    $ipDesc += ", Browser: " + $.browser.name + " V. " + $.browser.version;
                    $ipDesc += navigator.appName + ", ";
                    $ipDesc += navigator.product + ", ";
                    $ipDesc += navigator.appVersion + ", ";
                    $ipDesc += navigator.platform + ", ";
                    $ipDesc += navigator.language + ", ";
                    $ipDesc += navigator.javaEnabled() + ", ";
                    <?php
                        if(!isset($_COOKIE['ipDesc']))
                            setcookie('ipDesc', $ipDesc, time() + (60 * 60  *25), "/");
                    ?>
                @else
                    $ipDesc ="{{ $_COOKIE['ipDesc'] }}";
                @endif
    
            @if($close)
            }, "jsonp")
            @endif
            
            if(!$ipDesc){
                @if(isset($_COOKIE['ipDesc']))
                    $ipDesc ="{{ $_COOKIE['ipDesc'] }}";
                @else
                    $ipDesc = "ip= {{ @file_get_contents('https://api.ipify.org') }}";
                    $ipDesc += ", Browser: " + $.browser.name + " V. " + $.browser.version;
                    $ipDesc += navigator.appName + ", ";
                    $ipDesc += navigator.product + ", ";
                    $ipDesc += navigator.appVersion + ", ";
                    $ipDesc += navigator.platform + ", ";
                    $ipDesc += navigator.language + ", ";
                    $ipDesc += navigator.javaEnabled() + ", ";
                    <?php
                        if(!isset($_COOKIE['ipDesc']))
                            setcookie('ipDesc', $ipDesc, time() + (60 * 60  *25), "/");
                    ?>
                @endif
            }

             $("#getIP").val($ipDesc);
        })
    </script>
@endsection
