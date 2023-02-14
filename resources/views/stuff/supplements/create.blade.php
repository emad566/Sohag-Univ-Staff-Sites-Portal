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
@if($user->subjects->count() > 0)
    <div class="panel-heading">{{ trans('main.supplementCreateHead') }}</div>
    <div class="panel-body panelBodyForm">
        @include("partials.errors")
        @include("partials.success")
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action="{{ route('supplements.store', []) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <?php $randomId = $user->userFileId($user->id); ?>
            <input type='hidden' name='fileId' value='{{ $randomId }}'>

            <input type='hidden' name='_method' value='POST'>

            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title"> {{ trans('main.gTitle') }} *</label>
                <input value="{{ old('title') }}" type="text" name="title" required class="form-control name" id="title" placeholder=" {{ trans('main.gTitle') }} ">
                
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('subject_id') ? ' has-error' : '' }}">
                <label for="subject">- {{ trans('main.subjectDropDown') }} -</label>
                <select required class="form-control" id="subject" name="subject_id" >
                    <option value="">- {{ trans('main.subjectDropDown') }} -</option>
                    @foreach($user->subjects as $subject)
                        <option
                             @if (old('subject_id') == $subject->id) 
                                selected
                            @endif 
                        value="{{ $subject->id }}">{{ $subject->title }}</option>
                    @endforeach
                </select>
                
                @if ($errors->has('subject_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('subject_id') }}</strong>
                    </span>
                @endif    
            </div>

            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                <label for="content"> {{ trans('main.supplementDescription') }} *</label>
                <textarea required name="content" id="content" class="form-control content editor" placeholder="">{{ old('content') }}</textarea>
                @if ($errors->has('content'))
                    <span class="help-block">
                        <strong>{{ $errors->first('content') }}</strong>
                    </span>
                @endif
            </div>
            
        </form><!--#CreateUser .CreateUser -->  
        
        <label for="file_id">{{ trans('main.gFile') }}  
        </label>
        <form id='file_id' class='file_id dropzone' method='POST' action='{{ route('files.store', [])}}' enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='POST'>
            <input type='hidden' name='fileable_id' value='{{ $randomId }}'>
            <input type='hidden' name='uploads_id' value='{{ $user->uploads() }}'>
            <input type='hidden' name='fUserId' value='{{ $user->id }}'>
            <input type='hidden' name='fileable_type' value='App\Supplement'>

            <div class="dz-default dz-message"><span>{{ trans('main.gFileMsg') }}</span></div>
            
        </form><!--#file_id .file_id -->

        <button id="submitFormCreate" type="submit" form="CreateUser" class="btn btn-primary">{{ trans('main.gSave') }}</button>

    </div><!-- .panel-body  -->
@else
    <p class="alert alert-danger">يجب عليك <a href="{{ url('subjects/create') }}">إضافة مادة دراسية</a> علي الأقل لتتكمن من إضافة ملحق للمادة الدراسية</p>
@endif
</div><!-- .panel panel-default  -->
@endsection