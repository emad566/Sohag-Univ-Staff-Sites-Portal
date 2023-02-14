
          <div id="bContent" class="row bContent">
            <div class="col-xs-12 col-md-6 blockSections blokelements">

              <h2>{{ trans('main.last') }} {{ trans('main.navSubjects') }}</h2>
              <div class="blockContent">
                <?php $i=0; $outu = []; ?>
                @foreach($subjects as $subject)
                    <?php if (!in_array($subject->user->id,  $outu) && $i<5){ 
                        $i++;
                        array_push($outu, $subject->user->id);
                    ?>
                
                  <div class="blockItem">
                    @if(isset($subject->user->photo) && file_exists('./uploads/'.$subject->user->id.'/'.$subject->user->photo->name))
                      <a href="{{ url('/') }}/stuff/subjects/show/{{ $subject->id }}?p=subjects"><img class="circle" src="{{ url('/') }}/{{ $subject->user->uploads() . $subject->user->photo->name }}" alt="{{ $subject->title }}"></a>
                    @else
                      <a href="{{ url('/') }}/stuff/subjects/show/{{ $subject->id }}?p=subjects"><img class="circle" src="{{ url('/') }}/images/fac_photo.png" alt="{{ $subject->title }}"></a>
                    @endif

                    <div class="blockItemData">
                    <h3><a class="elmTitle" href="{{ url('/') }}/stuff/subjects/show/{{ $subject->id }}?p=subjects">{{ $user->stripText($subject->title, 40) }}</a></h3>
                    <h3><a href="{{ url('/') }}/stuff/subjects/show/{{ $subject->id }}?p=subjects">{{ $subject->user->fullName }}</a></h3>
                    </div><!-- .blockItemData -->
                  </div><!-- .blockItem -->
                  <?php } ?>
                @endforeach
              </div><!-- .blockContent -->
            </div><!-- .blockSections -->
            
          
          
          <div class="col-xs-12 col-md-6 blockSections blokelements">

              <h2 style="margin-top:0px;">{{ trans('main.last') }} {{ trans('main.navSupplements') }}</h2>
              <div class="blockContent">
                <?php $i=0; $outu = []; ?>
                @foreach($supplements as $supplement)
                    <?php if (!in_array($supplement->user->id,  $outu) && $i<5){ 
                        $i++;
                        array_push($outu, $supplement->user->id);
                    ?>
                
                  <div class="blockItem">
                    @if(isset($supplement->user->photo) && file_exists('./uploads/'.$supplement->user->id.'/'.$supplement->user->photo->name))
                      <a href="{{ url('/') }}/stuff/supplements/show/{{ $supplement->id }}?p=subjects"><img class="circle" src="{{ url('/') }}/{{ $supplement->user->uploads() . $supplement->user->photo->name }}" alt="{{ $supplement->title }}"></a>
                    @else
                      <a href="{{ url('/') }}/stuff/supplements/show/{{ $supplement->id }}?p=subjects"><img class="circle" src="{{ url('/') }}/images/fac_photo.png" alt="{{ $supplement->title }}"></a>
                    @endif

                    <div class="blockItemData">
                    <h3><a class="elmTitle" href="{{ url('/') }}/stuff/supplements/show/{{ $supplement->id }}?p=subjects">{{ $user->stripText($supplement->title, 40) }}</a></h3>
                    <h3><a href="{{ url('/') }}/stuff/supplements/show/{{ $supplement->id }}?p=subjects">{{ $supplement->user->fullName }}</a></h3>
                    </div><!-- .blockItemData -->
                  </div><!-- .blockItem -->
                  <?php } ?>
                @endforeach
              </div><!-- .blockContent -->
            </div><!-- .blockSections -->
            
          </div><!--.row #bContent-->

          <div id="bContent" class="row bContent">
            <div class="col-xs-12 col-md-6 blockSections blokelements">

              <h2>{{ trans('main.last') }} {{ trans('main.navTasks') }}</h2>
              <div class="blockContent">
                <?php $i=0; $outu = []; ?>
                @foreach($tasks as $task)
                    <?php if (!in_array($task->user->id,  $outu) && $i<5){ 
                        $i++;
                        array_push($outu, $task->user->id);
                    ?>
                
                  <div class="blockItem">
                    @if(isset($task->user->photo) && file_exists('./uploads/'.$task->user->id.'/'.$task->user->photo->name))
                      <a href="{{ url('/') }}/stuff/tasks/show/{{ $task->id }}?p=tasks"><img class="circle" src="{{ url('/') }}/{{ $task->user->uploads() . $task->user->photo->name }}" alt="{{ $task->title }}"></a>
                    @else
                      <a href="{{ url('/') }}/stuff/tasks/show/{{ $task->id }}?p=tasks"><img class="circle" src="{{ url('/') }}/images/fac_photo.png" alt="{{ $task->title }}"></a>
                    @endif

                    <div class="blockItemData">
                    <h3><a class="elmTitle" href="{{ url('/') }}/stuff/tasks/show/{{ $task->id }}?p=tasks">{{ $user->stripText($task->title, 40) }}</a></h3>
                    <h3><a href="{{ url('/') }}/stuff/tasks/show/{{ $task->id }}?p=tasks">{{ $task->user->fullName }}</a></h3>
                    </div><!-- .blockItemData -->
                  </div><!-- .blockItem -->
                  <?php } ?>
                @endforeach
              </div><!-- .blockContent -->
            </div><!-- .blockSections -->
            
          
          
            <div class="col-xs-12 col-md-6 blockSections blokelements">

              <h2>{{ trans('main.last') }} {{ trans('main.navAdvs') }}</h2>
              <div class="blockContent">
                <?php $i=0; $outu = []; ?>
                @foreach($advs as $adv)
                    <?php if (!in_array($adv->user->id,  $outu) && $i<5){ 
                        $i++;
                        array_push($outu, $adv->user->id);
                    ?>
                
                  <div class="blockItem">
                    @if(isset($adv->user->photo) && file_exists('./uploads/'.$adv->user->id.'/'.$adv->user->photo->name))
                      <a href="{{ url('/') }}/stuff/advs/show/{{ $adv->id }}?p=tasks"><img class="circle" src="{{ url('/') }}/{{ $adv->user->uploads() . $adv->user->photo->name }}" alt="{{ $adv->title }}"></a>
                    @else
                      <a href="{{ url('/') }}/stuff/advs/show/{{ $adv->id }}?p=tasks"><img class="circle" src="{{ url('/') }}/images/fac_photo.png" alt="{{ $adv->title }}"></a>
                    @endif

                    <div class="blockItemData">
                    <h3><a class="elmTitle" href="{{ url('/') }}/stuff/advs/show/{{ $adv->id }}?p=tasks">{{ $user->stripText($adv->title, 40) }}</a></h3>
                    <h3><a href="{{ url('/') }}/stuff/advs/show/{{ $adv->id }}?p=tasks">{{ $adv->user->fullName }}</a></h3>
                    </div><!-- .blockItemData -->
                  </div><!-- .blockItem -->
                  <?php } ?>
                @endforeach
              </div><!-- .blockContent -->
            </div><!-- .blockSections -->
            
          </div><!--.row #bContent-->
          
        