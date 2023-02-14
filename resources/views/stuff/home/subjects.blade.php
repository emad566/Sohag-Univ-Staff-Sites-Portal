{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ trans('main.navSubjects') }}</title>

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
@if($user->subjects()->count() > 0)
    <div role="tabpanel" class="tab-pane active" id="subjects">
        <div class="col-md-12 blogPostH postItems">
            <div class="row">
                @foreach($subjects as $subject)
                    <div class="col-xs-12 col-md-4">
                        <div class="post">
                            <h4 class="col-md-12 post-header">
                                <strong><a href="{{ url('/stuff/subjects/show/'.$subject->id) }}" class="post-title">{{ $user->stripText($subject->title, 40) }}</a></strong>
                            </h4>
                            <div class="post-header-line">
                                <span class="glyphicon glyphicon-calendar"></span> {{ $subject->created_at }}
                                <span class="spanIcon">
                                    <span><i class="fas fa-paperclip"></i> {{ $subject->supplements()->count() }} {{ trans('main.subjectSupp') }}</span> | 
                                    <span><i class="fas fa-tasks"></i> {{ $subject->tasks()->count() }} {{ trans('main.subjectTask') }}</span>
                                </span>  
                            </div>
                            <div class="post-content post-thumb">
                                @if(app()->getLocale() == "en")
                                    {{ $user->stripText($subject->content, 170) }}
                                @else
                                    {{ $user->stripText($subject->content, 230) }}
                                @endif
                                <a class="btn btn-read-more" href="{{ url('stuff/subjects/show/'.$subject->id."?p=subjects") }}">{{ trans('main.readMore') }}</a></p>
                                
                            </div>
                        </div>
                    </div>
                @endforeach
            </div><!-- EndPostRow -->
        </div>
    </div

    ><div class="clearfix"></div>
    <div class="text-center">
            {!! $subjects->appends(['p' => Request::get('p')])->render() !!}
    </div>
    
@endif
@endsection