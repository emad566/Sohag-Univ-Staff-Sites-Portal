<?php
// echo "الرجاء الإنتظار جاري تحديث الموقع الرجاء محاولة الإتصال لاحقا بعد 10 دقائق";
// return;
    //if($user->isActive == 0 || (Auth::check() && Auth::User()->role->name =='عضو')) {
    //if( !(Auth::check() && Auth::User()->role->name !='عضو')) {
    if($user->isActive == 0) {
        Auth::logout();
        echo "  الموقع تحت المراجعة حاول الزياره بعد ساعتين من الأن ألي ان يتم التأكد من حسابك وتنشيطه لك";
        //echo '<meta http-equiv="Refresh" content="0; url='. url('/') .'">';
        return;
    }

    if(Auth::check() && $isOwner){
      $user->unRace();
      ini_set('memory_limit','2048M');
    }
    //
    
    
?>


{{App::setLocale($user->lang)}}
@extends('layouts.headfooter')
{{App::setLocale($user->lang)}}
@section('allContent')
<?php $p = (@$_GET['p']) ? @$_GET['p'] : "home";  ?>
{{App::setLocale($user->lang)}}

@if($user->role_id == 2)
<meta http-equiv="Refresh" content="0; url={{ url('/backend') }}">
<?php return; ?>
@endif

<!=============================
| Header and Logo
===========================-->
<div id="headerWrap" class="container-fluid">
  <div id="headerContainer" class="container">
    <div class="row">
      
      @if(Auth::check() && $isOwner && $user->emailVerify !=1)
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>هام!: </strong> {!! $user->checkEmailVerify() !!}
      </div>
      @endif
      
      <div class="col-xs-12 col-md-6 col-content memberDiv">
            <div id="userHeadr" class="blockItem">
              
                @if(isset($user->photo))
                   <a href="{{ url('/' . $user->name) }}"><img class="circle" src="{{ url($user->uploads()) }}/{{ $user->photo->name }}" alt="{{ $user->name }}"></a>
                @else
                   <a href="{{ url('/' . $user->name) }}"><img class="circle" src="{{ url('images/fac_photo.png') }}" alt="{{ $user->name }}"></a>
                @endif
                <div class="blockItemData">
                    <p><span class="headerHead" uid="{{ $user->userID }}">{{ $user->fullName }}</span></p>
                    <p><span class="headerHead">{{ $user->getDegree(App::getLocale()) }} - {{ @$user->currentPosition }}</span></p>
                    @if(isset($user->faculty)) <p><span class="headerHead">@if(app()->getLocale() == "ar") {{ $user->faculty->name }} @else {{ $user->faculty->nameEn }}  @endif </span></p>@endif
                    @if(isset($user->title))   <p><span class="headerHead">{{ trans('main.address') }}</span>: {{ $user->title }}</p>@endif
                </div><!-- .blockItemData -->
                <div id="vote" class="pull-right">
               <p id="spanVoteVal">{{ $user->votesCount($user->id) }}</p>  {{ trans('main.like') }}
                  <a style="pointer-events: none; cursor: default; color:#23527c" data-toggle="tooltip" data-placement="bottom" title="Like" id="like" class="voteLike" href="#"><i class="fas fa-thumbs-up"></i></a>

                </div>
            </div><!-- .blockItem -->
      </div><!--.col-content -->
      <div class="col-xs-12 col-md-3 col-content">

      </div><!--.col-content -->
      <div class="col-xs-12 col-md-3 col-content">
          <a href="http://www.sohag-univ.edu.eg"><img id="soLogo" src="{{ url('images/soLogo.png') }}" alt="جامعة سوهاج"></a>
      </div><!--.col-content -->
    </div><!--.row -->
  </div><!--#headerContainer .container -->
</div><!--#headerWrap .container-->

