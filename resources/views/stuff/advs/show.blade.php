{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ $adv->title }}</title>

    <!-- facebook meta -->
    @if($adv->photos && $adv->photos()->count()>0)
        <meta property="og:image" content="{{ url($adv->photos()->first()->uploads.$adv->photos()->first()->name) }}" />

        <link rel="icon" href="{{ url($adv->photos()->first()->uploads.$adv->photos()->first()->name) }}" sizes="32x32" />
        <link rel="icon" href="{{ url($adv->photos()->first()->uploads.$adv->photos()->first()->name) }}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{ url($adv->photos()->first()->uploads.$adv->photos()->first()->name) }}" />
        <meta name="msapplication-TileImage" content="{{ url($adv->photos()->first()->uploads.$adv->photos()->first()->name) }}" />
    @else
        <meta property="og:image" content="{{ url('images/fac_photo.png') }}" />
    @endif

    <meta property="og:title" content="{{ $adv->title }}"/> 
    <?php 
        $webIntro = new \voku\Html2Text\Html2Text($adv->content); 
        $webIntro = strip_tags($webIntro->getText())
    ?>
    <meta property="og:description" content="{{ substr($webIntro, 0, 300) . '...' }}" />

@endsection


@section('stuffContent')
<div class="col-xs-12 singlePost">
    <h1 class="postHeader">{{ $adv->title }}</h1>
        
    @if($adv->photos && $adv->photos()->count()>0)
        <img class="postImg img img-fluid img-responsive" src="{{ url($user->uploads().$adv->photos()->first()->name) }}" alt="{{ $adv->title }}">
    @endif

    <div class="row">
        <div class="col-md-12 post-header-line">
            <span class="glyphicon glyphicon-calendar"></span> {{ $adv->created_at }}  | 
            @if (!Auth::guest() && $isOwner)
                <a href="{{ url('stuff/advs/'.$adv->id.'/edit') }}"><i class="fas fa-edit"></i> {{ trans('main.edit') }} </a>
            @endif
        </div>
    </div>
    
    <div class="postContent">
        {!! $adv->content !!}
    </div>

    @if($adv->files()->count()>0)
    <div class="attached alert alert-success">
            <h4>{{ trans('main.gAttach') }}</h4>
            <hr/>
            <ul style="list-style: none;">
                @foreach($adv->files as $file)
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