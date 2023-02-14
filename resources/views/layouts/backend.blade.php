{{App::setLocale(Auth()->user()->lang)}}
@extends('layouts.headfooter')

@section('allContent')
<?php $p = (@$_GET['p']) ? @$_GET['p'] : "home";  ?>
<!=============================
| Header and Logo
===========================-->
<div id="headerWrap" class="container-fluid">
  <div id="headerContainer" class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-content">
          <a href="{{ url('/') }}"><img id="soLogo" src="{{ asset('images/soLogo.png') }}" alt="موقع أعضاء هيئة التدريس - جامعة سوهاج"></a>
          <h1 id="mainHead">{{ trans('main.webHeaderTitle') }} - {{ trans('main.cpanelName') }}</h1>
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
        <li class="<?php if($p == "home") echo "hover";?>"><a href="{{ url('/backend') }}">{{ trans('main.navHome') }}</a></li>
        <li class="<?php if($p == "users") echo "hover";?>"><a href="{{ url('/backend/users?p=users') }}">{{ trans('main.user-indexHead') }}</a></li>
        <li class="<?php if($p == "faculties") echo "hover";?>"><a href="{{ url('/backend/faculties?p=faculties') }}">{{ trans('main.faculties') }}</a></li>
        @if(Auth::user()->role_id!=2)
        <li class="<?php if($p == "reports") echo "hover";?>"><a href="{{ url('/stuff/user/report?p=reports') }}">{{ trans('main.reports') }}</a></li>
        <li class="<?php if($p == "racereport") echo "hover";?>"><a href="{{ url('/stuff/user/racereport?p=racereport') }}">{{ trans('main.race') }}</a></li>
        @endif
        <li class="<?php if($p == "lastLoged") echo "hover";?>"><a href="{{ url('/u/loged?p=lastLoged') }}">{{ trans('main.lastLoged') }}</a></li>

        <li class="<?php if($p == "uedit") echo "hover";?>"><a href="{{ url('backend/users/'. Auth()->user()->id .'/edit?p=uedit') }}">{{ trans('main.cpanelUserAcountNav') }}</a></li>
        <li><a href="{{ url('/') }}">{{ trans('main.vistWebSite') }}</a></li>
        
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
          @if (Auth::guest())
          <li><a href="{{ url('/login') }}">أنشئ موقعك</a></li>
          @else
          <li><a>{{ trans('main.welcome') }} - {{ Auth::user()->name }}</a></li>
          <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ trans('main.logout') }}</a></li>


            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
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
      <div id="bContentWrap" class="col-xs-12">
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
        <P class="backendFooter">{!! trans('main.copyRightsl') !!}</P>
    </div><!--. -->
  </div><!--.row-->
</div><!--#footerWrap .container-fluid -->

@endsection