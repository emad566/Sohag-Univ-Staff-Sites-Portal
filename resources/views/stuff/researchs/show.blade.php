{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

<?php 
    session_start();
    $fn = rand(rand(1,10),rand(1,10));
    $sn = rand(rand(1,10), rand(1,10));
    $ops = [ '+', '*'];
    $op = rand(0, 1);
    $op = $ops[$op];
    $answer = 0;
    switch($op){
        case "+":
            $answer = $fn + $sn;
            break;
        /* 
        case "-":
            $answer = $fn - $sn;
            break; */

        case "*":
            $answer = $fn * $sn;
            break;
    }

    $_SESSION['resanswer'] = $answer;

?>

@section('headMeta')
    <title>{{ $user->fullName }} - {{ $research->title }}</title>

    <!-- facebook meta -->
    @if($research->photos && $research->photos()->count()>0)
        <meta property="og:image" content="{{ url($user->uploads().$research->photos()->first()->name) }}" />

        <link rel="icon" href="{{ url($user->uploads().$research->photos()->first()->name) }}" sizes="32x32" />
        <link rel="icon" href="{{ url($user->uploads().$research->photos()->first()->name) }}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{ url($user->uploads().$research->photos()->first()->name) }}" />
        <meta name="msapplication-TileImage" content="{{ url($user->uploads().$research->photos()->first()->name) }}" />
    @else
        <meta property="og:image" content="{{ url('images/fac_photo.png') }}" />
    @endif

    <meta property="og:title" content="{{ $research->title }}"/> 
    <?php 
        $webIntro = new \voku\Html2Text\Html2Text($research->content); 
        $webIntro = strip_tags($webIntro->getText())
    ?>
    <meta property="og:description" content="{{ substr($webIntro, 0, 300) . '...' }}" />

@endsection

@section('styles')
    <!-- datatables css-->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    {{-- //dropzon upload files --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">

@endsection

@section('stuffContent')
<div class="col-xs-12 singlePost">
    @include("partials.errors")
    @include("partials.success")

    <h1 class="postHeader"><a href="{{ url('stuff/subjects/show/'.$research->subject->id) }}">{{ $research->subject->title }}</a> - {{ $research->title }}</h1>
        


    <div class="row">
        <div class="col-md-12 post-header-line">
            <span class="glyphicon glyphicon-calendar"></span> {{ $research->created_at }}  | 
            <i class="fas fa-file-signature"></i> {{ $research->answers()->count() }} |
            @if (!Auth::guest() && $isOwner)
                <a href="{{ url('stuff/researchs/'.$research->id.'/edit') }}"><i class="fas fa-edit"></i> {{ trans('main.edit') }} </a>
            @endif 
        </div>
    </div>
    
    <div class="postContent">
        <table class="table table-hover table-striped table-bordered order-column">
            <tr>
                <th width="30%">{{ trans('main.stu-faculty_id') }}</th>
                <td>@if(app()->getLocale() == "ar") {{ $research->faculty->name }} @else {{ $research->faculty->nameEn }}@endif</td>
                
            </tr>
            <tr>
                <th>{{ trans('main.answerH-department') }}</th>
                <td>{{ $research->department }}</td>
            </tr>

            <tr>
                <th>{{ trans('main.answerH-level') }}</th>
                <td>@if(app()->getLocale() == "ar") {{ $research->level->ar }} @else {{ $research->level->en }}@endif</td>
            </tr>

            <tr>
                <th>{{ trans('main.subjectDropDown') }}</th>
                <td>{{ $research->subject->title }}</td>
            </tr>
            <tr>
                <th>{{ trans('main.QesReq') }}</th>
                <td>{!! $research->content !!}</td>
            </tr>
        </table>
        
    </div>

    

    @if($research->files()->count()>0)
    <div class="attached alert alert-success">
            <h4>{{ trans('main.gAttach') }}</h4>
            <hr/>
            <ul style="list-style: none;">
                @foreach($research->files as $file)
                    <li>
                        <label for="delId{{ $file->id }}">
                            <a id="{{ $file->id }}" class="downloadedA" target="_blank" href="{{ url($user->uploads('researchs') . $file->name ) }}"><i class="fas fa-book attachIcon"></i>{{ $file->name }} <span title="{{ trans('main.downloaded') }}" class="downloaded">{{ $file->downloaded }} <i class="fas fa-download downloadedI"></i></span> </a>
                        </label>
                    </li>
                @endforeach
            </ul>
        </table>
    </div>
    @endif

    @if (!$isOwner)
    <div id="postAnswers" class="well postAnswers">
        <h3>{{ trans('main.ResearchLeave') }}</h3>
        <form id='uploadForm' class='leaveAnsForm' method='POST' action='{{ route('resanswers.store', []) }}' enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="post">
            <input type="hidden" name="research_id" value="{{ $research->id }}">
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="faculty_id" value="{{ $user->faculty_id }}">
            <input type="hidden" name="subject_id" value="{{ $research->subject->id }}">
            <input type="hidden" name="facStu_id" value="{{ $research->facStu_id }}">
            <?php $randomId = $user->userFileId($user->id); ?>
            <input type='hidden' name='fileId' value='{{ $randomId }}'>

            <div class="form-group">
                <label for="name">{{ trans('main.answer-name') }}</label>
                <input value="{{ old('name') }}" type="text" name="name" required class="form-control name"  id="name" placeholder="{{ trans('main.answer-name') }}">
                
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif 
            </div>

            <div class="form-group">
                <label for="stuId">{{ trans('main.stuId') }} *</label>
                <input value="{{ old('stuId') }}" type="number" name="stuId" required class="form-control stuId"  id="stuId" placeholder="{{ trans('main.stuId') }}">
                
                @if ($errors->has('stuId'))
                    <span class="help-block">
                        <strong>{{ $errors->first('stuId') }}</strong>
                    </span>
                @endif 
            </div>
            
            <div class="form-group">
                <label for="setId">{{ trans('main.answer-setId') }}</label>
                <input value="{{ old('setId') }}" type="number" name="setId" class="form-control setId" id="setId" placeholder="{{ trans('main.answer-setId') }}">
                
                @if ($errors->has('setId'))
                    <span class="help-block">
                        <strong>{{ $errors->first('setId') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('file_id') ? ' has-error' : '' }}">
                <label for="file_id">{{ trans('main.uploadRessearch') }}</label>
                <input type="file" name="file_id" id="file_id" required accept="application/pdf" class="form-control file_id">
                <!-- Progress bar -->
                <div class="progress">
                    <div class="progress-bar"></div>
                </div>
                @if ($errors->has('file_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('file_id') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group{{ $errors->has('resanswer') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="lightP" for="resanswer">{{ trans('main.requredQues') }}: {{ $sn }} {{ $op }} {{ $fn }} =  </label>
                <input type="number" name="resanswer" required class="form-control name" id="resanswer" placeholder="{{ trans('main.writeAns') }}">
                
                @if ($errors->has('resanswer'))
                    <span class="help-block">
                        <strong>{!! $errors->first('resanswer') !!}</strong>
                    </span>
                @endif
            </div>

            
            <!-- Display upload status -->
            <div id="uploadStatus"></div>
            <a href="" id="refresh" class="btn btn-primary refresh">ارسال بحث أخر</a>
            <button id="uploadsubmit"  type="submit" form="uploadForm" class="btn btn-primary">{{ trans('main.gSave') }}</button>
        </form><!--#leaveAnsForm .leaveAnsForm -->

        

    </div>
    @endif

    @if (Auth::check() && $research->answers()->count()>0 && $isOwner)
    <div class="panel panel-default answersDiv">
    <h3 class="postHeader print-show">{{ $research->subject->title }} - {{ $research->title }}</h3>
    <p class="print-show">@if($research->fullDegree){{ trans('main.qesFullMark') }}: {{ $research->fullDegree }} {{ trans('main.mark') }}@endif</p>
        <div class="panel-heading">{{ trans('main.answeStu') }} <a href="" onclick="printMyPage()" class="btn btn-primary newRecored pull-left"><i class="fas fa-print"></i> {{ trans('main.print') }}</a></div>
        <div class="panel-body panelBodyForm">
            <table id="usersTable" class="answersTable table table-hover table-striped table-bordered order-column dataTable"> 

                <thead>
                    <tr>
                        <th>Id</th>
                        <th>{{ trans('main.delEdit') }}</th>
                        <th style="min-width:120px;">{{ trans('main.answerH-name') }}</th>
                        
                        <th>{{ trans('main.stuId') }}</th>
                        <th>{{ trans('main.answerH-setId') }}</th>
                        <th>{{ trans('main.pass') }}</th>
                        <th>{{ trans('main.gAttach') }}</th>
                    </tr>
                </thead>
        
                <tbody>
                    @if($research->answers)
                        <?php $i=0; ?>
                        @foreach($answers as $answer)
                            <tr>
                                <td><?php echo ++$i; ?></td>
                                <td>                                 
                                    <a href="#"
                                    onclick="
                                        var result = confirm(' {{ trans('main.delQues') }} : {{ $answer->name }}');
                                        if(result) {
                                            event.preventDefault();
                                            document.getElementById('delete-form{{ $answer->id }}').submit();
                                        }
                    
                                    "
                                    ><i class="fas fa-trash-alt delEdit"></i></a>
        
                                    <form id='delete-form{{ $answer->id }}' class='delete-form' method='post' action='{{ route('resanswers.destroy', [$answer->id]) }}'>
                                        {{ csrf_field() }}
                                        <input type='hidden' name='_method' value='DELETE'>
                                    </form><!--#delete-form .delete-form -->
                                </td>
                                <td>{{ $answer->name }}
                                    <p class="createdAns"><span class="glyphicon glyphicon-calendar"></span> {{ $answer->created_at }}</p>
                                </td> 
                                <td>{{ $answer->stuId }}</td>                               
                                <td>{{ $answer->setId }}</td>                               
                                <td>
                                    <div class="form-group">
                                        <input class="checkbox" type="checkbox" uId="{{ $answer->id }}" name="manageMark" @if($answer->pass) checked @endif class="form-control manageMark"  id="manageMark">
                                    </div>
                                </td>
                                <td>
                                    @if($answer->file)
                                        <a target="_blank" href="{{ url($user->uploads('researchs').$answer->file->name) }}"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></a>
                                    @endif
                            </tr>  
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div> 
    @endif
</div>
@endsection

@section('scriptsData')

<!-- Include datatables js -->
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>  
<script src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>  


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>

@if(Auth::guest())
{{-- //dropzon upload files --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.js"></script>
@endif




<script>
$(document).ready(function() {
    
    $( ".close" ).click(function() {
      $('.alert-dismissable').hide();
    });
    //dropzone file types
    Dropzone.options.dropzone = {
        acceptedFiles: 'image/*,application/pdf,.psd',
    }

    $('.dz-hidden-input').attr('accept', 'image/x-png, image/gif, image/jpeg');

    $("#uploadForm").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".progress-bar").width(percentComplete + '%');
                        $(".progress-bar").html(percentComplete+'%');
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: '{{ URL::to("stuff/user/uploadResearch") }}',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $(".progress-bar").width('2%');
                $('#uploadStatus').html('<img class="loading" src="{{url('images/loading.gif')}}"/>');
            },
            error:function(){
                $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
            },
            success: function(resp){
                if (resp == 'تم الحفظ بنجاح، شكرا لك.'){
                    $('#uploadsubmit').remove()
                    $('#refresh').css('display', 'block')
                }
                $('#uploadStatus').html(resp);
            }
        });
    })

    // File type validation
    $("#file_id").change(function(){
        var allowedTypes = ['application/pdf'];
        var file = this.files[0];
        var fileType = file.type;
        if(!allowedTypes.includes(fileType)){
            alert('Please select a valid file (PDF).');
            $("#fileInput").val('');
            return false;
        }
    });

    $('.manageMark').change(function(){
        $checked = ($(this).is(":checked")) ? 1 : 0 ;
        $id = $(this).attr('uId');
        $raceMark = $(this).val();
        if (!isNaN($id)){
            $.ajax({
                type:'get',
                url:'{{ URL::to("stuff/user/respass") }}',
                data: { 'id': $id, 'raceMark':  $checked},
                success: function(data){
                    console.log(data)
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