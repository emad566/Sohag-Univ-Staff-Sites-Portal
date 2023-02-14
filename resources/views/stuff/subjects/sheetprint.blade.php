{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }}</title>
@endsection



@section('stuffContent')
<div class="form-group{{ $errors->has('subject_id') ? ' has-error' : '' }}">
    <label for="subject">- {{ trans('main.sCoursePrint') }} -</label>
    <select required class="form-control" id="subject" name="subject_id" >
        <option value="all" 
                @if ($subject_id == "all") 
                    selected
                @endif
        >{{ trans('main.allSub') }}</option>
        @foreach($user->subjects as $subject)
            @if($subject->tasks()->count()>0)
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
    
    @if (Auth::check() && $subject_id != "all" && $subjectPrint->tasks()->count()>0 && $isOwner)
    <div class="panel panel-default answersDiv">
        <div class="panel-heading">{{ trans('main.printSheet') }} <a href="" onclick="printMyPage()" class="btn btn-primary newRecored pull-left"><i class="fas fa-print"></i> {{ trans('main.print') }}</a></div>
        <div class="panel-body panelBodyForm">
            <p>غـ : مقصود بها ان الطالب لم يرفق إجابة لهذا الواجب</p>
            <p>No أمام الدرجة النهائية: مقصود بها انك لم تحدد الدرجة النهائية للواجب حتي الأن أضغط عليها لتحولك لصفحة الواجب لتضع له درجة نهائية</p>
            <p>No أمام اسم الطالب: مقصود بها ان الطالب ارسل الاجابة علي هذا الواجب و انت لم تعطي إجابته درجة حتي الأن إضغط عليها لتحولك لصفحة الاجابة لتصحبحها</p>
            <table id="usersTable" class="table table-hover table-striped table-bordered order-column dataTable"> 

                <thead>
                    <tr>
                        <th>Id</th>
                        <th style="min-width:120px;">{{ trans('main.nameTaskNum') }}</th>
                        <?php $i=1; ?>
                        @foreach($subjectPrint->tasks()->orderBy('created_at', 'ASC')->get() as $task)
                            <th>
                                <span data-toggle="tooltip" data-placement="bottom" title="{{ $task->title }}" class="icoHover">{{ $i++ }}</span>
                            </th>
                        @endforeach
                        <th>{{ trans('main.total') }}</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th></th>
                        <th>{{ trans('main.fullDegree') }}</th>
                        <?php $fullDegrees = 0;?>
                        @foreach($subjectPrint->tasks()->orderBy('created_at', 'ASC')->get() as $task)
                            <td>
                                @if($task->fullDegree)
                                    {{ $task->fullDegree }}
                                    <?php $fullDegrees += $task->fullDegree; ?>
                                @else
                                    <a target="_blank" class="print-hide" href="{{ url('stuff/tasks/'.$task->id.'/edit') }}"><span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.edit') }}" class="icoHover">No</span></a>
                                    <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.edit') }}" class="print-show icoHover">No</span>
                                @endif
                            </td>
                        @endforeach
                        <td>{{ $fullDegrees }}</td>
                    </tr>

                    <?php $i=1;?>
                    @foreach($students as $student)
                        <tr> 
                            <th>{{ $i++ }}</th>
                            <th>{{ $student->name }} <p>{{ $student->stuId }}</p></th>
                            <?php $fullDegrees = 0;?>
                            @foreach($subjectPrint->tasks()->orderBy('created_at', 'ASC')->get() as $task)
                                <td>
                                    {!! $task->stuTaskDegree($task, $student, $fullDegrees) !!}
                                </td>
                            @endforeach
                            <td>{{ $fullDegrees }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
    @endif
</div>

@endsection

@section('scriptsData')
<script>
    $(document).ready(function(){
        $( "#subject" ).change(function() {
            window.location.replace('{{ url('/stuff/sheets/print/') }}/' + $( "#subject" ).val() +'?p=tasks')
        });
    })
</script>

@endsection