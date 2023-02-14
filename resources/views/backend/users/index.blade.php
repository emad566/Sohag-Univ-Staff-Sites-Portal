{{App::setLocale(Auth()->user()->lang)}}
@extends('layouts.backend')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ trans('main.user-indexHead') }} <a href="{{ url('backend/users/create') }}" class="btn btn-primary newRecored pull-left">{{ trans('main.user-AddNewUser') }}</a></div>
    @include("partials.errors")
    @include("partials.success")
    <div class="panel-body panelBodyForm">
        <table id="usersTable" class="table table-hover table-striped table-bordered order-column dataTable"> 

            <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ trans('main.delEdit') }}</th>
                    <th>{{ trans('main.user-name') }}</th>
                    <th>{{ trans('main.user-email') }}</th>
                    <th>{{ trans('main.user-role_id') }}</th>
                    <th>{{ trans('main.user-isActive') }}</th>
                    <th>{{ trans('main.user-userID') }}</th>
                </tr>
            </thead>
    
            <tbody>
                @if($users)
                    <?php $i=0; ?>
                    @foreach($users as $user)
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td>
                                
                                <a href="{{ url('backend/users') }}/{{ $user->id }}/edit"><i class="fas fa-edit delEdit"></i></a>
                                
                                @if($user->id != Auth()->user()->id)
                                <a href="#"
                                onclick="
                                    var result = confirm(' {{ trans('main.delQues') }} : {{ $user->name }}');
                                    if(result) {
                                        event.preventDefault();
                                        document.getElementById('delete-form{{ $user->id }}').submit();
                                    }
                
                                "
                                ><i class="fas fa-trash-alt delEdit"></i></a>
    
                                <form id='delete-form{{ $user->id }}' class='delete-form' method='post' action='{{ route('users.destroy', [$user->id]) }}'>
                                    {{ csrf_field() }}
                                    <input type='hidden' name='_method' value='DELETE'>
                                </form><!--#delete-form .delete-form -->
                                @endif
                            </td>
                            
                            
                            <td><a href="{{ url('/'.$user->name) }}">{{ $user->name }}</a></td>
                            <td>{{ $user->email }}</td>
                            
                            @if(app()->getLocale() == "en")
                                <td>{{ Auth::user()->ruleArr[$user->role->name] }}</td>
                            @else
                                <td>{{ $user->role->name }}</td>
                            @endif
                            
                            <td>{{ $user->isActive == 1 ? trans('main.active') : trans('main.notActive') }}</td>
                            <td>{{ $user->userID }}</td>
                        </tr>  
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
    
@endsection