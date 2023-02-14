{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ $post->title }}</title>

    <!-- facebook meta -->
    @if(isset($post->photo))
        <meta property="og:image" content="{{ url($user->uploads() . $post->photo->name) }}" />

        <link rel="icon" href="{{ url($user->uploads() . $post->photo->name) }}" sizes="32x32" />
        <link rel="icon" href="{{ url($user->uploads() . $post->photo->name) }}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{ url($user->uploads() . $post->photo->name) }}" />
        <meta name="msapplication-TileImage" content="{{ url($user->uploads() . $post->photo->name) }}" />
    @else
        <meta property="og:image" content="{{ url('images/fac_photo.png') }}" />
    @endif

    <meta property="og:title" content="{{ $post->title }}"/> 
    <?php 
        $webIntro = new \voku\Html2Text\Html2Text($post->content); 
        $webIntro = strip_tags($webIntro->getText())
    ?>
    <meta property="og:description" content="{{ substr($webIntro, 0, 300) . '...' }}" />

@endsection


@section('stuffContent')
<div class="col-xs-12 singlePost">
    <h1 class="postHeader">{{ $post->title }}</h1>

    @if(isset($post->photo))
        <img class="postImg img img-fluid img-responsive" src="{{ url($user->uploads() . $post->photo->name) }}" alt="{{ $post->title }}">
    @endif

    <div class="row tagsDiv">
        <div class="col-md-12 post-header-line">
            <span class="glyphicon glyphicon-calendar"></span> {{ $post->created_at }}  | 
            <i class="fas fa-tags"></i> {{ trans('main.postKeyWords') }}
            @foreach($post->tags as $tag)
                <a href="{{ url('stuff/home/tags/'.$tag->id.'/'.$user->id) }}">{{ $tag->name }}</a>,  
            @endforeach | 
            @if (!Auth::guest() && $isOwner)
                <a href="{{ url('stuff/posts/'.$post->id.'/edit') }}"><i class="fas fa-edit"></i> {{ trans('main.edit') }} </a>
            @endif
        </div>
    </div>

    

    <div class="panel-body panelBodyForm postPanel">
          
        <table class="table table-hover postShow">
            @if($post->journal)
            <tr>
                <th width="20%">{{ trans('main.post-journal') }}</th>
                <td>{{ $post->journal }}</td>
            </tr>
            @endif
 
            @if($post->num)
            <tr>
                <th>{{ trans('main.post-num') }}</th>
                <td>{{ $post->num }}</td>
            </tr>
            @endif
 
            @if($post->yearNum)
            <tr>
                <th>{{ trans('main.post-yearNum') }}</th>
                <td>{{ $post->yearNum }}</td>
            </tr>
            @endif
 
            @if($post->year)
            <tr>
                <th>{{ trans('main.post-year') }}</th>
                <td>{{ $post->year }}</td>
            </tr>
            @endif
 
            @if($post->type)
            <tr>
                <th>{{ trans('main.post-type') }}</th>
                <td>{{ $post->type }}</td>
            </tr>
            @endif
 
            @if($post->auther)
            <tr>
                <th>{{ trans('main.post-auther') }}</th>
                <td>{{ $post->auther }}</td>
            </tr>
            @endif
 
            @if($post->url)
            <tr>
                <th>{{ trans('main.post-url') }}</th>
                <td><a href="{{ $post->url }}">{{ $post->url }}</a></td>
            </tr>
            @endif
{{--   
            @if($post->urlTitle)
            <tr>
                <th>{{ trans('main.post-urlTitle') }}</th>
                <td>{{ $post->urlTitle }}</td>
            </tr>
            @endif

  --}}
        </table>
    </div>
    
    <div class="postContent">
        {!! $post->content !!}
    </div>

    @if($post->files()->count()>0)
    <div class="attached alert alert-success">
            <h4>{{ trans('main.gAttach') }}</h4>
            <hr/>
            <ul style="list-style: none;">
                @foreach($post->files as $file)
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