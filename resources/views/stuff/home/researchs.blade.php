{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ trans('main.navresearchs') }}</title>

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
@if($user->researchs()->count() > 0)
    <div role="tabpanel" class="tab-pane active" id="researchs">
    <div class="form-group{{ $errors->has('subject_id') ? ' has-error' : '' }}">
        <label for="subject">- {{ trans('main.sResearchs') }} -</label>
        <select required class="form-control" id="subject" name="subject_id" >
            <option value="all" 
                    @if ($subject_id == "all") 
                        selected
                    @endif
            >{{ trans('main.allSub') }}</option>
            @foreach($user->subjects as $subject)
                @if($subject->researchs()->count()>0)
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
        @foreach($researchs as $research)
            <div class="col-md-12 blogPostH">
                <div class="row">
                    <div class="col-md-12 post">
                        <div class="row">
                            <div class="col-md-12 post-header">
                                <h4>
                                    <strong><a href="{{ url('/stuff/researchs/show/'.$research->id.'?p=researchs') }}" class="post-title">{{ $research->title }}</a></strong>
                                </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 post-header-line">
                                <span>{{ trans('main.answerH-facutly_id') }}: </span>
                                <span>@if(app()->getLocale() == "ar") {{ $research->faculty->name }} @else {{ $research->faculty->nameEn }}@endif</span> - 
                                <span>{{ trans('main.answerH-level') }}: </span>
                                <span>@if(app()->getLocale() == "ar") {{ $research->level->ar }} @else {{ $research->level->en }}@endif</span> - 
                                <span class="glyphicon glyphicon-calendar"></span> {{ $research->created_at }} | 
                                <i class="fas fa-file-signature"></i> {{ $research->answers()->count() }} | 
                                <a href="{{ url('/stuff/subjects/show/'.$research->subject->id.'?p=subjects') }}"> <i class="fas fa-book"></i> {{ $research->subject->title }}</a>
                            </div>
                        </div>
                        <div class="row post-content">
                            
                            <div class="col-md-9 postText">
                                {{ $user->stripText($research->content, 300) }}
                                <a class="btn btn-read-more" href="{{ url('stuff/researchs/show/'.$research->id."?p=researchs") }}">{{ trans('main.readMore') }}</a></p>
                            </div>
                        </div>
                    </div>
                </div><!-- EndPostRow -->
            </div>
        @endforeach
    </div

    ><div class="clearfix"></div>
    <div class="text-center">
            {!! $researchs->appends(['p' => Request::get('p')])->render() !!}
    </div>
    
@endif
@endsection

@section('scriptsData')
<script>
    $(document).ready(function(){
        $( "#subject" ).change(function() {
            window.location.replace('{{ url('/stuff/home/researchs/'.$user->id) }}/' + $( "#subject" ).val() +'?p=researchs')
        });
    })
</script>

@endsection