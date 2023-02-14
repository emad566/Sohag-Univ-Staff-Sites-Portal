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

<div class="panel panel-default suffPanel stuffUserindexPanel">
    <div class="panel-heading">{{ trans('main.user-speInfoHead') }}:  {{ $user->name }}
        <a class="pull-left btn btn-primary" href="{{ url('stuff/user/'.$user->id.'/edit') }}">{{ trans('main.user-speInfoEdit') }}</a>
    </div>
    <div class="panel-body panelBodyForm">
            @include("partials.errors")
            @include("partials.success")
        @if(isset($user->photo))
            <img class="userPhoto circle" src="{{ url($user->uploads()) }}/{{ $user->photo->name }}" alt="{{ $user->name }}">
        @else
            <img class="userPhoto circle" src="{{ url('images/fac_photo.png') }}" alt="{{ $user->name }}">
        @endif

        <table class="table table-hover ">
            <tr>
                <th width="20%">{{ trans('main.user-fullName') }}</th>
                <td>{{ $user->fullName }}</td>
            </tr>

            <tr>
                <th width="20%">{{ trans('main.webLang') }}</th>
                <td>
                    @if($user->lang == "ar")
                        {{ trans('main.lang-ar') }}
                    @elseif($user->lang="en")
                        {{ trans('main.lang-en') }}
                    @endif
                </td>
            </tr>

            <tr>
                <th>{{ trans('main.user-sex') }}</th>
                <td>
                    @if($user->sex == 2)
                        {{ trans('main.user-Male') }}
                    @elseif($user->sex == 1)
                        {{ trans('main.user-female') }}
                    @endif
                </td>
            </tr>
            
            <tr>
                <th>{{ trans('main.user-brithDate') }}</th>
                <td>{{ $user->brithDate }}</td>
            </tr>

            <tr>
                <th>{{ trans('main.user-faculty_id') }}</th>
                <td>{{ @$user->faculty->name }}</td>
            </tr>

            <tr>
                <th>{{ trans('main.user-degree') }}</th>
                <td>{{ $user->getDegree(App::getLocale()) }}</td>
            </tr>

            <tr>
                <th>{{ trans('main.address') }}</th>
                <td>{{ $user->title }}</td>
            </tr>

            <tr>
                <th>{{ trans('main.user-currentPosition') }}</th>
                <td>{{ $user->currentPosition }}</td>
            </tr>
        </table>

        <div class="panel panel-info">
            <div class="panel-heading">{{ trans('main.academy') }}</div>
            <div class="panel-body panelBodyForm">
                <table class="table table-hover ">    
                    <tr>
                        <th width="20%">{{ trans('main.general') }}</th>
                        <td>{{ $user->general }}</td>
                    </tr>
                    
                    <tr>
                        <th>{{ trans('main.special') }}</th>
                        <td>{{ $user->special }}</td>
                    </tr>
                    
                    <tr>
                        <th>{{ trans('main.masterar') }}</th>
                        <td>{{ $user->masterar }}</td>
                    </tr>
                    
                    <tr>
                        <th>{{ trans('main.masteren') }}</th>
                        <td>{{ $user->masteren }}</td>
                    </tr>
                    
                    <tr>
                        <th>{{ trans('main.phdar') }}</th>
                        <td>{{ $user->phdar }}</td>
                    </tr>
                    
                    <tr>
                        <th>{{ trans('main.phden') }}</th>
                        <td>{{ $user->phden }}</td>
                    </tr>
                    
                    <tr>
                        <th>{{ trans('main.positions') }}</th>
                        <td>{{ $user->positions }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="table table-hover ">
            <tr>
                <th width="20%">{{ trans('main.user-webIntro') }}</th>
                <td>{!! $user->webIntro !!}</td>
            </tr>

            <tr>
                <th>{{ trans('main.user-phone') }}</th>
                <td>{{ $user->phone }}</td>
            </tr>

            <tr>
                <th>{{ trans('main.user-fax') }}</th>
                <td>{{ $user->fax }}</td>
            </tr>

            <tr>
                <th>{{ trans('main.user-mobile') }}</th>
                <td>{{ $user->mobile }}</td>
            </tr>

            <tr>
                <th>{{ trans('main.user-fb') }}</th>
                <td><a href="{{ $user->fb }}">{{ $user->fb }}</a></td>
            </tr>

            <tr>
                <th>{{ trans('main.user-twitter') }}</th>
                <td><a href="{{ $user->twitter }}">{{ $user->twitter }}</a></td>
            </tr>

            <tr>
                <th>{{ trans('main.user-yt') }}</th>
                <td><a href="{{ $user->yt }}">{{ $user->yt }}</a></td>
            </tr>

            <tr>
                <th>{{ trans('main.user-linkedIn') }}</th>
                <td><a href="{{ $user->linkedIn }}">{{ $user->linkedIn }}</a></td>
            </tr>

            <tr>
                <th>{{ trans('main.user-googlePlus') }}</th>
                <td><a href="{{ $user->googlePlus }}">{{ $user->googlePlus }}</a></td>
            </tr>
            
            <tr>
                <th>{{ trans('main.user-schooler') }}</th>
                <td><a href="{{ $user->schooler }}">{{ $user->schooler }}</a></td>
            </tr>
            
            <tr>
                <th>EKB ينك المعرفة المصري</th>
                <td><a href="{{ $user->EKP }}">{{ $user->EKP }}</a></td>
            </tr>
            
            <tr>
                <th>Research Gate</th>
                <td><a href="{{ $user->researchGate }}">{{ $user->researchGate }}</a></td>
            </tr>
        </table>

    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->
@endsection