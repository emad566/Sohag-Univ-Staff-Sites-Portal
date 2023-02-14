<?php //$lang="ar"; ?>
@include('common')
{{ setLang() }}
@extends('layouts.frontend')

@section('content')

<div id="contactWrap" class="contactWrap">
    @include("partials.errors")
    @include("partials.success")
    <div class="row" id="check">
        <form id="searchForm" action="{{ url('stuff/getresearchs'.'?p=getresearchs') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <input type='hidden' name='_method' value='GET'>

            <div class="form-group{{ $errors->has('facStu_id') ? ' has-error' : '' }}">
                <label for="facStu_id">{{ trans('main.stu-faculty_id') }} *</label>
                <select required class="form-control" id="facStu_id" name="facStu_id" >
                    <option value="">- {{ trans('main.sfac') }} -</option>
                    @foreach($faculties as $faculty)
                        <?php $select = 1; ?>
                        <option
                             @if(old('facStu_id') == $faculty->id) 
                                selected    <?php $select = 0; ?>
                            @elseif ($fac_id == $faculty->id && $select == 1) 
                                selected
                            @endif 
                        value="{{ $faculty->id }}">
                        @if(app()->getLocale() == "en")
                            @if($faculty->nameEn)
                                {{ $faculty->nameEn }}
                            @else
                               {{ $faculty->name }}
                            @endif
                        @else
                           {{ $faculty->name }}
                        @endif
                        </option>
                    @endforeach
                </select>   
            </div>

            <div class="form-group{{ $errors->has('level_id') ? ' has-error' : '' }}">
                <label for="level">{{ trans('main.answerH-level') }} *</label>
                <select required class="form-control" id="level" name="level_id" >
                    <option value="">- {{ trans('main.answerH-level') }} * -</option>
                    @foreach($levels as $level)
                        <?php $select = 1; ?>
                        <option
                            @if(old('level_id') == $level->id) 
                                selected    <?php $select = 0; ?>
                            @elseif ($level_id == $level->id && $select == 1) 
                                selected
                            @endif
                        value="{{ $level->id }}">
                        @if(app()->getLocale() == "en")
                            @if($level->en)
                                {{ $level->en }}
                            @else
                               {{ $level->ar }}
                            @endif
                        @else
                           {{ $level->ar }}
                        @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="stuId">{{ trans('main.stuId') }} *</label>
                <input value="@if($stuId){{$stuId}}@endif" type="number" name="stuId" required class="form-control stuId"  id="stuId" placeholder="{{ trans('main.stuId') }}">
            </div>
            
            <input type="submit" name="check" class="btn btn-primary searchBtn checkBtn" value="{{ trans('main.search') }}" style="margin-top: 25px;"> 
        </form>

        @if($fac_id)
        <table id="usersTable" class="restable table table-hover table-striped table-bordered order-column dataTable"> 

            <thead>
                <tr>
                    <th>Id</th>
                    <th colspan="2">{{ trans('main.lecturer') }}</th>
                    <th>{{ trans('main.answerH-department') }}</th>
                    <th>{{ trans('main.subjectDropDown') }}</th>
                    <th>{{ trans('main.uploadedOk') }}</th>
                    <th>{{ trans('main.gView') }}</th>
                </tr>
            </thead>
    
            <tbody>
                @if($researchs)
                    <?php $i=0; ?>
                    @foreach($researchs as $research)
                    
                    <tr>
                        <td><?php echo ++$i; ?></td>
                        <td>
                        @if(isset($research->user->photo) && file_exists('./uploads/'.$research->user->id.'/'.$research->user->photo->name))
                        <a target="_blank" href="{{ url('stuff/researchs/show/'.$research->id.'?p=researchs') }}"><img class="circle" src="{{ url('/') }}/{{$research->user->uploads() }}{{ $research->user->photo->name }}" alt="{{ $research->user->name }}"></a>
                        @else
                        <a target="_blank" href="{{ url('stuff/researchs/show/'.$research->id.'?p=researchs') }}"><img class="circle" src="{{ url('/') }}'/images/fac_photo.png" alt="{{ $research->user->name }}"></a>
                        @endif
                        
                        </td>
                        <td><a target="_blank" href="{{ url('stuff/researchs/show/'.$research->id.'?p=researchs') }}">{{$research->user->fullName}}</a></td>
                        <td>{{ $research->department }}</td>
                        <td>{{ $research->subject->title }}</td>
                        <td>
                            @if($research->answers->where('stuId', '=', $stuId)->count() > 0)
                            <i class="far fa-check-circle medico"></i>
                            @else
                            <i class="fas fa-times-circle medico medicofalse"></i>
                            @endif
                        </td>
                        <td><a target="_blank" href="{{ url('stuff/researchs/show/'.$research->id.'?p=researchs') }}" target="_blank">{{ trans('main.gView') }}</a></td>
                    </tr>  
                    @endforeach
                @endif
            </tbody>
        </table>
        @endif

        
    </div>
</div><!-- #loginTowebSite .loginTowebSite -->
@endsection

