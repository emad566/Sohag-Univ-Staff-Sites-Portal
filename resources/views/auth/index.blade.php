@include('common')
{{ setLang() }}
@extends('layouts.frontend')

@section('content')
<div id="contactWrap" class="row contactWrap">
    @include("partials.errors")
    @include("partials.success")
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 contact">
            <h3>{{ trans('main.contactInfo') }}</h3>
            <div class="wpb_text_column wpb_content_element ">
                <div class="wpb_wrapper">
                    <ul class="contactInfo">
                        <li><i class="fa fa-map-marker"></i> {{ trans('main.contactTitle') }}</li>
                        <li><i class="fa fa-envelope"></i> mh@science.sohag.edu.eg</li>
                        <!-- <li><i class="fas fa-mobile-alt"></i> 01065551321</li> -->
                    </ul>
                </div>
            </div>
        </div>
        <form id='contactus' class='contactus' method='POST' action='{{ url('stuff/helpe') }}' enctype=' text/plain'>
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='POST'>
            
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <h3>{{ trans('main.sendMsg') }}</h3>
                <div class="form-group{{ $errors->has('sName') ? ' has-error' : '' }}">
                    <label for="sName"> {{ trans('main.sName') }} *</label>
                    <input value="{{ old('sName') }}" type="text" name="sName" required class="form-control name" id="sName" placeholder="{{ trans('main.sName') }}">
                    
                    @if ($errors->has('sName'))
                        <span class="help-block">
                            <strong>{{ $errors->first('sName') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('user-email') ? ' has-error' : '' }}">
                    <label for="user-email"> {{ trans('main.user-email') }} *</label>
                    <input value="{{ old('user-email') }}" type="email" name="user-email" required class="form-control name" id="user-email" placeholder="{{ trans('main.user-email') }}">
                    
                    @if ($errors->has('user-email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('user-email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('user-phone') ? ' has-error' : '' }}">
                    <label for="user-phone"> {{ trans('main.user-phone') }} *</label>
                    <input value="{{ old('user-phone') }}" type="phone" name="user-phone" required class="form-control user-phone" id="user-phone" placeholder="{{ trans('main.user-phone') }}">
                    
                    @if ($errors->has('user-phone'))
                        <span class="help-block">
                            <strong>{{ $errors->first('user-phone') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('sWeb') ? ' has-error' : '' }}">
                    <label for="sWeb"> {{ trans('main.sWeb') }} *</label>
                    <input value="{{ old('sWeb') }}" type="url" name="sWeb" required class="form-control name" id="sWeb" placeholder="{{ trans('main.sWeb') }}">
                    
                    @if ($errors->has('sWeb'))
                        <span class="help-block">
                            <strong>{{ $errors->first('sWeb') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('sMessage') ? ' has-error' : '' }}">
                    <label for="sMessage"> {{ trans('main.sMessage') }} *</label>
                    <textarea required name="sMessage" id="sMessage" class="form-control sMessage editor" placeholder="">{{ old('sMessage') }}</textarea>
                    @if ($errors->has('sMessage'))
                        <span class="help-block">
                            <strong>{!! $errors->first('sMessage') !!}</strong>
                        </span>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-primary">{{ trans('main.Send') }}</button>                
            </div>
            
            
        </form>
            
    </div>
    
</div><!-- #loginTowebSite .loginTowebSite -->
@endsection

@section('scriptsData')
<script>
    $(document).ready(function(){
        
    })
</script>
@endsection