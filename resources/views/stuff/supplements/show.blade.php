{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ $supplement->title }}</title>

    <!-- facebook meta -->
    @if($supplement->photos && $supplement->photos()->count()>0)
        <meta property="og:image" content="{{ url($supplement->photo->uploads . $supplement->photo->name) }}" />

        <link rel="icon" href="{{ url($supplement->photo->uploads . $supplement->photo->name) }}" sizes="32x32" />
        <link rel="icon" href="{{ url($supplement->photo->uploads . $supplement->photo->name) }}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{ url($supplement->photo->uploads . $supplement->photo->name) }}" />
        <meta name="msapplication-TileImage" content="{{ url($supplement->photo->uploads . $supplement->photo->name) }}" />
    @else
        <meta property="og:image" content="{{ url('images/fac_photo.png') }}" />
    @endif

    <meta property="og:title" content="{{ $supplement->title }}"/> 
    <?php 
        $webIntro = new \voku\Html2Text\Html2Text($supplement->content); 
        $webIntro = strip_tags($webIntro->getText())
    ?>
    <meta property="og:description" content="{{ substr($webIntro, 0, 300) . '...' }}" />

@endsection


@section('stuffContent')
<div class="col-xs-12 singlePost">
    <h1 class="postHeader"><a href="{{ url('stuff/subjects/show/'.$supplement->subject->id) }}">{{ $supplement->subject->title }}</a> - {{ $supplement->title }}</h1>
        
    @if($supplement->photos && $supplement->photos()->count()>0)
        <img class="postImg img img-fluid img-responsive" src="{{ url($supplement->photos()->first()->uploads.$supplement->photos()->first()->name) }}" alt="{{ $supplement->title }}">
    @endif
    
    <div class="row">
        <div class="col-md-12 post-header-line">
            <span class="glyphicon glyphicon-calendar"></span> {{ $supplement->created_at }} | 
            @if (!Auth::guest() && $isOwner)
                <a href="{{ url('stuff/supplements/'.$supplement->id.'/edit') }}"><i class="fas fa-edit"></i> {{ trans('main.edit') }} </a>
            @endif
        </div>
    </div>

    <div class="postContent">
        {!! $supplement->content !!}
    </div>

    @if($supplement->files()->count()>0)
    <div class="attached alert alert-success">
            <h4>{{ trans('main.gAttach') }}</h4>
            <hr/>
            <ul style="list-style: none;">
                @foreach($supplement->files as $file)
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

</div>
@endsection