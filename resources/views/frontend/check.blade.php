<?php //$lang="ar"; ?>
@include('common')
{{ setLang() }}
@extends('layouts.frontend')

@section('content')
<style>
    .yesPrint{
        display:none;
    }
     @media print {
        .yesPrint{
            display:block;
        }
        .noPrint{
            display:none;
        }
        .printTable a{
            font-size:8px !important;
        }
    }
</style>
<div id="contactWrap" class="contactWrap">
    @include("partials.errors")
    @include("partials.success")
    <div class="row" id="check">
        <form id="searchForm" action="{{ url('stuff/check/checkfind') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <input type='hidden' name='_method' value='POST'>

            <div class="col-xs-12  col-md-4">
                <div class="form-group">
                    <label for="userID">{{ trans('main.stuId') }}</label>
                    <input type="number" class="form-control userID" id="userID" value="@if(isset($user)){{ $user->userID }}@endif" name="userID" placeholder="{{ trans('main.stuId') }}">
                </div>
            </div>
            
            <input type="submit" name="check" class="btn btn-primary searchBtn checkBtn" value="{{ trans('main.check') }}" style="margin-top: 25px;"> 
        </form>
        @if(isset($user))
        <a href="" onclick="printMyPage()" class="btn btn-primary newRecored pull-left"><i class="fas fa-print"></i> {{ trans('main.print') }}</a>
        <table class="table table-hover printTable">
            
            <tr>
                <th width="20%">{{ trans('main.stuId') }}</th>
                <td>{{ $user->userID }}</td>
            </tr>
            
            @if($user->fullName)
            <tr>
                <th width="20%">{{ trans('main.user-fullName') }}</th>
                <td>
                    <a target="_blanck"  class="noPrint"  href="{{url('/')}}/{{$user->name}}">{{ $user->fullName }}</a>
                    <p style="text-align:center;" class="yesPrint">{{ $user->fullName }}</p>
                </td>
            </tr>
            @endif
            
            @if(isset($user->faculty_id) && isset($user->faculty))
            <tr>
                <th>{{ trans('main.user-faculty_id') }}</th>
                <td>@if(app()->getLocale() == "ar") {{ $user->faculty->name }} @else {{ $user->faculty->nameEn }}@endif</td>
            </tr>
            @endif
        
            @if($user->email)
            <tr>
                <th width="20%">(Email-us) - {{ trans('main.user-email') }}</th>
                <td>{{ $user->email }}</td>
            </tr>
            @endif
            
            
            <tr>
                <th>Linked In</th>
                <td>
                    @if($user->linkedIn)
                        <a target="_blanck"  class="noPrint" href="{{ $user->linkedIn }}">{{ $user->linkedIn }}</a>
                        <a class="yesPrint">{{ $user->linkedIn }}</a>
                    @endif    
                </td>
                    
            </tr>
            
            <tr>
                <th>{{ trans('main.user-schooler') }}</th>
                <td>
                    @if($user->schooler)
                        <a target="_blanck"  class="noPrint"  href="{{ $user->schooler }}">{{ $user->schooler }}</a>
                        <a class="yesPrint">{{ $user->schooler }}</a>
                    @endif
                </td>
            </tr>
            
            <tr>
                <th>Research Gate</th>
                <td>
                    @if($user->researchGate)
                        <a target="_blanck"  class="noPrint"  href="{{ $user->researchGate }}">{{ $user->researchGate }}</a>
                        <a class="yesPrint">{{ $user->researchGate }}</a>
                    @endif
                </td>
            </tr>
            
            <tr>
                <th>EKB بنك المعرفة المصري</th>
                <td>
                    @if($user->EKP)
                        <a target="_blanck" class="noPrint" href="{{ $user->EKP }}">{{ $user->EKP }}</a>
                        <a class="yesPrint">{{ $user->EKP }}</a>
                    @endif
                </td>
            </tr>
            
        </table>
        @endif
    </div>
</div><!-- #loginTowebSite .loginTowebSite -->
@endsection

@section('scriptsData')
    
    <script>
        $(document).ready(function(){
            
        })
    </script>
@endsection
