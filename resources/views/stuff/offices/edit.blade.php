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
        <div class="panel-heading">{{ trans('main.officeEditHead') }} : {{ $office->office }}  
            <a class="btn btn-primary pull-left" href="#"
            onclick="
                var result = confirm(' {{ trans('main.delQues') }} : {{ $office->office }}');
                if(result) {
                    event.preventDefault();
                    document.getElementById('delete-form{{ $office->id }}').submit();
                }
    
            "
            ><i class="fas fa-trash-alt"></i> {{ trans('main.delete') }} </a>
    
            <form id='delete-form{{ $office->id }}' class='delete-form' method='post' action='{{ route('offices.destroy', [$office->id]) }}'>
                {{ csrf_field() }}
                <input type='hidden' name='_method' value='DELETE'>
            </form><!--#delete-form .delete-form -->
        </div>
    <div class="panel-body panelBodyForm">
            @include("partials.errors")
            @include("partials.success")

        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action="{{ route('offices.update', [$office->id]) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='PUT'>

            <div class="form-group{{ $errors->has('office') ? ' has-error' : '' }}">
                <label for="office"> *{{ trans('main.officeTitle') }}</label>
                <input value="{{ $office->office }}" type="text" name="office" required class="form-control name" id="office" placeholder="{{ trans('main.officeTitle') }}">
                
                @if ($errors->has('office'))
                    <span class="help-block">
                        <strong>{{ $errors->first('office') }}</strong>
                    </span>
                @endif
            </div>
            
            <table id="timeTable" width="100%">
                <tr>
                    <td width="50%">
                         <div class="form-group{{ $errors->has('startTime') ? ' has-error' : '' }}">
                            <label for="startTime"> {{ trans('main.officeFrom') }}</label>
                            <input value="{{ $office->startTime }}" type="text" name="startTime" required class="form-control name" id="startTime" placeholder="{{ trans('main.officeFrom') }}">
                            <p class="inputNotes">{{ trans('main.officeTimeHint') }} hh:mm  - 24 Hours</p>
                            @if ($errors->has('startTime'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('startTime') }}</strong>
                                </span>
                            @endif
                        </div>
                    </td>

                    <td width="50%">
                         <div class="form-group{{ $errors->has('endTime') ? ' has-error' : '' }}">
                            <label for="endTime"> {{ trans('main.officeTo') }}</label>
                            <input value="{{ $office->endTime }}" type="text" name="endTime" required class="form-control name" id="endTime" placeholder="{{ trans('main.officeTo') }}">
                            <p class="inputNotes">{{ trans('main.officeTimeHint') }} hh:mm  - 24 Hours</p>
                            @if ($errors->has('endTime'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('endTime') }}</strong>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            <label>{{ trans('main.days') }}</label>
            <ul style="list-style: none;">
                {!! $office->days2box() !!}
            </ul>

            <button id="submitFormCreate" type="submit" form="CreateUser" class="btn btn-primary">{{ trans('main.gSave') }}</button>

            
        </form><!--#CreateUser .CreateUser -->   

        
    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->
@endsection