<!=============================
| Main nav bar
===========================-->   
<nav id="mainNav" class="navbar nav-right  navbar-default ">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- <a class="navbar-brand" href="#">Brand</a> -->
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      
      <ul class="nav navbar-nav navbar-right">
        <li class="<?php if($p == "home") echo "hover";?>"><a href="{{ url('/'.$user->name) }}?p=home"><i class="fas fa-address-book navBarIco"></i>{{ trans('main.navProfile') }}</a></li>
        <li class="<?php if($p == "cv") echo "hover";?>"><a href="{{ url('/cv/'.$user->id) }}?p=cv"><i class="fas fa-address-card navBarIco"></i>{{ trans('main.navCv') }}</a></li>

        
        
        

        

        @if($user->posts()->count() > 0)
        <li class="<?php if($p == "posts") echo "hover";?>"><a href="{{ url('/stuff/home/posts/'.$user->id) }}?p=posts"><i class="fas fa-clipboard navBarIco"></i>{{ trans('main.navPosts') }}</a></li>
        @endif

        @if($user->offices()->count() > 0)
        <li class="<?php if($p == "offices") echo "hover";?>"><a href="{{ url('/stuff/home/offices/'.$user->id) }}?p=offices"><i class="fas fa-clock navBarIco"></i>{{ trans('main.navOffices') }}</a></li>
        @endif

        @if($user->researchs()->count() > 0)
          <li class="<?php if($p == "researchs") echo "hover";?>"><a href="{{ url('/stuff/home/researchs/'.$user->id) }}?p=researchs"><i class="far fa-sticky-note navBarIco"></i> {{ trans('main.manageResearchs') }} </a></li>
        @endif
        @if($user->subjects()->count() > 0 || 
        $user->supplements()->count() > 0 ||
        $user->tasks()->count() > 0 ||
        $user->advs()->count() > 0
        )
        <li class="dropdown <?php if($p == "advs") echo "hover";?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-graduate navBarIco"></i> {{ trans('main.students') }} <span class="caret"></span></a>
          <ul class="dropdown-menu">
            @if($user->subjects()->count() > 0)
              <li class="<?php if($p == "subjects") echo "hover";?>"><a href="{{ url('/stuff/home/subjects/'.$user->id) }}?p=subjects"><i class="fas fa-book"></i> {{ trans('main.navSubjects') }} </a></li>
            @endif

            @if($user->supplements()->count() > 0)
              <li class="<?php if($p == "supplements") echo "hover";?>"><a href="{{ url('/stuff/home/supplements/'.$user->id) }}?p=supplements"><i class="fas fa-paperclip"></i> {{ trans('main.navSupplements') }} </a></li>
            @endif

            @if($user->tasks()->count() > 0)
              <li class="<?php if($p == "tasks") echo "hover";?>"><a href="{{ url('/stuff/home/tasks/'.$user->id) }}?p=tasks"><i class="fas fa-tasks"></i> {{ trans('main.navTasks') }} <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.stuAnswers') }}" class="icoHover"><i class="fas fa-file-signature"></i> {{ $user->answersCounts($user->id) }}</span> <span class="taskIcon"></span> </a></li>
            @endif

            @if($user->advs()->count() > 0)
              <li class="<?php if($p == "advs") echo "hover";?>"><a href="{{ url('/stuff/home/advs/'.$user->id) }}?p=advs"><i class="fas fa-newspaper"></i>{{ trans('main.navAdvs') }} </a></li>
            @endif
            
          </ul>
        </li>
        @endif

        

        <!-- 
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li role="separator" class="divider"></li>
            <li class="multiMenuli">
              <a href="#" class="dropdown-toggle multiMenu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">SubDrob <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li class="multiMenuli">
                  <a href="#" class="dropdown-toggle multiMenu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">SubDrob <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                  </ul>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        -->
      </ul> 
      
      <ul class="nav navbar-nav navbar-left">
        @if (!Auth::guest() && (Auth::user()->isAdminAndStuff() || Auth::user()->isAdmin()) )
          <li><a href="{{ url('backend') }}"><i class="fas fa-cogs  cpanel navBarIco"></i>{{ trans('main.cpanelName') }}</a></li>
        @endif
        <li><a href="{{ url('/') }}"><i class="fas fa-home  navBarIco"></i>{{ trans('main.navStuffWeb') }}</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<!=============================
