{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ $subject->title }}</title>

    <!-- facebook meta -->
    @if($subject->photos && $subject->photos()->count()>0)
        <meta property="og:image" content="{{ url($subject->photo->uploads . $subject->photo->name) }}" />

        <link rel="icon" href="{{ url($subject->photo->uploads . $subject->photo->name) }}" sizes="32x32" />
        <link rel="icon" href="{{ url($subject->photo->uploads . $subject->photo->name) }}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{ url($subject->photo->uploads . $subject->photo->name) }}" />
        <meta name="msapplication-TileImage" content="{{ url($subject->photo->uploads . $subject->photo->name) }}" />
    @else
        <meta property="og:image" content="{{ url('images/fac_photo.png') }}" />
    @endif

    <meta property="og:title" content="{{ $subject->title }}"/> 
    <?php 
        $webIntro = new \voku\Html2Text\Html2Text($subject->content); 
        $webIntro = strip_tags($webIntro->getText())
    ?>
    <meta property="og:description" content="{{ substr($webIntro, 0, 300) . '...' }}" />

@endsection



@section('stuffContent')
<div class="col-xs-12 singlePost">
    <h1 class="postHeader">{{ $subject->title }}</h1>
        
    @if($subject->photos && $subject->photos()->count()>0)
        <img class="postImg img img-fluid img-responsive" src="{{ url($subject->photos()->first()->uploads.$subject->photos()->first()->name) }}" alt="{{ $subject->title }}">
    @endif
    
    <div class="row">
        <div class="col-md-12 post-header-line">
            <span class="glyphicon glyphicon-calendar"></span> {{ $subject->created_at }}  | 
            @if (!Auth::guest() && $isOwner)
                <a href="{{ url('stuff/subjects/'.$subject->id.'/edit') }}"><i class="fas fa-edit"></i> {{ trans('main.edit') }} </a>
            @endif
        </div>
    </div>

    <div class="postContent">
        {!! $subject->content !!}
    </div>

    @if($subject->files()->count()>0)
    <div class="attached alert alert-success">
            <h4>{{ trans('main.gAttach') }}</h4>
            <hr/>
            <ul style="list-style: none;">
                @foreach($subject->files as $file)
                    <li>
                        <label for="delId{{ $file->id }}">
                            <a id="{{ $file->id }}" class="downloadedA" target="_blank" href="{{ url($user->uploads() . $file->name ) }}"><i class="fas fa-book attachIcon"></i>{{ $file->name }} <span title="{{ trans('main.downloaded') }}" class="downloaded">{{ $file->downloaded }} <i class="fas fa-download downloadedI"></i></span> </a>
                        </label>
                    </li>
                @endforeach
            </ul>
        </table>
    </div>
    @endif

    <hr>

    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            @if($subject->tasks()->count() > 0)
                <li role="presentation" class="active"><a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab"> <i class="fas fa-newspaper"></i> {{ trans('main.subjectDownTasks') }}</a></li>
            @endif

            @if($subject->supplements()->count() > 0)
                <li role="presentation" class=""><a href="#supplements" aria-controls="supplements" role="tab" data-toggle="tab"> <i class="fas fa-clipboard"></i>  {{ trans('main.subjectDownSupp') }} </a></li>
            @endif
        </ul>
        
        <!-- Tab panes -->
        <div class="tab-content">

            @if($subject->tasks()->count() > 0)
                <div role="tabpanel" class="tab-pane active" id="tasks">
                    @foreach($subject->tasks as $task)
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
                                            <a href="{{ url('/stuff/subjects/show/'.$task->subject->id.'?p=subjects') }}"> <i class="fas fa-book"></i> {{ $task->subject->title }}</a>
                                        </div>
                                    </div>
                                    <div class="row post-content">
                                        <div class="col-md-3 post-thumb">
                                            <a href="{{ url('stuff/tasks/show/'.$task->id.'?p=tasks') }}">
                                                @if($task->photos && $task->photos()->count()>0)
                                                <img src="{{ url($user->uploads().$task->photos()->first()->name) }}" alt="{{ $task->title }}" class="img-responsive">
                                                @else
                                                <img src="{{ url('images/tasks-default.jpg') }}" alt="{{ $task->title }}" class="img-responsive">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="col-md-9 postText">
                                            <?php 
                                                $content = new \voku\Html2Text\Html2Text($task->content); 
                                                $content = strip_tags($content->getText())
                                            ?>
                                            {{ substr($content, 0, 300) . '...' }}
                                                <a class="btn btn-read-more" href="{{ url('stuff/tasks/show/'.$task->id."?p=tasks") }}">{{ trans('main.readMore') }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- EndPostRow -->
                        </div>
                    @endforeach
                </div>
            @endif
            

            @if($subject->supplements()->count() > 0)
                <div role="tabpanel" class="tab-pane" id="supplements">
                    <div class="col-md-12 blogPostH postItems">
                        <div class="row">
                            @foreach($subject->supplements as $supplement)
                                <div class="col-xs-12 col-md-4">
                                    <div class="post">
                                        <h4 class="col-md-12 post-header">
                                            <strong><a href="{{ url('/stuff/supplements/show/'.$supplement->id.'?p=supplements') }}" class="post-title">{{ substr($supplement->title, 0, 40) }}</a></strong>
                                        </h4>
                                        <div class="post-header-line">
                                            <span class="glyphicon glyphicon-calendar"></span> {{ $supplement->created_at }} 
                                            <a href="{{ url('/stuff/subjects/show/'.$supplement->subject->id.'?p=subjects') }}"> <i class="fas fa-book"></i> {{ $supplement->subject->title }}</a>
                                        </div>
                                        <div class="post-content post-thumb">
                                                
                                            <?php 
                                                $content = new \voku\Html2Text\Html2Text($supplement->content); 
                                                $content = strip_tags($content->getText())
                                            ?>
                                             @if(app()->getLocale() == "en")
                                    {{ substr($content, 0, 170) }}
                                @else
                                    {{ substr($content, 0, 230) }}
                                @endif
                                            <a class="btn btn-read-more" href="{{ url('stuff/supplements/show/'.$supplement->id."?p=supplements") }}">{{ trans('main.readMore') }}</a></p>
                                            
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div><!-- EndPostRow -->
                    </div>
                </div>
            @endif

        </div>
    
    </div>


</div>
@endsection