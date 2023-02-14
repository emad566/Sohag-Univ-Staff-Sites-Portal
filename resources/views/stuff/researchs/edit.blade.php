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
    <div class="panel-heading">{{ trans('main.researchCreateHead') }}: {{ $research->title }}  
        <a class="btn btn-primary pull-left" href="#"
        onclick="
            var result = confirm(' {{ trans('main.delQues') }} : {{ $research->title }}');
            if(result) {
                event.preventDefault();
                document.getElementById('delete-form{{ $research->id }}').submit();
            }

        "
        ><i class="fas fa-trash-alt"></i> {{ trans('main.delete') }} </a>

        <form id='delete-form{{ $research->id }}' class='delete-form' method='post' action='{{ route('researchs.destroy', [$research->id]) }}'>
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='DELETE'>
        </form><!--#delete-form .delete-form -->
    </div>

    <div class="panel-body panelBodyForm">
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action="{{ route('researchs.update', [$research->id]) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='PUT'>

            <div class="form-group{{ $errors->has('facStu_id') ? ' has-error' : '' }}">
                <label for="faculty">{{ trans('main.stu-faculty_id') }} *</label>
                <select required class="form-control" id="faculty" name="facStu_id" >
                    <option value="">- {{ trans('main.sfac') }} -</option>
                    @foreach($faculties as $faculty)
                        <?php $select = 1; ?>
                        <option
                             @if(old('facStu_id') == $faculty->id) 
                                selected    <?php $select = 0; ?>
                            @elseif ($research->facStu_id == $faculty->id && $select == 1) 
                                selected
                            @endif 
                        value="{{ $faculty->id }}">
                        @if(app()->getLocale() == "en")
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
                
                @if ($errors->has('facStu_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('facStu_id') }}</strong>
                    </span>
                @endif    
            </div>

            <div class="form-group{{ $errors->has('level_id') ? ' has-error' : '' }}">
                <label for="level">{{ trans('main.answerH-level') }} *</label>
                <select required class="form-control" id="level" name="level_id" >
                    <option value="">- {{ trans('main.answerH-level') }} * -</option>
                    @foreach($levels as $level)
                        <?php $select = 1; ?>
                        <option
                            @if(old('level_id') == $level->id) 
                                selected    <?php $select = 0; ?>
                            @elseif ($research->level_id == $level->id && $select == 1) 
                                selected
                            @endif
                        value="{{ $level->id }}">
                        @if(app()->getLocale() == "en")
                            @if($level->en)
                                {{ $level->en }}
                            @else
                               {{ $level->ar }}
                            @endif
                        @else
                           {{ $level->ar }}
                        @endif
                        </option>
                    @endforeach
                </select>
                
                @if ($errors->has('level_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('level_id') }}</strong>
                    </span>
                @endif    
            </div>

            <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                <label for="department"> {{ trans('main.answerH-department') }} *</label>
                <input value="@if(old('department')){{ old('department') }}@else{{ $research->department }}@endif" type="text" name="department" required class="form-control name" id="department" placeholder=" {{ trans('main.answerH-department') }} *">
                
                @if ($errors->has('department'))
                    <span class="help-block">
                        <strong>{{ $errors->first('department') }}</strong>
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
                            @elseif ($research->subject_id == $subject->id && $select == 1) 
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

            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title"> {{ trans('main.gTitle') }} *</label>
                <input value="@if(old('title')){{ old('title') }}@else{{ $research->title }}@endif" type="text" name="title" required class="form-control name" id="title" placeholder="{{ trans('main.gTitle') }}">
                
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>

            

            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                <label for="content"> {{ trans('main.QesReq') }} *</label>
                <textarea required name="content" id="content" class="form-control content editor" placeholder="">@if(old('content')){{ old('content') }}@else{{ $research->content }}@endif</textarea>
                @if ($errors->has('content'))
                    <span class="help-block">
                        <strong>{{ $errors->first('content') }}</strong>
                    </span>
                @endif
            </div>
           
            

            <div class="attached alert alert-success">
                    <h4>{{ trans('main.gAttach') }}</h4>
                    <hr/>
                    <ul style="list-style: none;">
                        @foreach($research->files as $file)
                            <li>
                                <label for="delId{{ $file->id }}">
                                    <input type="checkbox" value="{{ $file->id }}" name="delId[]" id="delId{{ $file->id }}"> {{ trans('main.delete') }} :- 
                                    <a target="_blank" href="{{ url($user->uploads('researchs') . $file->name ) }}"><i class="fas fa-book attachIcon"></i>{{ $file->name }}</a>
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
            <input type='hidden' name='fileable_id' value='{{ $research->id }}'>
            <input type='hidden' name='uploads_id' value='{{ $user->uploads('researchs') }}'>
            <input type='hidden' name='fUserId' value='{{ $user->id }}'>
            <input type='hidden' name='fileable_type' value='App\Research'>

            <div class="dz-default dz-message"><span>{{ trans('main.gFileMsg') }}</span></div>
            
        </form><!--#file_id .file_id -->

        <button id="submitFormCreate" type="submit" form="CreateUser" class="btn btn-primary">{{ trans('main.gSave') }}</button>
        

    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->
@endsection