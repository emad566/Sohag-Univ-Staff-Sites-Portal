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

@section('styles')



@endsection

@section('stuffContent')
<div class="panel panel-default">
    <div class="panel-heading">{{ trans('main.postCreateHead') }}</div>
    <div class="panel-body panelBodyForm">
        
        
        
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action="{{ route('posts.store', []) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <?php $randomId = $user->userFileId($user->id); ?>
            <input type='hidden' name='fileId' value='{{ $randomId }}'>

            <input type='hidden' name='_method' value='POST'>

            @if ($errors->has('photo_id'))
                <div class="alert alert-dismissable alert-danger">
                    <span class="help-block">
                        <strong>{{ $errors->first('photo_id') }}</strong>
                    </span>
                </div>
            @endif

            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title"> {{ trans('main.post-title') }} *</label>
                <input value="{{ old('title') }}" type="text" name="title" required class="form-control name" id="title" placeholder="{{ trans('main.post-title') }}">
                
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>
        
            <div class="form-group{{ $errors->has('journal') ? ' has-error' : '' }}">
                <label for="journal"> {{ trans('main.post-journal') }}</label>
                <input value="{{ old('journal') }}" type="text" name="journal"  class="form-control name" id="journal" placeholder="{{ trans('main.post-journal') }}">
                
                @if ($errors->has('journal'))
                    <span class="help-block">
                        <strong>{{ $errors->first('journal') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('year') ? ' has-error' : '' }}">
                <label for="year"> {{ trans('main.post-year') }}</label>
                <input value="{{ old('year') }}" type="number" name="year" class="form-control name" id="year" placeholder="{{ trans('main.post-year') }}">
                <p class="inputNotes">{{ trans('main.as') }}: 2018</p>
                @if ($errors->has('year'))
                    <span class="help-block">
                        <strong>{{ $errors->first('year') }}</strong>
                    </span>
                @endif
            </div>

            <div style="display: none" class="form-group{{ $errors->has('yearNum') ? ' has-error' : '' }}">
                <label for="yearNum"> {{ trans('main.post-yearNum') }}</label>
                <input value="{{ old('yearNum') }}" type="number" name="yearNum" class="form-control name" id="yearNum" placeholder="{{ trans('main.post-yearNum') }}">
                <p class="inputNotes">{{ trans('main.as') }}: 29</p>
                @if ($errors->has('yearNum'))
                    <span class="help-block">
                        <strong>{{ $errors->first('yearNum') }}</strong>
                    </span>
                @endif
            </div>

            <div style="display: none" class="form-group{{ $errors->has('num') ? ' has-error' : '' }}">
                <label for="num"> {{ trans('main.post-num') }}</label>
                <input value="{{ old('num') }}" type="number" name="num" class="form-control name" id="num" placeholder="{{ trans('main.post-num') }}">
                <p class="inputNotes">{{ trans('main.as') }} : 12</p>
                @if ($errors->has('num'))
                    <span class="help-block">
                        <strong>{{ $errors->first('num') }}</strong>
                    </span>
                @endif
            </div>

            <div style="display: none" class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                <label for="type"> {{ trans('main.post-type') }}</label>
                <input value="{{ old('type') }}" type="text" name="type" class="form-control name" id="type" placeholder="{{ trans('main.post-type') }}">
                <p class="inputNotes">{{ trans('main.posttypehint') }}</p>
                @if ($errors->has('type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
            </div>

            <div id="taginputDiv" class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                <label for="tags">{{ trans('main.postKeyWords') }}  *</label>
                <input name="tags" value="{{ old('tags') }}" id="tags" maxlength="30">
                <ul id="singleFieldTags"></ul>
                <p class="inputNotes">{{ trans('main.sep') }}</p>

                @if ($errors->has('tags'))
                    <span class="help-block">
                        <strong>{{ $errors->first('tags') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('auther') ? ' has-error' : '' }}">
                <label for="auther">{{ trans('main.post-auther') }}</label>
                <input value="{{ old('auther') }}" type="text" name="auther"  class="form-control name" id="auther" placeholder="{{ trans('main.post-auther') }}">
                
                @if ($errors->has('auther'))
                    <span class="help-block">
                        <strong>{{ $errors->first('auther') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                <label for="url">{{ trans('main.post-url') }} </label>
                <input value="{{ old('url') }}" type="text" name="url"  class="form-control name" id="url" placeholder="{{ trans('main.post-url') }} ">
                
                @if ($errors->has('url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('url') }}</strong>
                    </span>
                @endif
            </div>
{{--              
            <div class="form-group{{ $errors->has('urlTitle') ? ' has-error' : '' }}">
                <label for="urlTitle">{{ trans('main.post-urlTitle') }}</label>
                <input value="{{ old('urlTitle') }}" type="text" name="urlTitle"  class="form-control name" id="urlTitle" placeholder="{{ trans('main.post-urlTitle') }}">
                
                @if ($errors->has('urlTitle'))
                    <span class="help-block">
                        <strong>{{ $errors->first('urlTitle') }}</strong>
                    </span>
                @endif
            </div>

  --}}
            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                <label for="content">{{ trans('main.post-content') }} *</label>
                <textarea required name="content" id="content" class="form-control content editor" placeholder="{{ trans('main.post-content') }}">{{ old('content') }}</textarea>
                @if ($errors->has('content'))
                    <span class="help-block">
                        <strong>{{ $errors->first('content') }}</strong>
                    </span>
                @endif
            </div>

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

            <div class="form-group">
                <label for="photo_id">{{ trans('main.gPhoto') }} 
                    <img id="userPhoto" class="thumb-sm" src="" alt="">
                </label>
                <input type="file" accept="image/x-png, image/gif, image/jpeg" name="photo_id" id="photo_id" onchange="readURL(this);" class="photo_id">
                <p class="inputNotes">{{ trans('main.gPhotoHint') }} (jpg, png)</p>
                @if ($errors->has('photo_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('photo_id') }}</strong>
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
            <input type='hidden' name='fileable_type' value='App\Post'>

            <div class="dz-default dz-message"><span>{{ trans('main.gFileMsg') }}</span></div>
            
        </form><!--#file_id .file_id -->

        <button id="submitFormCreate" type="submit" form="CreateUser" class="btn btn-primary">{{ trans('main.gSave') }}</button>

    </div><!-- .panel-body  -->
</div><!-- .panel

    
@endsection

@section('scripts')

    
    
    <link href="{{ url('taginput/css/jquery.tagit.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('taginput/css/tagit.ui-zendesk.css') }}" rel="stylesheet" type="text/css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>

    <!-- The real deal -->
    <script src="{{ url('taginput/js/tag-it.js') }}" type="text/javascript" charset="utf-8"></script>
  

    <script type="text/javascript">
        
        jQuery(document).ready(function() {
            jQuery("#singleFieldTags").tagit({
                singleField: true,
                singleFieldNode: $('#tags'),
                allowSpaces: true,
                minLength: 2,
                removeConfirmation: true,
                autocomplete: {
                    delay: 0,
                    minLength: 2,
                 },
                 availableTags: {!! $tagsItems !!},
                    
            });
        });
    </script>


@endsection