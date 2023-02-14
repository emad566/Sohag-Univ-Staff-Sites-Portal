@include('common')
{{ setLang() }}
@extends('layouts.frontend')

@section('headMeta')
    <title>{{ trans('main.webHeaderTitle') }}</title>

    <!-- facebook meta -->

    <!-- facebook meta -->
 
    <meta property="og:image" content="{{ url('/fbn.jpg') }}" />
    <meta property="og:title" content="{{ trans('main.stufffbTitle') }}"/> 
  
    <meta property="og:description" content="{{ trans('main.stufffbDesc') }}" />



    <meta property="og:image" content="{{ url('images/soLogo.png') }}" />

    <link rel="icon" href="{{ url('images/soLogo.png') }}" sizes="32x32" />
    <link rel="icon" href="{{ url('images/soLogo.png') }}" sizes="192x192" />
    <link rel="apple-touch-icon-precomposed" href="{{ url('images/soLogo.png') }}" />
    <meta name="msapplication-TileImage" content="{{ url('images/soLogo.png') }}" />

@endsection

@section('content')   
        <div id="bContent" class="row bContent">
            
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                
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
            
             @if($fac_id != "all")
            
            <table id="reportTable" class="table table-hover table-striped table-bordered order-column"> 
                <thead>
                    <tr>
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
            </table>
            @endif
              
              <div class="alert alert-info webNote">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>{!! trans('main.important') !!}!: </strong>
                 تم تحديث أعضاء الصفحة الرئيسية بحيث يظهر فيها فقط من قاموا بتعديل أو إضافة أو رفع أي شئ في مواقعم خلال اخر 48 ساعة، فاذا كان هناك عضو يظهر في الصفحة الرئيسية ولاحظ اختفاء موقعه يقوم بتسجيل الدخول علي موقعه و يقوم بتعديل أو اضافة او رفع اي شئ و سيأخذ مكانه مرة اخري. 
                 
              </div>
              
            </div>
            
            <div class="col-xs-12 col-md-6 blockSections">

              <h2>{{ $visitors }} - {{ trans('main.webNew') }}</h2>
              <div class="blockContent">
                <?php $i=0; ?>
                @foreach($newUsers as $user)
                @if ($i++ < 5)
                  <div class="blockItem">
                    @if(isset($user->photo) && file_exists('./uploads/'.$user->id.'/'.$user->photo->name))
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
                    <span class="mostViewCount">{{ $user->updated_at }}<span class="glyphicon glyphicon-calendar"></span></span>
                  </div><!-- .blockItem -->
                @endif
                @endforeach
              </div><!-- .blockContent -->
            </div><!-- .blockSections -->
  
            
            <div class="col-xs-12 col-md-6 blockSections moreVisited">
              <h2>{{ trans('main.webMostVisited') }}</h2>
              <div class="blockContent">
                <?php $i=0; ?>
                @foreach($mostViews as $user)
                @if ($i++ < 5)
                <div class="blockItem">
                    @if(isset($user->photo) && file_exists('./uploads/'.$user->id.'/'.$user->photo->name))
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
                                {{ $user->faculty->nameEn }}@endif
                        @endif
                    </p>
                  </div><!-- .blockItemData -->
                  <span class="mostViewCount"> <a target="_blank" href="{{ url('/test/ip/'.$user->name) }}">{{ $user->mostView }}</a><i class="fas fa-eye"></i></span>
                </div><!-- .blockItem -->
                @endif
                @endforeach
              </div><!-- .blockContent -->
            </div><!-- .blockSections -->
            
          </div><!--.row #bContent-->
  
          <div id="bContent" class="row bContent">
  
            <div class="col-xs-12 col-md-6 blockSections moreActive">
              <h2>{{$logedins->count()}} - {{ trans('main.webMostActive') }}</h2>
              <div class="blockContent">
                <?php $i=0; ?>
                @foreach($mostActives as $user)
                @if ($i++ < 5)
                  <div class="blockItem">
                    @if(isset($user->photo) && file_exists('./uploads/'.$user->id.'/'.$user->photo->name))
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
                                {{ $user->faculty->nameEn }}@endif
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
                      <span data-toggle="tooltip" data-placement="bottom" title="{{ trans('main.countTime') }}" class="icoHover">{{ $user->countTime  }}<i class="fas fa-plug"></i></span> 
                    </span>
                  </div><!-- .blockItem -->
                @endif
                @endforeach
              </div><!-- .blockContent -->
            </div><!-- .blockSections -->
  
            
            <div class="col-xs-12 col-md-6 blockSections moreContent">
              <h2>{{ trans('main.webMostContent') }}</h2>
              <div class="blockContent">
                <?php $isF = true; ?>
                <?php $i=0; ?>
                @foreach($mostContents as $user)
                @if ($i++ < 4)
                  <?php 
                    if($isF)  $maxValue = $user->mostContent;
                    $isF = false;
                    $width = $user->mostContent / $maxValue * 100;
                  ?>
                  <div class="blockItem">
                    @if(isset($user->photo) && file_exists('./uploads/'.$user->id.'/'.$user->photo->name))
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
                                {{ $user->faculty->nameEn }}@endif
                        @endif
                      </p>
                    </div><!-- .blockItemData -->
                    <div class="userProgress">
                      <div data-toggle="tooltip" data-placement="top" title="{{ trans('main.contentComplete') }}" class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $user->mostContent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $width }}%;">
                          {{ $user->mostContent }}
                        </div>
                      </div>
                    </div>
                  </div><!-- .blockItem -->
                @endif
                @endforeach
              </div><!-- .blockContent -->
            </div><!-- .blockSections -->
          </div><!--.row #bContent--> 
          

@endsection

@section('scriptsData')
<script>
    $(document).ready(function(){
        $( "#faculty_id" ).change(function() {
            window.location.replace('{{ url('/') }}/' + $( "#faculty_id" ).val() +'\\single')
        });
    })
</script>

@endsection


