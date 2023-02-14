{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ trans('main.navOffices') }}</title>

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
<div class="panel panel-default">
    <div class="panel-heading">{{ trans('main.manageOffices') }} 
        @if (!Auth::guest() && $isOwner)<a href="{{ url('stuff/offices') }}" class="btn btn-primary newRecored pull-left   "><i class="fas fa-calendar-plus"></i> {{ trans('main.edit') }} {{ trans('main.manageOffices') }} </a>@endif
    </div>
    @include("partials.errors")
    @include("partials.success")
    <div class="panel-body panelBodyForm">
        <table id="usersTable" class="table table-hover table-striped table-bordered order-column"> 

            <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ trans('main.officeTitle') }}</th>
                    <th>{{ trans('main.fromTo') }}</th>
                    <th>{{ trans('main.days') }}</th>
                </tr>
            </thead>
    
            <tbody>
                @if($user->offices)
                    <?php $i=0; ?>
                    @foreach($user->offices as $office)
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td>{{ $office->office }}</td>
                            <td>{{ $office->startTime }} - {{ $office->endTime }}</td>
                            <td>{{ $office->days }}</td>
                        </tr>  
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div> 
@endsection