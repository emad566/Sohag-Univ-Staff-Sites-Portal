@extends('layouts.headfooter')

@section('allContent')
<?php $p = (@$_GET['p']) ? @$_GET['p'] : "home"; 
// echo "الرجاء الإنتظار جاري تحديث الموقع الرجاء محاولة الإتصال لاحقا بعد 10 دقائق";
// return;
?>

<!=============================
| Header and Logo
===========================-->
<div id="headerWrap" class="container-fluid">
  <div id="headerContainer" class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-content">
          
        <a href="http://www.sohag-univ.edu.eg"><img id="soLogo" src="{{ url('images/soLogo.png') }}" alt="موقع أعضاء هيئة التدريس - جامعة سوهاج"></a>
          <h1 id="mainHead">{!! trans('main.webHeaderTitle') !!}</h1>

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
        <li class="<?php if($p == "home") echo "hover";?>"><a  href="{{ url('/') }}?p=home"><i class="fas fa-home navBarIco"></i>{{ trans('main.navHome') }}</a></li>
        <li class="<?php if($p == "search") echo "hover";?>"><a href="{{ url('stuff/search') }}?p=search"><i class="fas fa-search-plus navBarIco"></i>{{ trans('main.searchForWebUser') }}</a></li>
        <li class="<?php if($p == "helpe") echo "hover";?>"><a href="{{ url('stuff/helpe') }}?p=helpe"><i class="fas fa-question-circle navBarIco"></i>{{ trans('main.helpe') }}</a></li>

        @if(Auth::guest())
        <li><a href="?lang=<?php echo (session()->has('lang') && session()->get('lang') == "en") ?  'ar': 'en'; ?>"><i class="fas fa-globe navBarIco"></i> <?php 
          echo (session()->has('lang') && session()->get('lang') == "en") ?  trans('main.langAr'): trans('main.langEn'); 
        ?></a></li>
        @endif

        <li class="<?php if($p == "check") echo "hover";?>"><a href="{{ url('stuff/check') }}?p=check"><i class="fas fa-question-circle navBarIco"></i>{{ trans('main.check') }}</a></li>
        
        
        <li class="<?php if($p == "getresearchs") echo "hover";?>"><a href="{{ url('stuff/getresearchs?p=getresearchs') }}"><i class="far fa-sticky-note navBarIco"></i> {{ trans('main.getresearchs') }} </a></li>
        
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

        @if (!Auth::guest() && (Auth::user()->isAdminAndStuff() || Auth::user()->isStuff()) )
          <li><a href="{{ url('/' . Auth::user()->name) }}"><i class="fas fa-address-book navBarIco"></i>{{ trans('main.vistMyWebSite') }}</a></li>
        @endif
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
            <link rel="stylesheet" type="text/css" href="{{ asset('circle/styles/plugin.css') }}">
            <a style="background-color: transparent; border: none; margin: auto; padding: 0px; color: #666" href="{{ url('stuff/search?p=search') }}"><div class="my-progress-bar"></div></a>

            @if (Auth::guest())
              <a href="{{ url('/login') }}"><span class="moveArow glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span>{{ trans('main.login') }}</a>
            @else
            <a href="{{ url('/'.Auth::user()->name) }}"><span class="moveArow glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span>{{ trans('main.welcome') }} - {{ Auth::user()->name }}</a>
            <a href="{{ route('logout') }}" 
                              onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                <span class="moveArow glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span>{{ trans('main.logout') }}</a>


                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif
          
          {{--  <a class="s2nav" href=""><span class="moveArow glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span> دليل الاستخدام</a>  --}}
        </nav>
        
        <div class="learn">
            <p>شرح استخدام Microsoft Teams لأستاذ المادة والمعلمين</p>
            
            <iframe src="https://www.youtube.com/embed/9yta1cRRDP8" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

            <p> عرض تقديمي   Microsoft Teams</p>
            <a href="{{ asset('images/mteam.pdf') }}">
            <img src="{{ asset('images/mteamlogo.jpg') }}" style="width: 100%; height: 200px;" alt="mteamlogo"></a>
            <!--<p> شرح عمل  Research Gate</p>-->
            <!--<iframe src="https://www.youtube.com/embed/Ho-EjNAjqzE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>-->
            <p> عرض تقديمي  2 Microsoft Teams</p>
            <a href="{{ asset('images/mteam2.pdf') }}">
              <img src="{{ asset('images/mteamlogo.jpg') }}" style="width: 100%; height: 200px;" alt="mteamlogo"></a>
        </div>

        <div class="logedin">
        @if (isset($logedins))
          @foreach($logedins as $user)
            <div class="blockItem">
              @if(isset($user->photo))
                <a href="{{ url('/' . $user->name) }}"><img class="circle" src="{{ url('/') }}/{{ $user->uploads() . $user->photo->name }}" alt="{{ $user->name }}"></a>
              @else
                <a href="{{ url('/' . $user->name) }}"><img class="circle" src="{{ url('/') }}/images/fac_photo.png" alt="{{ $user->name }}"></a>
              @endif

              <div class="blockItemData">
                <h3><a href="{{ url('/' . $user->name) }}">{{-- substr($user->fullName, 0, 35) --}} {{ $user->fullName }}</a></h3>
                <p>{{ $user->getDegree($user->lang) }} - 
                  @if(isset($user->faculty_id) && isset($user->faculty))
                      @if($user->lang =="ar") 
                          {{ $user->faculty->name }} 
                      @else 
                          {{ $user->faculty->nameEn }}
                      @endif
                  @endif
                </p>
              </div><!-- .blockItemData -->
            </div><!-- .blockItem -->
          @endforeach
        @endif
        </div>
      </div>
      
      <div id="bContentWrap" class="col-xs-12  col-md-9">
        @yield('content')
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
        <h2>{{ trans('main.sohahCotact') }}</h2>
        <p class="desribSohag">{{ trans('main.sohagContactDesc') }}</p>
        <ul>
          <li><i class="fas fa-map-marker-alt"></i> <span>{!! trans('main.address') !!}</span>{{ trans('main.univTitle') }}</li>
          <li><i class="fas fa-mobile"></i> <span>{{ trans('main.user-phone') }}</span>0934570000</li>
          <li><i class="fas fa-fax"></i> <span>{{ trans('main.user-fax') }}</span>0934605745</li>
          <li><i class="fas fa-at"></i> <span>{{ trans('main.user-email') }}</span>president@sohag.edu.eg</li>
        </ul>
      </div><!-- #contactUs .contactUs -->

      <div id="importantLinks" class="importantLinks col-xs-12 col-md-4">
        <h2>{{ trans('main.ImportantLinks') }}</h2>
        <nav>
          <a href="http://www.sohag.gov.eg/"><i class="fas fa-angle-double-left"></i>{{ trans('main.SohagGovernoratePortal') }}</a>
          <a href="https://www.egypt.gov.eg/arabic/home.aspx"><i class="fas fa-angle-double-left"></i>{{ trans('main.EgyptionGovernoratePortal') }}</a>
          <a href="http://srv4.eulc.edu.eg/eulc_v5/libraries/start.aspx"><i class="fas fa-angle-double-left"></i>{{ trans('main.EgyptionUniveristiesLibratiesUnion') }}</a>
          <a href="http://www.egy-mhe.gov.eg/"><i class="fas fa-angle-double-left"></i>{{ trans('main.MinistryOfHighEducation') }}</a>
          <a href="http://www.scu.eun.eg/wps/portal"><i class="fas fa-angle-double-left"></i>{{ trans('main.SupremeCouncilOfUniversities') }}</a>
          <a href="http://www.aaru.edu.jo/Home.aspx"><i class="fas fa-angle-double-left"></i>{{ trans('main.EgyptionUniversitiesUnion') }}</a>
        </nav>
      </div><!-- #importantLinks .importantLinks -->
      
      <div id="withSocial" class="withSocial col-xs-12 col-md-4">
        <h2>{{ trans('main.sohagUniv') }}</h2>
        <a href="http://www.sohag-univ.edu.eg"><img class="sologo" src="/images/soLogo.png" alt=""></a>
        <h3>{{ trans('main.webHeaderTitle') }}</h3>
        <p class="designedBy"><span class="design">{{ trans('main.desgnedBy') }}/</span> <a target="_blank" href="http://www.emadeldeen.com">Emadeldeen</a></p>
      </div><!-- #withSocial .withSocial -->

    </div><!--. -->
  </div><!--.row-->
</div><!--#footerWrap .container-fluid -->

@endsection

@section('scriptsData')

@endsection