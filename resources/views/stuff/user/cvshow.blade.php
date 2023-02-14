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
    <div class="panel-heading">{{ trans('main.user-speInfoHead') }}:  {{ $user->fullName }}
        @if (!Auth::guest() && $isOwner)<a class="pull-left btn btn-primary" href="{{ url('stuff/user/'.$user->id.'/edit') }}">{{ trans('main.user-speInfoEdit') }}</a>@endif
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

            @if($user->fullName)
            <tr>
                <th width="20%">{{ trans('main.user-fullName') }}</th>
                <td>{{ $user->fullName }}</td>
            </tr>
            @endif

            @if($user->email)
            <tr>
                <th width="20%">{{ trans('main.user-email') }}</th>
                <td>{{ $user->email }}</td>
            </tr>
            @endif
 
            @if($user->sex)
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
            @endif
 
            @if($user->brithDate)
            <tr>
                <th>{{ trans('main.user-brithDate') }}</th>
                <td>{{ $user->brithDate }}</td>
            </tr>
            @endif
 
            @if(isset($user->faculty_id) && isset($user->faculty))
            <tr>
                <th>{{ trans('main.user-faculty_id') }}</th>
                <td>@if(app()->getLocale() == "ar") {{ $user->faculty->name }} @else {{ $user->faculty->nameEn }}@endif</td>
            </tr>
            @endif
 
            @if(isset($user->degree))
            <tr>
                <th>{{ trans('main.user-degree') }}</th>
                <td>{{ $user->getDegree(App::getLocale()) }}</td>
            </tr>
            @endif
 
            @if($user->title)
            <tr>
                <th>{{ trans('main.address') }}</th>
                <td>{{ $user->title }}</td>
            </tr>
            @endif
 
            @if($user->currentPosition)
            <tr>
                <th>{{ trans('main.user-currentPosition') }}</th>
                <td>{{ $user->currentPosition }}</td>
            </tr>
            @endif
        </table>

        <div class="panel panel-info">
            <div class="panel-heading">{{ trans('main.academy') }}</div>
            <div class="panel-body panelBodyForm">
                <table class="table table-hover ">

                    @if($user->general)
                    <tr>
                        <th width="20%">{{ trans('main.general') }}</th>
                        <td>{{ $user->general }}</td>
                    </tr>
                    @endif
        
                    @if($user->special)
                    <tr>
                        <th>{{ trans('main.special') }}</th>
                        <td>{{ $user->special }}</td>
                    </tr>
                    @endif
        
                    @if($user->masterar)
                    <tr>
                        <th>{{ trans('main.masterar') }}</th>
                        <td>{{ $user->masterar }}</td>
                    </tr>
                    @endif

                    @if($user->masteren)
                    <tr>
                        <th>{{ trans('main.masteren') }}</th>
                        <td>{{ $user->masteren }}</td>
                    </tr>
                    @endif
        
                    @if($user->phdar)
                    <tr>
                        <th>{{ trans('main.phdar') }}</th>
                        <td>{{ $user->phdar }}</td>
                    </tr>
                    @endif
        
                    @if($user->phden)
                    <tr>
                        <th>{{ trans('main.phden') }}</th>
                        <td>{{ $user->phden }}</td>
                    </tr>
                    @endif
        
                    @if($user->positions)
                    <tr>
                        <th>{{ trans('main.positions') }}</th>
                        <td>{{ $user->positions }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        <table class="table table-hover ">
 
            
            <tr>
                <th width="20%">{{ trans('main.user-phone') }}</th>
                <td>@if($user->phone){{ $user->phone }}@endif</td>
            </tr>
           
 
            @if($user->fax)
            <tr>
                <th>{{ trans('main.user-fax') }}</th>
                <td>{{ $user->fax }}</td>
            </tr>
            @endif

            @if($user->mobile)
            <tr>
                <th>{{ trans('main.user-mobile') }}</th>
                <td>{{ $user->mobile }}</td>
            </tr>
            @endif
 
            @if($user->fb)
            <tr>
                <th>{{ trans('main.user-fb') }}</th>
                <td><a href="{{ $user->fb }}">{{ $user->fb }}</a></td>
            </tr>
            @endif
 
            @if($user->twitter)
            <tr>
                <th>{{ trans('main.user-twitter') }}</th>
                <td><a href="{{ $user->twitter }}">{{ $user->twitter }}</a></td>
            </tr>
            @endif
 
            @if($user->yt)
            <tr>
                <th>{{ trans('main.user-yt') }}</th>
                <td><a href="{{ $user->yt }}">{{ $user->yt }}</a></td>
            </tr>
            @endif
 
            
 
            @if($user->googlePlus)
            <tr>
                <th>{{ trans('main.user-googlePlus') }}</th>
                <td><a href="{{ $user->googlePlus }}">{{ $user->googlePlus }}</a></td>
            </tr>
            @endif
            
            
            <tr>
                <th>{{ trans('main.user-schooler') }}</th>
                <td>@if($user->schooler)<a href="{{ $user->schooler }}">{{ $user->schooler }}</a>@endif</td>
            </tr>
            
            
            <tr>
                <th>{{ trans('main.user-linkedIn') }}</th>
                <td>@if($user->linkedIn)<a href="{{ $user->linkedIn }}">{{ $user->linkedIn }}</a>@endif</td>
            </tr>
            
            
            
            <tr>
                <th>Research Gate</th>
                <td>@if($user->researchGate)<a href="{{ $user->researchGate }}">{{ $user->researchGate }}</a>@endif</td>
            </tr>
            
            
            
            <tr>
                <th>EKP بنك المعرفة المصري</th>
                <td>@if($user->EKP)<a href="{{ $user->EKP }}">{{ $user->EKP }}</a>@endif</td>
            </tr>
            
        </table>

    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->

@if($user->contentCV)
<div class="panel panel-default suffPanel stuffUserindexPanel">
    <div class="panel-heading"> {{ trans('main.userPortofilo') }}
         @if (!Auth::guest() && $isOwner)<a class="pull-left btn btn-primary" href="{{ url('cv/'.$user->id.'/edit') }}">{{ trans('main.cvBtnEdit') }} </a>@endif
    </div>
    <div class="panel-body panelBodyForm">
        {!! $user->contentCV !!}
    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->
@endif

@if($user->files()->count()>0)
<div class="attached alert alert-success">
        <h4>{{ trans('main.fileAttachedH') }}  @if (!Auth::guest() && $isOwner)<a class="pull-left btn btn-primary" href="{{ url('cv/'.$user->id.'/edit') }}">{{ trans('main.attachEdit') }} </a>@endif</h4>
        <hr/>
        <ul style="list-style: none;">
            @foreach($user->files as $file)
                <li>
                    <label for="delId{{ $file->id }}">
                        <a target="_blank" href="{{ url($user->uploads() . $file->name ) }}"><i class="fas fa-book attachIcon"></i>{{ $file->name }}</a>
                    </label>
                </li>
            @endforeach
        </ul>
    </table>
</div>
@endif

@endsection