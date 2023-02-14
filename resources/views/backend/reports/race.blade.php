{{App::setLocale(Auth()->user()->lang)}}
@extends('layouts.backend')

@section('content')
<div id="reports" class="panel panel-default">
    <div class="panel-heading">{{ trans('main.reports') }} <a href="" onclick="printMyPage()" class="btn btn-primary newRecored pull-left"><i class="fas fa-print"></i> {{ trans('main.print') }}</a></div>

    @include("partials.errors")
    @include("partials.success")
    <div class="panel-body panelBodyForm">
        <p>تقرير بالساده الأعضاء المشتركين في المسابقة و مقدار تقاطهم في المسابقة</p>
        <form id='marksForm' class='marksForm' method='post' action='{{ url('/') }}' enctype='multipart/form-data'>
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='GET'>
            
            <label for="type">- اختر فئة -</label>
            <select required class="form-control" id="type" name="type" >
                <option value="all">الكل</option>
                <option value="345" @if($type == 345) selected @endif>أعضاء هيئة التدريس</option>
                <option value="12" @if($type == 12) selected @endif>الهيئة المعاونة</option>
            </select>
            <br>

            <table id="reportTable" class="table table-hover table-striped table-bordered order-column dataTable"> 
                <thead>
                    <tr>
                        <th>م</th>
                        <th>الاسم</th>
                        <th>الرقم القومي</th>
                        <th>الاكتمال</th>
                        <th>النشاط</th>
                        <th>الزيارات</th>
                        <th>الاعجاب</th>
                        <th>الادارة</th>
                        <th>النهائية</th>
                    </tr>
                </thead>

                <tbody id="reportTable">
                <?php $i = 1; ?>
                @foreach($races as $race)
                    <?php $race->getCountTime = false; ?>
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td><a target="_blank" href="{{ url('/'.$race->name) }}">{{ $race->fullName }}</a></td>
                        <td>{{ $race->userID }}</td>
                        <td>{{ $race->mostContent }} <span class="autoMark">{{ $race->markMostContent() }}</span></td>
                        <td>{{ intval($race->countTime/60) }}<span class="autoMark">{{ $race->markMostActive() }}</span></td>
                        <td>{{ $race->visitors()->count() }}<span class="autoMark">{{ $race->markMostView() }}</span></td>
                        <td>{{ $race->votes()->where('val', '=', '1')->count() }}<span class="autoMark">{{ $race->markMostLikes() }}</span></td>
                        <td>
                            <div class="form-group">
                                <input value="{{ $race->raceMark }}" type="number" uId="{{ $race->id }}" name="manageMark"  class="form-control manageMark"  id="manageMark" placeholder="الدرجة">
                            </div>
                        </td>
                        <td><span id="fMark{{ $race->id }}">{{ $race->getFullMark() }}</span></td>
                    </tr>  
                @endforeach
                </tbody>
            </table>
        
        </form>
    </div>
</div>
    
@endsection

@section('scriptsData')
<script>
    $(document).ready(function(){

        $( "#type" ).change(function() {
            window.location.replace('{{ url('stuff/user/racereport') }}/' + $( "#type" ).val() +'?p=racereport')
        });

        $('.manageMark').change(function(){
            $id = $(this).attr('uId');
            $raceMark = $(this).val();
            if (!isNaN($id)){
                $.ajax({
                    type:'get',
                    url:'{{ URL::to("stuff/user/setMark") }}',
                    data: { 'id': $(this).attr('uId'), 'raceMark': $(this).val()},
                    success: function(data){
                        $("#fMark"+$id).html(data);
                        $("#fMark"+$id).parent().css({
                            'background-color' : '#f90'
                        });
                    }
                });
            }
        });
    });
</script>
@endsection