{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ trans('main.navAdvs') }}</title>

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
@if($user->advs()->count() > 0)
    <div role="tabpanel" class="tab-pane active" id="advs">
        @foreach($advs as $adv)
            <div class="col-md-12 blogPostH">
                <div class="row">
                    <div class="col-md-12 post">
                        <div class="row">
                            <div class="col-md-12 post-header">
                                <h4>
                                    <strong><a href="{{ url('/stuff/advs/show/'.$adv->id .'?p=advs') }}" class="post-title">{{ $adv->title }}</a></strong>
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
    </div

    ><div class="clearfix"></div>
    <div class="text-center">
            {!! $advs->appends(['p' => Request::get('p')])->render() !!}
    </div>
    
@endif
@endsection