| Body Conteent
===========================-->
<div id="bodyContentWrap" class="container-fluid">
  <div id="bodyContent" class="container">
    <div id="" class="row">
      
      <div id="sideBar" class="col-xs-12  col-md-3 sideBar">
        
        <nav class="sideNav">
            
            

            <!-- <a target='_blank' class="btn btn-info" style="display:block;" href="{{ url('/test/ip/'.$user->name) }}">{{ trans('main.vLink') }}</a> -->
            @if (Auth::guest())                  
              <a href="{{ url('/login') }}"><span class="moveArow glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span>{{ trans('main.login') }}</a>
            @elseif(!$isOwner)
              <a href="{{ url('/'.Auth::user()->name) }}"><span class="moveArow glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span>{{ trans('main.vistMyWebSite') }}</a>
            @else
            <a><span class="moveArow glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span>{{ trans('main.welcome') }} - {{ Auth::user()->name }}</a>
            <a href="{{ route('logout') }}" 
                              onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                <span class="moveArow glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span>{{ trans('main.logout') }}</a>


                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif
          
          {{--  <a class="s2nav" href=""><span class="moveArow glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span>{{ trans('main.manual') }}</a>  --}}
        </nav>

        <div id="uAnalysis">
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
        </div>
       <div class="userProgress">
          <div data-toggle="tooltip" data-placement="top" title="{{ trans('main.contentComplete') }}" class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $user->mostContent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $user->getContent() }}%;">
              {{ $user->mostContent }}
            </div>
          </div>
        </div>
        <br>

        @if (!Auth::guest() && $isOwner)

        <div class="panel panel-default StuffDashboard">
          <div class="panel-heading"><i class="fas fa-cogs"></i> {{ trans('main.managePanel') }} </div>
          <div class="panel-body">
            <nav class="dashboardNav">
              <a href="{{ url('/suff/passchange') }}"><i class="fas fa-key"></i> {{ trans('main.passchange') }} </a>
              <a href="{{ url('/suff/langchange') }}"><i class="fas fa-globe"></i> {{ trans('main.langchange') }} </a>

              <a href="{{ url('stuff/user') }}"><i class="fas fa-id-card"></i> {{ trans('main.manageSpcInfo') }} </a>
              <a href="{{ url('cv/' . $user->id . '/edit') }}"><i class="fas fa-address-card"></i>{{ trans('main.manageCv') }} </a>
              <a href="{{ url('stuff/subjects?p=subjects') }}"><i class="fas fa-book"></i> {{ trans('main.manageSubjects') }}</a>

              <a href="{{ url('stuff/sheets/print?p=subjects') }}"><i class="fas fa-print"></i> {{ trans('main.printSheet') }}</a>
              
              @if($user->subjects()->count() > 0)
              <a href="{{ url('stuff/supplements?p=supplements') }}"><i class="fas fa-paperclip"></i> {{ trans('main.manageSupplements') }} </a>
              @endif

              @if($user->subjects()->count() > 0)
              <a href="{{ url('stuff/tasks?p=tasks') }}"><i class="fas fa-tasks"></i> {{ trans('main.manageTasks') }} </a>
              @endif

              @if($user->subjects()->count() > 0 )
              <a href="{{ url('stuff/researchs?p=researchs') }}"><i class="far fa-sticky-note"></i> {{ trans('main.manageResearchs') }} </a>
              @endif

              <a href="{{ url('stuff/posts?p=posts') }}"><i class="fas fa-clipboard"></i> {{ trans('main.managePosts') }} </a>
              <a href="{{ url('stuff/offices?p=offices') }}"><i class="fas fa-clock"></i> {{ trans('main.manageOffices') }} </a>
              <a href="{{ url('stuff/advs?p=advs') }}"><i class="far fa-newspaper"></i> {{ trans('main.manageAdvs') }} </a>
            </nav>
          </div>
        </div>
        
        @endif

        
        <!-- Added New -->
        @if(
          $user->subjects()->count() > 0 || 
          $user->supplements()->count() > 0 || 
          $user->tasks()->count() > 0 || 
          $user->posts()->count() > 0 || 
          $user->advs()->count() > 0 
        )
        <div class="panel panel-default StuffDashboard">
          <div class="panel-heading"><i class="fas fa-cart-plus"></i>{{ trans('main.AddedNew') }}</div>
          <div class="panel-body">
            <nav>
              @if($user->subjects()->count() > 0)
                @foreach($user->subjects->slice(0,2) as $subject)
                  <a href="{{ url('stuff/subjects/show/'.$subject->id.'?p=subjects') }}"><i class="fas fa-book navAddedNewIco"></i>{{ $subject->title }}</a>
                @endforeach
              @endif

              @if($user->supplements()->count() > 0)
                @foreach($user->supplements->slice(0,2) as $supplement)
                  <a href="{{ url('stuff/supplements/show/'.$supplement->id.'?p=supplements') }}"><i class="fas fa-paperclip navAddedNewIco"></i>{{ $supplement->title }}</a>
                @endforeach
              @endif

              @if($user->tasks()->count() > 0)
                @foreach($user->tasks->slice(0,2) as $task)
                  <a href="{{ url('stuff/tasks/show/'.$task->id.'?p=tasks') }}"><i class="fas fa-tasks navAddedNewIco"></i>{{ $task->title }}</a>
                @endforeach
              @endif

              @if($user->posts()->count() > 0)
                @foreach($user->posts->slice(0,2) as $post)
                  <a href="{{ url('stuff/posts/show/'.$post->id.'?p=posts') }}"><i class="fas fa-clipboard  navAddedNewIco"></i>{{ $post->title }}</a>
                @endforeach
              @endif

              @if($user->advs()->count() > 0)
                @foreach($user->advs->slice(0,2) as $adv)
                  <a href="{{ url('stuff/advs/show/'.$adv->id.'?p=advs') }}"><i class="fas fa-newspaper  navAddedNewIco"></i>{{ $adv->title }}</a>
                @endforeach
              @endif
            </nav>
          </div>
        </div>
        @endif

         <div class="panel panel-default StuffDashboard">
          <div class="panel-body">

            <section id="chartId">
              <div class="pieID pie">
                
              </div>
              <ul class="pieID legend">
                <li>
                  <em><i class="fas fa-book"></i> {{ trans('main.manageSubjects') }}</em>
                  <span>{{ $user->subjects->count() }}</span>
                </li>
                <li>
                  <em><i class="fas fa-paperclip"></i> {{ trans('main.manageSupplements') }}</em>
                  <span>{{ $user->supplements->count() }}</span>
                </li>
                <li>
                  <em><i class="fas fa-tasks"></i> {{ trans('main.manageTasks') }}</em>
                  <span>{{ $user->tasks->count() }}</span>
                </li>
                <li>
                  <em><i class="fas fa-clipboard"></i> {{ trans('main.managePosts') }}</em>
                  <span>{{ $user->posts->count() }}</span>
                </li>
                <li>
                  <em><i class="far fa-clock"></i> {{ trans('main.navOffices') }}</em>
                  <span>{{ $user->offices->count() }}</span>
                </li>
                <li>
                  <em><i class="far fa-newspaper"></i> {{ trans('main.manageAdvs') }}</em>
                  <span>{{ $user->advs->count() }}</span>
                </li>
                <li title="عدد الملفات المرفقة في المواد الدراسية وملحقاتها">
                  <em><i class="fas fa-cloud-upload-alt"></i> {{ trans('main.fileTimes') }}</em>
                  <span>{{ $user->allfiles()->whereIn('fileable_type' , array('App\Subject', 'App\Supplement'))->count() }}</span>
                </li>
                <li  title="عدد مرات تحميل الملفات المرفقة في المواد الدراسية وملحقاتها">
                  <em><i class="fas fa-cloud-download-alt"></i> {{ trans('main.downloadedTimes') }}</em>
                  <span>{{ $user->allfiles()->whereIn('fileable_type' , array('App\Subject', 'App\Supplement'))->sum('downloaded') }}</span>
                </li>
              </ul>
            </section>

          </div>
        </div>

      </div>
      
      <div id="bContentWrap" class="col-xs-12  col-md-9">
        @yield('stuffContent')
      </div><!-- .bContentWrap -->
      
    </div><!--.row -->
  </div><!--.bodyContent .container-->
