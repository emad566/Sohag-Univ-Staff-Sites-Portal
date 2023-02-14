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
<div class="panel panel-default">
    <div class="panel-heading">{{ trans('main.manageSubjects') }} <a href="{{ url('stuff/subjects/create') }}" class="btn btn-primary newRecored pull-left"><i class="fas fa-calendar-plus"></i> {{ trans('main.addNew') }} </a></div>
    @include("partials.errors")
    @include("partials.success")
    <div class="panel-body panelBodyForm">
       
        @if($user->subjects()->count()<1)
            <div id="" class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>
                    <li>{{ trans('main.EmptyRecordsHint') }}</li>
                </strong>
            </div><!--#  .alert alert-dismissable alert-sucess -->
        @else

        <table id="usersTable" class="table table-hover table-striped table-bordered order-column dataTable"> 

            <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ trans('main.delEdit') }}</th>
                    <th>{{ trans('main.gTitle') }}</th>
                    <th>{{ trans('main.gView') }}</th>
                </tr>
            </thead>
    
            <tbody>
                @if($user->subjects)
                    <?php $i=0; ?>
                    @foreach($user->subjects as $subject)
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td>
                                <a href="{{ url('stuff/subjects') }}/{{ $subject->id }}/edit"><i class="fas fa-edit delEdit"></i></a>
                                
                                <a href="#"
                                onclick="
                                    var result = confirm(' {{ trans('main.delQues') }} : {{ $subject->title }}');
                                    if(result) {
                                        event.preventDefault();
                                        document.getElementById('delete-form{{ $subject->id }}').submit();
                                    }
                
                                "
                                ><i class="fas fa-trash-alt delEdit"></i></a>
    
                                <form id='delete-form{{ $subject->id }}' class='delete-form' method='post' action='{{ route('subjects.destroy', [$subject->id]) }}'>
                                    {{ csrf_field() }}
                                    <input type='hidden' name='_method' value='DELETE'>
                                </form><!--#delete-form .delete-form -->
                            </td>
                            
                            <td><a href="{{ url('stuff/subjects') }}/{{ $subject->id  }}">{{ $subject->title }}</a></td>
                            <td><a href="{{ url('stuff/subjects/'.$subject->id.'?p=subjects') }}" target="_blank">{{ trans('main.gView') }}</a></td>
                        </tr>  
                    @endforeach
                @endif
            </tbody>
        </table>
        @endif
    </div>
</div> 
@endsection