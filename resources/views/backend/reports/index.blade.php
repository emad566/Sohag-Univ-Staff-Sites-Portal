{{App::setLocale(Auth()->user()->lang)}}
@extends('layouts.backend')

@section('content')
<div id="reports" class="panel panel-default">
    <div class="panel-heading">{{ trans('main.reports') }} <a href="" onclick="printMyPage()" class="btn btn-primary newRecored pull-left"><i class="fas fa-print"></i> {{ trans('main.print') }}</a></div>

    @include("partials.errors")
    @include("partials.success")
    <div class="panel-body panelBodyForm">

        <div class="form-group{{ $errors->has('faculty_id') ? ' has-error' : '' }}">
            <label for="faculty_id">- {{ trans('main.sfac') }} -</label>
            <select required class="form-control" id="faculty_id" name="faculty_id" >
                <option value="all" 
                        @if ($fac_id == "all") 
                            selected
                        @endif
                >{{ trans('main.allfac') }}</option>
                @foreach($faculties as $faculty)
                    <option
                            @if ($faculty->id == $fac_id) 
                                selected
                            @endif 
                            value="{{ $faculty->id }}"
                    >{{ $faculty->name }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('faculty_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('faculty_id') }}</strong>
                </span>
            @endif    
        </div>
        @if($fac_id == "all")
            <p class="reportDescription">تقرير يعرض عدد السادة أعضاء و معاوني أعضاء هيئة التدريس الذين أكملوا مقدار الاكتمال المطلوب لمواقعهم في كل كلية علي حده.</p>
        @else
        <p class="reportDescription">تقرير يعرض عدد السادة أعضاء و معاوني أعضاء هيئة التدريس الذين أكملوا مقدار الاكتمال المطلوب لمواقعهم في كلية <span class="reportSpan">{{ $fac_name }}</span></p>
        @endif
        <table id="reportTable" class="table table-hover table-striped table-bordered order-column dataTable"> 
        @if($fac_id == "all")
            <thead>
                <tr>
                    <th>م</th>
                    <th>{{ trans('main.answerH-facutly_id') }}</th>
                    <th>{{ trans('main.helper') }}</th>
                    <th>{{ trans('main.staff') }}</th>
                    <th>{{ trans('main.allStaff') }}</th>
                    <td><i title="{!! trans('main.fileTimes') !!}" class='fas fa-cloud-upload-alt'></i></td>
                    <td><i title="{!! trans('main.downloadedTimes') !!}" class='fas fa-cloud-download-alt'></i></td>
                </tr>
            </thead>
            <tbody>
                {!! $tr !!}
            </tbody>
        
        @else
            <thead>
                <tr>
                    <th>م</th>
                    <th>{{ trans('main.user-name') }}</th>
                    <th width="25%">{{ trans('main.user-userID') }}</th>
                    <th>{{ trans('main.webLink') }}</th>
                    <th>الوظية</th>
                    <th>{{ trans('main.precentVal') }}</th>
                </tr>
            </thead>
            <tbody id="reportTable">
            <?php $i = 1; ?>
            @if($usersGT50 && $usersGT50->count()>0)
                @foreach($usersGT50 as $userM)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $userM->fullName }}</td>
                    <td>{{ $userM->userID }}</td>
                    <td><a class="print-hide" target='_blank' href="http://staffsites.sohag-univ.edu.eg/{{ $userM->name }}">http://staffsites.sohag-univ.edu.eg/{{ $userM->name }}</a><span class="print-show">http://staffsites.sohag-univ.edu.eg/{{ $userM->name }}</span></td>
                    <td>{{ $userM->getDegree('ar') }}</td>
                    <td>{{ $userM->mostContent }}</td>
                </tr>  
                @endforeach
            @endif

            @if($usersGT30 && $usersGT30->count()>0)
                @foreach($usersGT30 as $userM)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $userM->fullName }}</td>
                    <td>{{ $userM->userID }}</td>
                    <td><a class="print-hide" target='_blank' href="http://staffsites.sohag-univ.edu.eg/{{ $userM->name }}">http://staffsites.sohag-univ.edu.eg/{{ $userM->name }}</a><span class="print-show">http://staffsites.sohag-univ.edu.eg/{{ $userM->name }}</span></td>
                    <td>{{ $userM->getDegree('ar') }}</td>
                    <td>{{ $userM->mostContent }}</td>
                </tr>  
                @endforeach
            @endif

        @endif
            </tbody>
        </table>
    </div>
</div>
    
@endsection

@section('scriptsData')
<script>
    $(document).ready(function(){
        $( "#faculty_id" ).change(function() {
            window.location.replace('{{ url('/stuff/user/report') }}/' + $( "#faculty_id" ).val() +'?p=reports')
        });
    })
</script>

@endsection