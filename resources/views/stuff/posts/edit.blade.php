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
    <div class="panel-heading">{{ trans('main.postEditHead') }}: {{ $post->title }}  
        <a class="btn btn-primary pull-left" href="#"
        onclick="
            var result = confirm(' {{ trans('main.delQues') }} : {{ $post->title }}');
            if(result) {
                event.preventDefault();
                document.getElementById('delete-form{{ $post->id }}').submit();
            }

        "
        ><i class="fas fa-trash-alt"></i> {{ trans('main.delete') }} </a>

        <form id='delete-form{{ $post->id }}' class='delete-form' method='post' action='{{ route('posts.destroy', [$post->id]) }}'>
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='DELETE'>
        </form><!--#delete-form .delete-form -->
    </div>
    <div class="panel-body panelBodyForm">
     
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action="{{ route('posts.update', [$post->id]) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='PUT'>

            @if ($errors->has('photo_id'))
                <div class="alert alert-dismissable alert-danger">
                    <span class="help-block">
                        <strong>{{ $errors->first('photo_id') }}</strong>
                    </span>
                </div>
            @endif

            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title"> {{ trans('main.post-title') }} *</label>
                <input value="{{ $post->title }}" type="text" name="title" required class="form-control name" id="title" placeholder="{{ trans('main.post-title') }}">
                
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>
        
            <div class="form-group{{ $errors->has('journal') ? ' has-error' : '' }}">
                <label for="journal"> {{ trans('main.post-journal') }}</label>
                <input value="{{ $post->journal }}" type="text" name="journal"  class="form-control name" id="journal" placeholder="{{ trans('main.post-journal') }}">
                
                @if ($errors->has('journal'))
                    <span class="help-block">
                        <strong>{{ $errors->first('journal') }}</strong>
                    </span>
                @endif
            </div>

        <div class="form-group{{ $errors->has('year') ? ' has-error' : '' }}">
            <label for="year"> {{ trans('main.post-year') }}</label>
            <input value="{{ $post->year }}" type="number" name="year" class="form-control name" id="year" placeholder="{{ trans('main.post-year') }}">
            <p class="inputNotes">{{ trans('main.as') }}: 2018</p>
            @if ($errors->has('year'))
                <span class="help-block">
                    <strong>{{ $errors->first('year') }}</strong>
                </span>
            @endif
        </div>

            <div style="display: none" class="form-group{{ $errors->has('yearNum') ? ' has-error' : '' }}">
                <label for="yearNum"> {{ trans('main.post-yearNum') }}</label>
                <input value="{{ $post->yearNum }}" type="number" name="yearNum" class="form-control name" id="yearNum" placeholder="{{ trans('main.post-yearNum') }}">
                <p class="inputNotes">{{ trans('main.as') }}: 29</p>
                @if ($errors->has('yearNum'))
                    <span class="help-block">
                        <strong>{{ $errors->first('yearNum') }}</strong>
                    </span>
                @endif
            </div>

            <div style="display: none" class="form-group{{ $errors->has('num') ? ' has-error' : '' }}">
                <label for="num"> {{ trans('main.post-num') }}</label>
                <input value="{{ $post->num }}" type="number" name="num" class="form-control name" id="num" placeholder="{{ trans('main.post-num') }}">
                <p class="inputNotes">{{ trans('main.as') }} : 12</p>
                @if ($errors->has('num'))
                    <span class="help-block">
                        <strong>{{ $errors->first('num') }}</strong>
                    </span>
                @endif
            </div>

            <div style="display: none" class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                <label for="type"> {{ trans('main.post-type') }}</label>
                <input value="{{ $post->type }}" type="text" name="type" class="form-control name" id="type" placeholder="{{ trans('main.post-type') }}">
                <p class="inputNotes">{{ trans('main.posttypehint') }}</p>
                @if ($errors->has('type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
            </div>

            <div id="taginputDiv" class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                <label for="tags">{{ trans('main.postKeyWords') }}  *</label>
                <input name="tags" value="{{ $tagsChoosed }}" id="tags" maxlength="30">
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
                <input value="{{ $post->auther }}" type="text" name="auther"  class="form-control name" id="auther" placeholder="{{ trans('main.post-auther') }}">
                
                @if ($errors->has('auther'))
                    <span class="help-block">
                        <strong>{{ $errors->first('auther') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                <label for="url">{{ trans('main.post-url') }} </label>
                <input value="{{ $post->url }}" type="text" name="url"  class="form-control name" id="url" placeholder="{{ trans('main.post-url') }} ">
                
                @if ($errors->has('url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('url') }}</strong>
                    </span>
                @endif
            </div>
            
{{--  
            <div class="form-group{{ $errors->has('urlTitle') ? ' has-error' : '' }}">
                <label for="urlTitle">{{ trans('main.post-urlTitle') }}</label>
                <input value="{{ $post->urlTitle }}" type="text" name="urlTitle"  class="form-control name" id="urlTitle" placeholder="{{ trans('main.post-urlTitle') }}">
                
                @if ($errors->has('urlTitle'))
                    <span class="help-block">
                        <strong>{{ $errors->first('urlTitle') }}</strong>
                    </span>
                @endif
            </div>

  --}}
            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                <label for="content">{{ trans('main.post-content') }} *</label>
                <textarea required name="content" id="content" class="form-control content editor" placeholder="{{ trans('main.post-content') }}">{{ $post->content }}</textarea>
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
                    <img id="userPhoto" class="thumb-sm" src="@if(isset($post->photo)) {{ url($user->uploads() . $post->photo->name) }}@endif" alt="">
                </label>
                <input type="file" accept="image/x-png, image/gif, image/jpeg" name="photo_id" id="photo_id" onchange="readURL(this);" class="photo_id">
                <p class="inputNotes">{{ trans('main.gPhotoHint') }} (jpg, png)</p>
                @if ($errors->has('photo_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('photo_id') }}</strong>
                    </span>
                @endif
            </div>

            <div class="attached alert alert-success">
                    <h4>{{ trans('main.gAttach') }}</h4>
                    <hr/>
                    <ul style="list-style: none;">
                        @foreach($post->files as $file)
                            <li>
                                <label for="delId{{ $file->id }}">
                                    <input type="checkbox" value="{{ $file->id }}" name="delId[]" id="delId{{ $file->id }}"> {{ trans('main.delete') }} :- 
                                    <a target="_blank" href="{{ url($user->uploads() . $file->name ) }}"><i class="fas fa-book attachIcon"></i>{{ $file->name }}</a>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </table>
            </div>

            
        </form><!--#CreateUser .CreateUser -->   

        
        <label for="file_id">{{ trans('main.gFile') }}  
        </label>
        <form id='file_id' class='file_id dropzone' method='POST' action='{{ route('files.store', [])}}' enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='POST'>
            <input type='hidden' name='fileable_id' value='{{ $post->id }}'>
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