</div><!--#bodyContentWrap .container-fluid-->

<!=============================
| #footer
===========================-->
<div id="footerWrap" class="container-fluid footerWrap">
  <div id="footer" class="container">
    <div id="" class="row">

      <div id="contactUs" class="contactUs col-xs-12 col-md-4">
        <h2>{{ trans('main.contactMeFootS') }}</h2>
        <ul>
          @if($user->title)<li><i class="fas fa-map-marker-alt"></i> <span>{!! trans('main.address') !!}</span>{{ $user->title }}</li>@endif
          @if($user->phone)<li><i class="fas fa-mobile"></i> <span>{{ trans('main.user-phone') }}</span>{{ $user->phone }}</li>@endif
          @if($user->fax)<li><i class="fas fa-fax"></i> <span>{{ trans('main.user-fax') }}</span>{{ $user->fax }}</li>@endif
          @if($user->email)<li><i class="fas fa-at"></i> <span>{{ trans('main.user-email') }}</span>{{ $user->email }}</li>@endif
        </ul>
        <hr>
        <p style="color:#FFF; font-size:17px;">{{ trans('main.dataNote') }}</p>
      </div><!-- #contactUs .contactUs -->

      <div id="importantLinks" class="importantLinks col-xs-12 col-md-4">
        <h2>{{ trans('main.ImportantLinks') }}</h2>
        <nav>
          <a href="http://www.sohag.gov.eg/"><i class="fas fa-angle-double-left"></i>{{ trans('main.SohagGovernoratePortal') }}</a>
          <a href="https://www.egypt.gov.eg/arabic/home.aspx"></i>{{ trans('main.EgyptionGovernoratePortal') }}</a>
          <a href="http://srv4.eulc.edu.eg/eulc_v5/libraries/start.aspx"><i class="fas fa-angle-double-left"></i>{{ trans('main.EgyptionUniveristiesLibratiesUnion') }}</a>
          <a href="http://www.egy-mhe.gov.eg/"><i class="fas fa-angle-double-left"></i>{{ trans('main.MinistryOfHighEducation') }}</a>
          <a href="http://www.scu.eun.eg/wps/portal"><i class="fas fa-angle-double-left"></i>{{ trans('main.SupremeCouncilOfUniversities') }}</a>
          <a href="http://www.aaru.edu.jo/Home.aspx"><i class="fas fa-angle-double-left"></i>{{ trans('main.EgyptionUniversitiesUnion') }}</a>
        </nav>
      </div><!-- #importantLinks .importantLinks -->
      
      <div id="withSocial" class="withSocial col-xs-12 col-md-4">
        <h2>{{ trans('main.sohagUniv') }}</h2>
        <a href="http://www.sohag-univ.edu.eg"><img class="sologo" src="{{ url('images/soLogo.png') }}" alt=""></a>
        <a href="{{ url('/') }}"><h3>{{ trans('main.webHeaderTitle') }}</h3></a>
        <p class="designedBy"><span class="design">{{ trans('main.desgnedBy') }}/</span> <a target="_blank" href="http://www.emadeldeen.com">Emadeldeen</a></p>
      </div><!-- #withSocial .withSocial -->

    </div><!--. -->
  </div><!--.row-->

  <div class="row scocialMedia">
    <ul class="social-network .circle">
        
        @if($user->fb)
          <a  href="{{ $user->fb }}"><i class="fab fa-facebook-f"></i></a>
        @endif

        @if($user->twitter)
          <a href="{{ $user->twitter }}"><i class="fab fa-twitter"></i></a>
        @endif

        @if($user->yt)
          <a href="{{ $user->yt }}"><i class="fab fa-youtube-square"></i></a>
        @endif

        @if($user->linkedIn)
          <a href="{{ $user->linkedIn }}"><i class="fab fa-linkedin"></i></a>
        @endif

        @if($user->googlePlus)
          <a href="{{ $user->googlePlus }}"><i class="fab fa-google-plus-square"></i></a>
        @endif
        
        @if($user->schooler)
          <a href="{{ $user->schooler }}"><i class="fas fa-graduation-cap"></i></a>
        @endif
    </ul>
  </div>

