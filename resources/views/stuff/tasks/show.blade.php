{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }} - {{ $task->title }}</title>

    <!-- facebook meta -->
    @if($task->photos && $task->photos()->count()>0)
        <meta property="og:image" content="{{ url($user->uploads().$task->photos()->first()->name) }}" />

        <link rel="icon" href="{{ url($user->uploads().$task->photos()->first()->name) }}" sizes="32x32" />
        <link rel="icon" href="{{ url($user->uploads().$task->photos()->first()->name) }}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{ url($user->uploads().$task->photos()->first()->name) }}" />
        <meta name="msapplication-TileImage" content="{{ url($user->uploads().$task->photos()->first()->name) }}" />
    @else
        <meta property="og:image" content="{{ url('images/fac_photo.png') }}" />
    @endif

    <meta property="og:title" content="{{ $task->title }}"/> 
    <?php 
        $webIntro = new \voku\Html2Text\Html2Text($task->content); 
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

    <h1 class="postHeader"><a href="{{ url('stuff/subjects/show/'.$task->subject->id) }}">{{ $task->subject->title }}</a> - {{ $task->title }}</h1>
        
    @if($task->photos && $task->photos()->count()>0)
        <img class="postImg img img-fluid img-responsive" src="{{ url($user->uploads().$task->photos()->first()->name) }}" alt="{{ $task->title }}">
    @endif

    <div class="row">
        <div class="col-md-12 post-header-line">
            <span class="glyphicon glyphicon-calendar"></span> {{ $task->created_at }}  | 
            <i class="fas fa-file-signature"></i> {{ $task->answers()->count() }} |
            @if (!Auth::guest() && $isOwner)
                <a href="{{ url('stuff/tasks/'.$task->id.'/edit') }}"><i class="fas fa-edit"></i> {{ trans('main.edit') }} </a>
            @endif 
        </div>
    </div>
    
    <div class="postContent">
        {!! $task->content !!}
    </div>

    

    @if($task->files()->count()>0)
    <div class="attached alert alert-success">
            @if($task->fullDegree) <h5>{{ trans('main.qesFullMark') }}: {{ $task->fullDegree }} {{ trans('main.mark') }}</h5>@endif
            <h4>{{ trans('main.gAttach') }}</h4>
            <hr/>
            <ul style="list-style: none;">
                @foreach($task->files as $file)
                    <li>
                        <label for="delId{{ $file->id }}">
                            <a id="{{ $file->id }}" class="downloadedA" target="_blank" href="{{ url($user->uploads() . $file->name ) }}"><i class="fas fa-book attachIcon"></i>{{ $file->name }} <span title="{{ trans('main.downloaded') }}" class="downloaded">{{ $file->downloaded }} <i class="fas fa-download downloadedI"></i></span> </a>
                        </label>
                    </li>
                @endforeach
            </ul>
        </table>
    </div>
    @endif

    @if (!$isOwner)
    <div id="postAnswers" class="well postAnswers">
        <h3>{{ trans('main.answerLeave') }}</h3>
        <form id='CreateUser' class='leaveAnsForm' method='POST' action='{{ route('answers.store', []) }}' enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="post">
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="subject_id" value="{{ $task->subject->id }}">
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
                <label for="auther">{{ trans('main.answer-facutly_id') }}</label>
                <div class="form-group{{ $errors->has('faculty_id') ? ' has-error' : '' }}">
                    <select  required class="form-control" id="faculty" name="faculty_id" >
                        <option value="">- {{ trans('main.answer-facutly_id') }} -</option>
                        @foreach($faculties as $faculty)
                            <option
                                @if(old('faculty_id') == $faculty->id) 
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
                    
                    @if ($errors->has('faculty_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('faculty_id') }}</strong>
                        </span>
                    @endif    
                </div>
            </div>

            <div class="form-group">
                <label for="department">{{ trans('main.answer-department') }}</label>
                <input value="{{ old('department') }}" type="text" name="department" required class="form-control department" id="department" placeholder="{{ trans('main.answer-department') }}">
                
                @if ($errors->has('department'))
                    <span class="help-block">
                        <strong>{{ $errors->first('department') }}</strong>
                    </span>
                @endif 
            </div>
            
            <div class="form-group">
                <label for="level">{{ trans('main.answer-level') }}</label>
                <input value="{{ old('level') }}" type="text" name="level" required class="form-control level" id="level" placeholder="{{ trans('main.answer-level') }}">

                @if ($errors->has('level'))
                    <span class="help-block">
                        <strong>{{ $errors->first('level') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="email">{{ trans('main.answer-email') }}</label>
                <input value="{{ old('email') }}" type="email" name="email" required class="form-control email" id="email" placeholder="{{ trans('main.answer-email') }}">
                
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <table width="100%">
                <tr>
                    <td width="50%">
                        <div class="form-group">
                            <label for="setId">{{ trans('main.answer-setId') }}</label>
                            <input value="{{ old('setId') }}" type="number" name="setId" class="form-control setId" id="setId" placeholder="{{ trans('main.answer-setId') }}">
                            
                            @if ($errors->has('setId'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('setId') }}</strong>
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="menuId">{{ trans('main.answer-menuId') }}</label>
                            <input value="{{ old('menuId') }}" type="number" name="menuId" class="form-control menuId" id="menuId" placeholder="{{ trans('main.answer-menuId') }}">
                            
                            @if ($errors->has('menuId'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('menuId') }}</strong>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            <div class="form-group">
                <label for="nots">{{ trans('main.answer-nots') }}</label>
                <textarea name="nots" class="form-control" rows="3" placeholder="{{ trans('main.answer-nots') }}">{{ old('nots') }}</textarea>
                
                @if ($errors->has('nots'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nots') }}</strong>
                    </span>
                @endif
            </div>

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

    $_SESSION['answer'] = $answer;

?>

            <div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }} col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <label class="lightP" for="answer">{{ trans('main.requredQues') }}: {{ $sn }} {{ $op }} {{ $fn }} =  </label>
                <input type="number" name="answer" required class="form-control name" id="answer" placeholder="{{ trans('main.writeAns') }}">
                
                @if ($errors->has('answer'))
                    <span class="help-block">
                        <strong>{!! $errors->first('answer') !!}</strong>
                    </span>
                @endif
            </div>

        </form><!--#leaveAnsForm .leaveAnsForm -->

        <label for="file_id">{{ trans('main.answer-file_id') }}
        </label>
        <form id='file_id' class='file_id dropzone' method='POST' action='{{ route('files.store', [])}}' enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='POST'>
            <input type='hidden' name='fileable_id' value='{{ $randomId }}'>
            <input type='hidden' name='uploads_id' value='{{ $user->uploads() }}'>
            <input type='hidden' name='fUserId' value='{{ $user->id }}'>
            <input type='hidden' name='fileable_type' value='App\Answer'>

            <div class="dz-default dz-message"><span>{{ trans('main.gFileMsg') }}</span></div>
            
        </form><!--#file_id .file_id -->

        <button id="submitFormCreate" type="submit" form="CreateUser" class="btn btn-primary">{{ trans('main.gSave') }}</button>

    </div>
    @endif

    @if (Auth::check() && $task->answers()->count()>0 && $isOwner)
    <div class="panel panel-default answersDiv">
    <h3 class="postHeader print-show">{{ $task->subject->title }} - {{ $task->title }}</h3>
    <p class="print-show">@if($task->fullDegree){{ trans('main.qesFullMark') }}: {{ $task->fullDegree }} {{ trans('main.mark') }}@endif</p>
        <div class="panel-heading">{{ trans('main.answeStu') }} <a href="" onclick="printMyPage()" class="btn btn-primary newRecored pull-left"><i class="fas fa-print"></i> {{ trans('main.print') }}</a></div>
        <div class="panel-body panelBodyForm">
            <table id="usersTable" class="answersTable table table-hover table-striped table-bordered order-column dataTable"> 

                <thead>
                    <tr>
                        <th>Id</th>
                        <th>{{ trans('main.delEdit') }}</th>
                        <th style="min-width:120px;">{{ trans('main.answerH-name') }}</th>
                        <th>{{ trans('main.stuDegree') }}</th>
                        <th>{{ trans('main.answerH-department') }}</th>
                        <th>{{ trans('main.answerH-level') }}</th>
                        <th>{{ trans('main.gView') }}</th>
                    </tr>
                </thead>
        
                <tbody>
                    @if($task->answers)
                        <?php $i=0; ?>
                        @foreach($answers as $answer)
                            <tr>
                                <td><?php echo ++$i; ?></td>
                                <td> 
                                    <p>{{ $answer->stuId }}</p>                                   
                                    <a href="#"
                                    onclick="
                                        var result = confirm(' {{ trans('main.delQues') }} : {{ $answer->name }}');
                                        if(result) {
                                            event.preventDefault();
                                            document.getElementById('delete-form{{ $answer->id }}').submit();
                                        }
                    
                                    "
                                    ><i class="fas fa-trash-alt delEdit"></i></a>
        
                                    <form id='delete-form{{ $answer->id }}' class='delete-form' method='post' action='{{ route('answers.destroy', [$answer->id]) }}'>
                                        {{ csrf_field() }}
                                        <input type='hidden' name='_method' value='DELETE'>
                                    </form><!--#delete-form .delete-form -->
                                </td>
                                <td>{{ $answer->name }}
                                    <p class="createdAns"><span class="glyphicon glyphicon-calendar"></span> {{ $answer->created_at }}</p>
                                </td>                                
                                <td>{{ $answer->stuDegree }}</td>
                                <td>{{ $answer->department }}</td>
                                <td>{{ $answer->level }}</td>
                                <td>
                                    @if($answer->files()->count()>0)
                                        <a target="_blank" href="{{ url('stuff/answers/show/'.$answer->id.'?p=tasks') }}"><i class="fas fa-paperclip"></i></a>
                                    @endif
                                    <a href="{{ url('stuff/answers/show/'.$answer->id.'?p=tasks') }}" target="_blank">{{ trans('main.gView') }}</a></td>
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

<!-- Initialize the editor. -->
<script src="https://cdn.tiny.cloud/1/t25js77ya6y844vjfbq2afel4dxhwpcmwmrlnfr3lwzibz47/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>    
    <script>
      tinymce.init({
           selector: "textarea.editor",
           plugins: 'print preview   importcss tinydrive searchreplace autolink autosave save directionality  visualblocks visualchars fullscreen image link media  template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help   charmap quickbars emoticons',
            tinydrive_token_provider: 'URL_TO_YOUR_TOKEN_PROVIDER',
            tinydrive_dropbox_app_key: 'YOUR_DROPBOX_APP_KEY',
            tinydrive_google_drive_key: 'YOUR_GOOGLE_DRIVE_KEY',
            tinydrive_google_drive_client_id: 'YOUR_GOOGLE_DRIVE_CLIENT_ID',
            mobile: {
            plugins: 'print preview   importcss tinydrive searchreplace autolink autosave save directionality  visualblocks visualchars fullscreen image link media  template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help  charmap quickbars emoticons'
            },
            menubar: 'file edit view insert format tools table tc help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange  formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,
            link_list: [
            { title: 'My page 1', value: 'https://www.tiny.cloud' },
            { title: 'My page 2', value: 'http://www.moxiecode.com' }
            ],
            image_list: [
            { title: 'My page 1', value: 'https://www.tiny.cloud' },
            { title: 'My page 2', value: 'http://www.moxiecode.com' }
            ],
            image_class_list: [
            { title: 'None', value: '' },
            { title: 'Some class', value: 'class-name' }
            ],
            importcss_append: true,
            templates: [
              { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
            { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
            { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 300,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            spellchecker_whitelist: ['Ephox', 'Moxiecode'],
            content_style: '.mymention{ color: gray; }',
            contextmenu: 'link image imagetools table configurepermanentpen',
            a11y_advanced_options: true,
            //   skin: useDarkMode ? 'oxide-dark' : 'oxide',
            //   content_css: useDarkMode ? 'dark' : 'default',
            /*
            The following settings require more configuration than shown here.
            For information on configuring the plugin, see:
            https://www.tiny.cloud/docs/plugins/premium/mentions/.
            */
            //   mentions_fetch: mentions_fetch,
            //   mentions_menu_hover: mentions_menu_hover,
            //   mentions_menu_complete: mentions_menu_complete,
            //   mentions_select: mentions_select
        });
    </script>


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
    
  

  
  });
</script>

@endsection