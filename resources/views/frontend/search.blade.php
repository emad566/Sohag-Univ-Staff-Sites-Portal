
@include('common')
{{ setLang() }}

@extends('layouts.frontend')

@section('headMeta')
    <title>{{ trans('main.webHeaderTitle') }}</title>

    <!-- facebook meta -->

    <meta property="og:image" content="{{ url('images/soLogo.png') }}" />

    <link rel="icon" href="{{ url('images/soLogo.png') }}" sizes="32x32" />
    <link rel="icon" href="{{ url('images/soLogo.png') }}" sizes="192x192" />
    <link rel="apple-touch-icon-precomposed" href="{{ url('images/soLogo.png') }}" />
    <meta name="msapplication-TileImage" content="{{ url('images/soLogo.png') }}" />


    <meta property="og:title" content="{{ trans('main.webHeaderTitle') }}"/> 
    <meta property="og:description" content="{{ trans('main.webHeaderTitle') }}" />

@endsection

@section('content')
<div id="SearchStuff" class="row SearchStuff">
    <form id="searchForm" action="" method="post">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='get'>

        <div class="col-xs-12  col-md-4">
            <div class="form-group">
                <label for="searchName">{{ trans('main.searchByName') }}</label>
                <input type="text" class="form-control name" id="searchName" name="name" placeholder="{{ trans('main.searchByName') }}">
            </div>
        </div>

        <div class="col-xs-12  col-md-4">
            <div class="form-group{{ $errors->has('degree') ? ' has-error' : '' }}">
                    
                <label for="degree">{{ trans('main.user-degree') }}</label>
                
                <div class="button-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">{{ trans('main.user-degree') }}</span> <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach($degrees as $degree)
                            <?php $degreeValue = (App::getLocale() == "ar")? $degree->ar : $degree->en ; ?>
                            <li><a class="small" data-value="{{ $degreeValue }}" tabIndex="-1"><input name="degree[]" class="degree" value="{{ $degree->id }}" type="checkbox"/>&nbsp;{{ $degreeValue }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xs-12  col-md-4">
            <div class="form-group">
                <label for="searchIn">{{ trans('main.searchByFac') }}</label>
                <select name="searchIn" id="searchIn" class="form-control searchIn">
                    <option value="all">- {{ trans('main.facAll') }} -</option> 
                    @foreach($faculties as $faculty)
                        <?php 
                            if(app()->getLocale() == "ar") 
                                $facName = $faculty->name;
                            else 
                                $facName = $faculty->nameEn;
                        ?>
                        <option
                            @if(old('faculty_id') == $faculty->id) 
                                selected
                            @endif 
                        value="{{ $faculty->id }}">{{ $facName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-xs-12  col-md-4">
            <div class="form-group">
                <label for="precentVal">{{ trans('main.precentVal') }}</label>
                <select class="form-control operator" name="operator" id="operator">
                    <option value=">">{{ trans('main.gt') }}</option>
                    <option value="=">{{ trans('main.eq') }}</option>
                    <option value="<">{{ trans('main.lt') }}</option>
                </select>
                <input type="number" min="0" max="100" class="form-control name" id="precentVal" name="precentVal" placeholder="{{ trans('main.precentVal') }}">
            </div>
        </div>

        <div class="col-xs-12  col-md-4">
            <div class="form-group">
                <label for="orderBy">{{ trans('main.orderBy') }}</label>
                <select name="orderBy" id="orderBy" class="form-control orderBy">
                    <option value="mostContent"> {{ trans('main.webMostContent') }} </option>
                    <option value="updated_at"> {{ trans('main.recentlyVisited') }} </option> 
                    <option value="mostView"> {{ trans('main.webMostVisited') }} </option> 
                    <option value="countTime"> {{ trans('main.webMostActive') }} </option> 
                    <option value="name"> {{ trans('main.user-name') }} </option>  
                    <option value="degree"> {{ trans('main.user-degree') }} </option>  
                    <!-- <option value="lang"> {{-- trans('main.webLang') --}} </option>  -->
                </select>
            </div>
        </div>

        <div class="col-xs-12  col-md-4">
            <div class="form-group">
                <label for="orderType">{{ trans('main.orderType') }}</label>
                <select name="orderType" id="orderType" class="form-control orderBy">
                    <option value="DESC"> {{ trans('main.DESC') }} </option> 
                    <option value="ASC"> {{ trans('main.ASC') }} </option>
                </select>
            </div>
        </div>
        <button type="button" id="runSearch" class="btn btn-primary searchBtn pull-left">
            <span class="glyphicon glyphicon-search"></span> {{ trans('main.search') }}
        </button>
    </form>
</div><!--.row .SearchStuff-->

<div id="stuffSearchItem" class="row stuffSearchItem">
    <img id="searchLoading" class="searchLoading" src="{{ url('sloading.gif') }}" alt="Loading">
    <div id="ajaxdata">

    <div class="text-center">
            {!! $users->appends(['p' => Request::get('p')])->render() !!}
    </div>
    @foreach($users as $user)
        <?php 
            $width = $user->mostContent / $maxValue * 100;
        ?>
        <div class="col-xs-12  col-md-4 blockItemMain">
            <div class="blockItem">
                <a href="{{ url('/'.$user->name) }}">
                    @if(isset($user->photo))
                        <img class="circle" src="{{ url($user->uploads() . $user->photo->name) }}" alt="{{ $user->name }}">
                    @else
                        <img class="circle" src="{{ url('images/fac_photo.png') }}" alt="{{ $user->name }}">
                    @endif
                </a>
                <div class="blockItemData">
                    <p><span class="mostViewCount searchLastUpdated"> {{ $user->updated_at }} <span class="glyphicon glyphicon-calendar"></span></span></p>
                    <h3><a href="{{ url('/'.$user->name) }}">{{ $user->fullName }}</a></h3>
                    <p>{{ $user->getDegree($user->lang) }} - 
                        @if(isset($user->faculty_id) && isset($user->faculty))
                            @if($user->lang == "ar")
                                {{ $user->faculty->name }} 
                            @else 
                                {{ $user->faculty->nameEn }}
                            @endif
                        @endif
                    </p>
                </div><!-- .blockItemData -->
                <span class="mostViewCount"> 
                <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.manageSubjects') }}" class="icoHover">{{ $user->subjects()->count() }}<i  class="fas fa-book"></i></span> 
                      <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.manageSupplements') }}" class="icoHover">{{ $user->supplements()->count() }}<i class="fas fa-paperclip"></i></span> 
                      <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.manageTasks') }}" class="icoHover">{{ $user->tasks()->count() }}<i class="fas fa-tasks"></i></span> 
                      <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.managePosts') }}" class="icoHover">{{ $user->posts()->count() }}<i class="fas fa-clipboard"></i></span> 
                      <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.manageOffices') }}" class="icoHover">{{ $user->offices()->count() }}<i class="fas fa-clock"></i></span> 
                      <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.manageAdvs') }}" class="icoHover">{{ $user->advs()->count() }}<i class="fas fa-newspaper"></i></span> 
                      <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.VisitNum') }}" class="icoHover">{{ $user->mostView }}<i class="fas fa-eye"></i></span> 
                      <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.countTime') }}" class="icoHover">{{ $user->countTime }}<i class="fas fa-plug"></i></span> 

                </span>
                <div class="userProgress">
                    <div <i data-toggle="tooltip" data-placement="top" title="{{ trans('main.contentComplete') }}" class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $user->mostContent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $width }}%;">
                            {{ $user->mostContent }}
                        </div>
                    </div>
                </div>
            </div><!-- .blockItem -->
        </div>
    @endforeach
    </div><!-- #ajaxdata -->
</div><!--.row .stuffSearchItem-->

<div class="clearfix"></div>
<div class="text-center">
        {!! $users->appends(['p' => Request::get('p')])->render() !!}
</div>

@endsection

@section('scriptsData')
<script>
    <?php 
        if(app()->getLocale() == "ar") 
            $getLocale = 'ar';
        else 
            $getLocale = 'en';
    ?>
    $(document).ready(function(){
        function search(){
            $("#searchLoading").show();
            
            var degree = [];
            $(".degree:checked").each(function() {
                degree.push($(this).val());
            });
            
            $.ajax({
                type:'get',
                url:'{{ URL::to("stuff/search/find") }}',
                data: { 
                        'fac': $( "#searchIn" ).val(), 
                        'name': $( "#searchName" ).val(), 

                        'noserchFound': '{{ trans('main.noserchFound') }}',
                        'stafffounds':'{{ trans('main.stafffounds') }}',
                        'member':'{{ trans('main.member') }}',
                        'inside' : '{{ trans('main.inside') }}',
                        'hasName' : '{{ trans('main.hasName') }}',
                        'facAll' : '{{ trans('main.facAll') }}',
                        'applyedSearch' : '{{ trans('main.applyedSearch') }}',
                        'precentVal' : $( "#precentVal" ).val(),
                        'orderBy' : $( "#orderBy" ).val(),
                        'orderType' : $( "#orderType" ).val(),
                        'operator' : $( "#operator" ).val(),
                        'degree' : degree,
                        'getLocale': '<?php echo $getLocale; ?>'
                    },
                success: function(data){
                    //console.log(data);  
                    $('#ajaxdata').html(data);
                    $("#searchLoading").hide();
                },
				error: function(jqXHR, textStatus, errorThrown) {
                    $('#searchLoading').html('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
					console.log('jqXHR:');
					console.log(jqXHR);
					console.log('textStatus:');
					console.log(textStatus);
					console.log('errorThrown:');
                    console.log(errorThrown);
                    
					if (confirm($( "#orderBy" ).val() + " - " +  $( "#orderType" ).val() + " - " + $( "#precentVal" ).val() + $( "#searchName" ).val() + $( "#searchIn" ).val() +  "لم يتم العثور علي نتائج لبحثك!، حاول مرة أخري.")) {
                        location.reload();
                    } else {
                        location.reload();
                    }					
				},
            });
        }
        $( "#searchIn" ).change(function() {
            search();
        });

        $( "#searchName" ).change(function() {
            search();
        });

        $( "#orderBy" ).change(function() {
            search();
        });

        $( "#precentVal" ).change   (function() {
            //alert($( "#precentVal" ).val());
            search();
        });

        $( "#orderType" ).change(function() {
            search();
        });

        $( "#degree" ).change(function() {
            search();
        });

        $( "#operator" ).change(function() {
            search();
        });

        $( "#runSearch" ).click(function() {
            search();
        });



    })
</script>

@endsection