</div><!--#footerWrap .container-fluid -->

<?php
     //$ip = @@file_get_contents('https://api.ipify.org');
     //echo "My public IP address is: " . $ip . "<br>";
?>
<!-- <p style="text-align:left;" id="yourip"></p> -->

@endsection

@section('scriptsData')
<script>

  $(document).ready(function(){
    $voteValue = ($(this).attr('id') == "like") ? 1 : -1;
    $.ajax({
      type:'get',
      url:'{{ URL::to("stuff/votes/create/". $user->id) }}',
      data: { 'user_id': {{ $user->id }}, 'ip': $ipDesc, 'val':  $voteValue},
      success: function(data){
        // alert(data)
          if(data == 'active'){
            // $("#spanVoteVal").html(data);
            $('.voteLike').css({
              'color' : '#337ab7',
              'pointer-events': 'auto',
              'cursor': 'pointer',
            })
          }
      }
    });


    $('.voteLike').click(function(){
      $voteValue = ($(this).attr('id') == "like") ? 1 : -1;
      $.ajax({
        type:'get',
        url:'{{ URL::to("stuff/votes/create") }}',
        data: { 'user_id': {{ $user->id }}, 'ip': $ipDesc, 'val':  $voteValue},
        success: function(data){
          // alert(data)
            $("#spanVoteVal").html(data);
            $('.voteLike').css({
              'color' : '#23527c',
              'pointer-events': 'none',
              'cursor': 'default',
          })
        }
      });
    });
  })

