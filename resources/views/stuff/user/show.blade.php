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

<div class="stuffHome">
@if (!Auth::guest() && $isOwner)<a class="pull-left btn btn-primary webIntroEdit" href="{{ url('stuff/user/'.$user->id.'/edit') }}">{{ trans('main.WebIntroBtnEdit') }}</a>@endif
    @include("partials.errors")
    @include("partials.success")
    
    @if($user->webIntro)
    {!! $user->webIntro !!}
    @else
    <h1>{{ trans('main.defaultWelcomeMsg') }}</h1>
    @endif

    <hr>
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            @if($user->advs()->count() > 0)
                <li role="presentation" class="active"><a href="#advs" aria-controls="advs" role="tab" data-toggle="tab"> <i class="fas fa-newspaper"></i> {{ trans('main.manageAdvs') }}</a></li>
            @endif

            @if($user->posts()->count() > 0)
                <li role="presentation" class=""><a href="#posts" aria-controls="posts" role="tab" data-toggle="tab"> <i class="fas fa-clipboard"></i>  {{ trans('main.managePosts') }} </a></li>
            @endif
            
            @if($user->tasks()->count() > 0)
                <li role="presentation" class=""><a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab"> <i class="fas fa-tasks"></i> {{ trans('main.manageTasks') }} </a></li>
            @endif
            
            
        </ul>
        
        <!-- Tab panes -->
        <div class="tab-content">
            @if($user->advs()->count() > 0)
                <div role="tabpanel" class="tab-pane active" id="advs">
                    <?php $advs =  $user->advs()->orderBy('updated_at', 'DESC')->paginate(5);  ?>
                    @foreach($advs as $adv)
                        <div class="col-md-12 blogPostH">
                            <div class="row">
                                <div class="col-md-12 post">
                                    <div class="row">
                                        <div class="col-md-12 post-header">
                                            <h4>
                                                <strong><a href="{{ url('/stuff/advs/show/'.$adv->id.'?p=advs') }}" class="post-title">{{ $adv->title }}</a></strong>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 post-header-line">
                                            <span class="glyphicon glyphicon-calendar"></span> {{ $adv->created_at }} 
                                        </div>
                                    </div>
                                    <div class="row post-content">
                                        <div class="col-md-3 post-thumb">
                                            <a href="{{ url('stuff/advs/show/'.$adv->id.'?p=advs') }}">
                                                @if($adv->photos()->count()>0)
                                                <img src="{{ url($user->uploads().$adv->photos()->first()->name) }}" alt="{{ $adv->title }}" class="img-responsive">
                                                @else
                                                <img src="{{ url('images/advs-default.jpg') }}" alt="{{ $adv->title }}" class="img-responsive">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="col-md-9 postText">
                                                {{ $user->stripText($adv->content, 300) }}
                                                <a class="btn btn-read-more" href="{{ url('stuff/advs/show/'.$adv->id."?p=advs") }}">{{ trans('main.readMore') }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- EndPostRow -->
                        </div>
                    @endforeach
                    <div class="clearfix"></div>
                    <div class="text-center">
                        <a class="pReadMore btn btn-primary col-lg-12" href="{{ url('stuff/home/advs/'.$user->id.'?p=advs') }}">{{ trans('main.readMoreAdv') }}</a>
                    </div>
                </div>
            @endif

            @if($user->posts()->count() > 0)
                <div role="tabpanel" class="tab-pane" id="posts">
                    <?php $posts =  $user->posts()->orderBy('updated_at', 'DESC')->paginate(5);  ?>
                    @foreach($posts as $post)
                        <div class="col-md-12 blogPostH">
                            <div class="row">
                                <div class="col-md-12 post">
                                    <div class="row">
                                        <div class="col-md-12 post-header">
                                            <h4>
                                                <strong><a href="{{ url('/stuff/posts/show/'.$post->id.'?p=posts') }}" class="post-title">{{ $post->title }}</a></strong>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="row tagsDiv">
                                        <div class="col-md-12 post-header-line">
                                            <span class="glyphicon glyphicon-calendar"></span> {{ $post->created_at }}  | 
                                            <i class="fas fa-tags"></i> {{ trans('main.postKeyWords') }}
                                            @foreach($post->tags()->orderBy('updated_at', 'DESC')->get() as $tag)
                                                <a href="{{ url('stuff/home/tags/'.$tag->id.'/'.$user->id) }}">{{ $tag->name }}</a>,  
                                            @endforeach 
                                        </div>
                                    </div>
                                    <div class="row post-content">
                                        <div class="col-md-3 post-thumb">
                                            <a href="{{ url('stuff/posts/show/'.$post->id.'?p=posts') }}">
                                                @if(isset($post->photo))
                                                    <img class="img-responsive" src="{{ url($user->uploads() . $post->photo->name) }}" alt="{{ $post->title }}">
                                                @else
                                                <img src="{{ url('images/posts-default.jpg') }}" alt="{{ $post->title }}" class="img-responsive">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="col-md-9 postText">
                                                {{ $user->stripText($post->content, 300) }}
                                                <a class="btn btn-read-more" href="{{ url('stuff/posts/show/'.$post->id)."?p=posts" }}">{{ trans('main.readMore') }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- EndPostRow -->
                        </div>
                    @endforeach
                    <div class="clearfix"></div>
                    <div class="text-center">
                        <a class="pReadMore btn btn-primary col-lg-12" href="{{ url('stuff/home/posts/'.$user->id.'?p=advs') }}">{{ trans('main.readMorePosts') }}</a>
                    </div>
                </div>
            @endif

            @if($user->tasks()->count() > 0)
                <div role="tabpanel" class="tab-pane" id="tasks">
                    <?php $tasks = $user->tasks()->orderBy('updated_at', 'DESC')->paginate(5);  ?>
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
                                                {{ $user->stripText($task->content, 300) }}
                                                <a class="btn btn-read-more" href="{{ url('stuff/tasks/show/'.$task->id."?p=tasks") }}">{{ trans('main.readMore') }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- EndPostRow -->
                        </div>
                    @endforeach
                    <div class="clearfix"></div>
                    <div class="text-center">
                        <a class="pReadMore btn btn-primary col-lg-12" href="{{ url('stuff/home/tasks/'.$user->id.'?p=advs') }}">{{ trans('main.readMoreTasks') }}</a>
                    </div>
                </div>
            @endif

        </div>
    
    </div>
</div><!-- .stuffHome -->
@endsection

@section('scripts')

@endsection
