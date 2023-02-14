{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ trans('main.navPosts') }}</title>

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
    @if($user->posts()->count() > 0)
        <div class="clearfix"></div>
        <div class="text-center">
                {!! $posts->appends(['p' => Request::get('p')])->render() !!}
        </div>
        <div class="clearfix"></div>

        <div role="tabpanel" class="tab-pane" id="posts">
            @foreach($posts as $post)
                <div class="col-md-12 blogPostH">
                    <div class="row">
                        <div class="col-md-12 post">
                            <div class="row">
                                <div class="col-md-12 post-header">
                                    <h4>
                                        <strong><a href="{{ url('/stuff/posts/show/'.$post->id) }}?p=posts" class="post-title">{{ $post->title }}</a></strong>
                                    </h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 post-header-line">
                                    <span class="glyphicon glyphicon-calendar"></span> {{ $post->year }} | 

                                    <i class="fas fa-tags"></i> {{ trans('main.postKeyWords') }}
                                    @foreach($post->tags as $tag)
                                        <a href="{{ url('stuff/home/tags/'.$tag->id.'/'.$user->id) }}?p=posts">{{ $tag->name }}</a>,  
                                    @endforeach
                                </div>
                            </div>
                            <div class="row post-content">
                                <div class="col-md-3 post-thumb">
                                    <a href="{{ url('stuff/posts/show/'.$post->id) }}?p=posts">
                                        @if(isset($post->photo))
                                            <img src="{{ url($user->uploads() . $post->photo->name) }}" alt="{{ $post->title }}">
                                        @else
                                        <img src="{{ url('images/posts-default.jpg') }}" alt="{{ $post->title }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="col-md-9 postText">
                                    {{ $user->stripText($post->content, 300) }}
                                    <a class="btn btn-read-more" href="{{ url('stuff/posts/show/'.$post->id."?p=posts") }}">{{ trans('main.readMore') }}</a></p>
                                </div>
                            </div>
                        </div>
                    </div><!-- EndPostRow -->
                </div>
            @endforeach
        </div>

        <div class="clearfix"></div>
        <div class="text-center">
            {!! $posts->appends(['p' => Request::get('p')])->render() !!}
        </div>

    @endif

    

@endsection