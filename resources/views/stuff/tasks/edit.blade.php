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
    <div class="panel-heading">{{ trans('main.taskEditHead') }}: {{ $task->title }}  
        <a class="btn btn-primary pull-left" href="#"
        onclick="
            var result = confirm(' {{ trans('main.delQues') }} : {{ $task->title }}');
            if(result) {
                event.preventDefault();
                document.getElementById('delete-form{{ $task->id }}').submit();
            }

        "
        ><i class="fas fa-trash-alt"></i> {{ trans('main.delete') }} </a>

        <form id='delete-form{{ $task->id }}' class='delete-form' method='post' action='{{ route('tasks.destroy', [$task->id]) }}'>
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='DELETE'>
        </form><!--#delete-form .delete-form -->
    </div>

    <div class="panel-body panelBodyForm">
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action="{{ route('tasks.update', [$task->id]) }}" enctype="multipart/form-data">
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
                <label for="title"> {{ trans('main.gTitle') }} *</label>
                <input value="@if(old('title')){{ old('title') }}@else{{ $task->title }}@endif" type="text" name="title" required class="form-control name" id="title" placeholder="{{ trans('main.gTitle') }}">
                
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="fullDegree">{{ trans('main.fullDegree') }} *</label>
                <input value="@if(old('fullDegree')){{ old('fullDegree') }}@else{{ $task->fullDegree }}@endif" type="number" name="fullDegree" required class="form-control fullDegree"  id="fullDegree" placeholder="{{ trans('main.fullDegree') }}">
                
                @if ($errors->has('fullDegree'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fullDegree') }}</strong>
                    </span>
                @endif 
            </div>

            <div class="form-group{{ $errors->has('subject_id') ? ' has-error' : '' }}">
                <label for="subject">- {{ trans('main.subjectDropDown') }} -</label>
                <select required class="form-control" id="subject" name="subject_id" >
                    <option value="">- {{ trans('main.subjectDropDown') }} -</option>
                    <?php $select = 1; ?>
                    @foreach($user->subjects as $subject)
                        <option
                            @if (old('subject_id') == $subject->id) 
                                selected    <?php $select = 0; ?>
                            @elseif ($task->subject_id == $subject->id && $select == 1) 
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
                <label for="content"> {{ trans('main.gContent') }} *</label>
                <textarea required name="content" id="content" class="form-control content editor" placeholder="">@if(old('content')){{ old('content') }}@else{{ $task->content }}@endif</textarea>
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
                    <img id="userPhoto" class="thumb-sm" src="@if($task->photos()->first() != null) {{ url($user->uploads() . $task->photos()->first()->name) }}@endif" alt="">
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
                        @foreach($task->files as $file)
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
            <input type='hidden' name='fileable_id' value='{{ $task->id }}'>
            <input type='hidden' name='uploads_id' value='{{ $user->uploads() }}'>
            <input type='hidden' name='fUserId' value='{{ $user->id }}'>
            <input type='hidden' name='fileable_type' value='App\Task'>

            <div class="dz-default dz-message"><span>{{ trans('main.gFileMsg') }}</span></div>
            
        </form><!--#file_id .file_id -->

        <button id="submitFormCreate" type="submit" form="CreateUser" class="btn btn-primary">{{ trans('main.gSave') }}</button>
        

    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->
@endsection