//charts js
    @if(//Auth()->check() &&
      (
        $user->advs()->count() > 0 ||  
        $user->offices()->count() > 0 ||  
        $user->posts()->count() > 0 || 
        $user->subjects()->count() > 0 || 
        $user->supplements()->count() > 0 || 
        $user->tasks()->count() > 0
      )
    )
    function sliceSize(dataNum, dataTotal) {
      return (dataNum / dataTotal) * 360;
    }
    function addSlice(sliceSize, pieElement, offset, sliceID, color) {
      $(pieElement).append("<div class='slice "+sliceID+"'><span></span></div>");
      var offset = offset - 1;
      var sizeRotation = -179 + sliceSize;
      $("."+sliceID).css({
        "transform": "rotate("+offset+"deg) translate3d(0,0,0)"
      });
      $("."+sliceID+" span").css({
        "transform"       : "rotate("+sizeRotation+"deg) translate3d(0,0,0)",
        "background-color": color
      });
    }
    function iterateSlices(sliceSize, pieElement, offset, dataCount, sliceCount, color) {
      var sliceID = "s"+dataCount+"-"+sliceCount;
      var maxSize = 179;
      if(sliceSize<=maxSize) {
        addSlice(sliceSize, pieElement, offset, sliceID, color);
      } else {
        addSlice(maxSize, pieElement, offset, sliceID, color);
        iterateSlices(sliceSize-maxSize, pieElement, offset+maxSize, dataCount, sliceCount+1, color);
      }
    }
    function createPie(dataElement, pieElement) {
      var listData = [];
      $(dataElement+" span").each(function() {
        listData.push(Number($(this).html()));
      });
      var listTotal = 0;
      for(var i=0; i<listData.length; i++) {
        listTotal += listData[i];
      }
      var offset = 0;
      var color = [
        "cornflowerblue", 
        "olivedrab", 
        "orange", 
        "tomato", 
        "crimson", 
        "purple", 
        "turquoise", 
        "forestgreen", 
        "navy", 
        "gray"
      ];
      for(var i=0; i<listData.length; i++) {
        var size = sliceSize(listData[i], listTotal);
        iterateSlices(size, pieElement, offset, i, 0, color[i]);
        $(dataElement+" li:nth-child("+(i+1)+")").css("border-color", color[i]);
        offset += size;
      }
    }
    createPie(".pieID.legend", ".pieID.pie");

    @endif
    

</script>

@endsection