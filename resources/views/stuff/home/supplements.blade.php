{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ trans('main.navSupplements') }}</title>

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
@if($user->supplements()->count() > 0)
    <div role="tabpanel" class="tab-pane active" id="supplements">
    <div class="form-group{{ $errors->has('subject_id') ? ' has-error' : '' }}">
        <label for="subject">- {{ trans('main.sSupplements') }} -</label>
        <select required class="form-control" id="subject" name="subject_id" >
            <option value="all" 
                    @if ($subject_id == "all") 
                        selected
                    @endif
            >{{ trans('main.allSub') }}</option>
            @foreach($user->subjects as $subject)
                @if($subject->supplements()->count()>0)
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
        <div class="col-md-12 blogPostH postItems">
            <div class="row">
                @foreach($supplements as $supplement)
                    <div class="col-xs-12 col-md-4">
                        <div class="post">
                            <h4 class="col-md-12 post-header">
                                <strong><a href="{{ url('/stuff/supplements/show/'.$supplement->id) }}?p=supplements" class="post-title">{{ $user->stripText($supplement->title, 40) }}</a></strong>
                            </h4>
                            <div class="post-header-line">
                                <span class="glyphicon glyphicon-calendar"></span> {{ $supplement->created_at }} 
                                <a href="{{ url('/stuff/subjects/show/'.$supplement->subject->id) }}?p=subjects"> <i class="fas fa-book"></i> {{ $supplement->subject->title }}</a>
                            </div>
                            <div class="post-content post-thumb">
                                @if(app()->getLocale() == "en")
                                    {{ $user->stripText($supplement->content, 170) }}
                                @else
                                    {{ $user->stripText($supplement->content, 230) }}
                                @endif     
                                <a class="btn btn-read-more" href="{{ url('stuff/supplements/show/'.$supplement->id."?p=supplements") }}">{{ trans('main.readMore') }}</a></p>
                                
                            </div>
                        </div>
                    </div>
                @endforeach
            </div><!-- EndPostRow -->
        </div>
    </div

    ><div class="clearfix"></div>
    <div class="text-center">
            {!! $supplements->appends(['p' => Request::get('p')])->render() !!}
    </div>
    
@endif
@endsection

@section('scriptsData')
<script>
    $(document).ready(function(){
        $( "#subject" ).change(function() {
            window.location.replace('{{ url('/stuff/home/supplements/'.$user->id) }}/' + $( "#subject" ).val() + '?p=supplements')
        });
    })
</script>

@endsection