{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ trans('main.navTasks') }}</title>

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
@if($user->tasks()->count() > 0)
    <div role="tabpanel" class="tab-pane active" id="tasks">
    <div class="form-group{{ $errors->has('subject_id') ? ' has-error' : '' }}">
        <label for="subject">- {{ trans('main.sCourse') }} -</label>
        <select required class="form-control" id="subject" name="subject_id" >
            <option value="all" 
                    @if ($subject_id == "all") 
                        selected
                    @endif
            >{{ trans('main.allSub') }}</option>
            @foreach($user->subjects as $subject)
                @if($subject->tasks()->count()>0)
                    <option
                            @if ($subject_id == $subject->id) 
                                selected
                            @endif 
                            value="{{ $subject->id }}"
                    >{{ $subject->title }}</option>
                @endif
            @endforeach
        </select>
        
        @if ($errors->has('subject_id'))
            <span class="help-block">
                <strong>{{ $errors->first('subject_id') }}</strong>
            </span>
        @endif    
    </div>
        @foreach($tasks as $task)
            <div class="col-md-12 blogPostH">
                <div class="row">
                    <div class="col-md-12 post">
                        <div class="row">
                            <div class="col-md-12 post-header">
                                <h4>
                                    <strong><a href="{{ url('/stuff/tasks/show/'.$task->id.'?p=tasks') }}" class="post-title">{{ $task->title }}</a></strong>
                                </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 post-header-line">
                                <span class="glyphicon glyphicon-calendar"></span> {{ $task->created_at }} | 
                                <i class="fas fa-file-signature"></i> {{ $task->answers()->count() }} | 
                                <a href="{{ url('/stuff/subjects/show/'.$task->subject->id.'?p=subjects') }}"> <i class="fas fa-book"></i> {{ $task->subject->title }}</a>
                            </div>
                        </div>
                        <div class="row post-content">
                            <div class="col-md-3 post-thumb">
                                <a href="{{ url('stuff/tasks/'.$task->id.'?p=tasks') }}">
                                    @if($task->photos()->count()>0)
                                    <img src="{{ url($user->uploads().$task->photos()->first()->name) }}" alt="{{ $task->title }}" class="img-responsive">
                                    @else
                                    <img src="{{ url('images/tasks-default.jpg') }}" alt="{{ $task->title }}" class="img-responsive">
                                    @endif
                                </a>
                            </div>
                            <div class="col-md-9 postText">
                                {{ $user->stripText($task->content, 300) }}
                                <a class="btn btn-read-more" href="{{ url('stuff/tasks/show/'.$task->id."?p=tasks") }}">{{ trans('main.readMore') }}</a></p>
                            </div>
                        </div>
                    </div>
                </div><!-- EndPostRow -->
            </div>
        @endforeach
    </div

    ><div class="clearfix"></div>
    <div class="text-center">
            {!! $tasks->appends(['p' => Request::get('p')])->render() !!}
    </div>
    
@endif
@endsection

@section('scriptsData')
<script>
    $(document).ready(function(){
        $( "#subject" ).change(function() {
            window.location.replace('{{ url('/stuff/home/tasks/'.$user->id) }}/' + $( "#subject" ).val() +'?p=tasks')
        });
    })
</script>

@endsection