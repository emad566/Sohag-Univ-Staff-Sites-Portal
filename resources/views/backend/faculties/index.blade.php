{{App::setLocale(Auth()->user()->lang)}}
@extends('layouts.backend')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ trans('main.user-indexHead') }} <a href="{{ url('backend/faculties/create') }}" class="btn btn-primary newRecored pull-left">{{ trans('main.addNewFaculty') }}</a></div>
    @include("partials.errors")
    @include("partials.success")
    <div class="panel-body panelBodyForm">
        <table id="facultiesTable" class="table table-hover table-striped table-bordered order-column dataTable"> 

            <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ trans('main.delEdit') }}</th>
                    <th>{{ trans('main.user-faculty_id') }}</th>
                    <th>{{ trans('main.user-NameEn') }}</th>
                </tr>
            </thead>
    
            <tbody>
                @if($faculties)
                    <?php $i=0; ?>
                    @foreach($faculties as $faculty)
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td>
                                <a href="{{ url('backend/faculties') }}/{{ $faculty->id }}/edit"><i class="fas fa-edit delEdit"></i></a>
                                
                                <a href="#"
                                onclick="
                                    var result = confirm(' {{ trans('main.delQues') }} :@if(app()->getLocale() == "ar") {{ $faculty->name }} @else {{ $faculty->nameEn }}  @endif');
                                    if(result) {
                                        event.preventDefault();
                                        document.getElementById('delete-form{{ $faculty->id }}').submit();
                                    }
                
                                "
                                ><i class="fas fa-trash-alt delEdit"></i></a>
    
                                <form id='delete-form{{ $faculty->id }}' class='delete-form' method='post' action='{{ route('faculties.destroy', [$faculty->id]) }}'>
                                    {{ csrf_field() }}
                                    <input type='hidden' name='_method' value='DELETE'>
                                </form><!--#delete-form .delete-form -->
                            </td>
                            <td>{{ $faculty->name }}</td>
                            <td>{{ $faculty->nameEn }}</td>
                        </tr>  
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
    
@endsection