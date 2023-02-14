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
    

    <div class="panel-body panelBodyForm postPanel">
        @include("partials.errors")
        @include("partials.success")
        <p class="createdAns"><span class="glyphicon glyphicon-calendar"></span> {{ $answer->created_at }}
            <a href="#"
            onclick="
                var result = confirm(' {{ trans('main.delQues') }} : {{ $answer->name }}');
                if(result) {
                    event.preventDefault();
                    document.getElementById('delete-form{{ $answer->id }}').submit();
                }

            "
            ><i class="fas fa-trash-alt delEdit"></i></a>

            <form id='delete-form{{ $answer->id }}' class='delete-form' method='post' action='{{ route('answers.destroy', [$answer->id]) }}'>
                {{ csrf_field() }}
                <input type='hidden' name='_method' value='DELETE'>
            </form><!--#delete-form .delete-form -->
        </p>
        
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action="{{ route('answers.update', [$answer->id]) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='PUT'>
            
            <table class="table table-hover postShow">
                @if($answer->task)
                <tr>
                    <th width="20%"><h2>{{ trans('main.answerH-task_id') }}</h2></th>
                    <td><h2><a href="{{ url("/stuff/tasks/show") }}/{{ $answer->task->id }}?p=tasks">{{ $answer->task->title }}</a></h2></td>
                </tr>
                @endif

                @if($answer->task->fullDegree)
                <tr>
                    <th width="20%">{{ trans('main.fullDegree') }}</th>
                    <td>{{ $answer->task->fullDegree }}</td>
                </tr>
                @endif

                @if($answer->setId)
                <tr>
                    <th width="20%">{{ trans('main.answerH-setId') }}</th>
                    <td>{{ $answer->setId }}</td>
                </tr>
                @endif
    
                @if($answer->menuId)
                <tr>
                    <th>{{ trans('main.answerH-menuId') }}</th>
                    <td>{{ $answer->menuId }}</td>
                </tr>
                @endif

                @if($answer->name)
                <tr>
                    <th>{{ trans('main.answerH-name') }}</th>
                    <td>{{ $answer->name }}</td>
                </tr>
                @endif

                <tr>
                    <th width="20%">{{ trans('main.stuId') }}</th>
                    <td>{{ $answer->stuId }}</td>
                </tr>

                @if($answer->facutly)
                <tr>
                    <th>{{ trans('main.answerH-facutly_id') }}</th>
                    <td>{{ $answer->facutly->name }}</td>
                </tr>
                @endif

                @if($answer->department)
                <tr>
                    <th>{{ trans('main.answerH-department') }}</th>
                    <td>{{ $answer->department }}</td>
                </tr>
                @endif
    
                @if($answer->level)
                <tr>
                    <th>{{ trans('main.answerH-level') }}</th>
                    <td>{{ $answer->level }}</td>
                </tr>
                @endif
    
                @if($answer->email)
                <tr>
                    <th>{{ trans('main.answerH-email') }}</th>
                    <td>{{ $answer->email }}</td>
                </tr>
                @endif
    
                @if($answer->nots)
                <tr>
                    <th>{{ trans('main.answerH-nots') }}</th>
                    <td>{{ $answer->nots }}</td>
                </tr>
                @endif

                @if($answer->files()->count()>0)
                    <tr>
                        <td>{{ trans('main.gAttach') }}</td>
                        <td>
                            <div class="attached alert alert-success">
                                <ul style="list-style: none;">
                                    @foreach($answer->files as $file)
                                        <li>
                                            <label for="delId{{ $file->id }}">
                                                <a target="_blank" href="{{ url($user->uploads() . $file->name ) }}"><i class="fas fa-book attachIcon"></i>{{ $file->name }}</a>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endif

                @if($answer->task->fullDegree)
                <tr>
                    <th><label for="stuDegree">{{ trans('main.stuDegree') }} *</label></th>
                    <td>
                        <div class="form-group">
                            <input value="@if($answer->stuDegree){{ $answer->stuDegree }}@endif" type="number" name="stuDegree" class="form-control stuDegree"  id="stuDegree" placeholder="{{ trans('main.stuDegree') }}">
                            
                            @if ($errors->has('stuDegree'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('stuDegree') }}</strong>
                                </span>
                            @endif 
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td></td>
                    <td><button  id="submitFormCreate" type="submit" form="CreateUser" class="btn btn-primary pull-left">{{ trans('main.gSave') }}</button></td> 
                </tr>

                @else
                <tr>
                    <td colspan="2">يجب عليك وضع درجة نهائية للواجب من <a target="_blank" href="{{ url("/stuff/tasks/".$answer->task->id."/edit") }}?p=tasks">هنا</a> حتي تتمكن من إعطاء درجة لهذه الإجابة</td>
                </tr>
                @endif
    
            </table>
        </form>
    </div>
   
@endsection