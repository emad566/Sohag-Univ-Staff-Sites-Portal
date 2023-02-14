{{App::setLocale($user->lang)}}
@extends('layouts.stuff')

@section('headMeta')
    <title>{{ $user->fullName }}</title>

    <!-- facebook meta -->
    @if(isset($user->photo))
        <meta property="og:image" content="{{ url($user->uploads()) }}/{{ $user->photo->name }}" />

        <link rel="icon" href="{{ url($user->uploads()) }}/{{ $user->photo->name }}" sizes="32x32" />
        <link rel="icon" href="{{ url($user->uploads()) }}/{{ $user->photo->name }}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{ url($user->uploads()) }}/{{ $user->photo->name }}" />
        <meta name="msapplication-TileImage" content="{{ url($user->uploads()) }}/{{ $user->photo->name }}" />
    @else
        <meta property="og:image" content="{{ url('images/fac_photo.png') }}" />
    @endif

    <meta property="og:title" content="{{ $user->name }}"/> 
    <?php 
        $webIntro = new \voku\Html2Text\Html2Text($user->webIntro); 
        $webIntro = strip_tags($webIntro->getText())
    ?>
    <meta property="og:description" content="{{ substr($webIntro, 0, 300) . '...' }}" />

@endsection

@section('stuffContent')
<div class="panel panel-default">
    <div class="panel-heading">{{ trans('main.cvBtnEdit') }} <a class="btn btn-primary pull-left" target="_blank" href="{{ url('/cv/'.$user->id).'?p=cv'}}"> <i class="fas fa-eye"></i> {{ trans('main.gView') }} </a> </div>
    <div class="panel-body panelBodyForm">
        @include("partials.success")
        
        <form id='CreateUser' class='CreateUser form-horizontal panelForm' method='POST' action="{{ url('cv/' . $user->id ) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='PUT'>


            <div class="form-group{{ $errors->has('contentCV') ? ' has-error' : '' }}">
                <label for="contentCV"> {{ trans('main.user-contentCV') }} </label>
                <textarea name="contentCV" id="contentCV" class="form-control contentCV editor" placeholder="">{{ $user->contentCV }}</textarea>
                @if ($errors->has('contentCV'))
                    <span class="help-block">
                        <strong>{{ $errors->first('contentCV') }}</strong>
                    </span>
                @endif
            </div>
           

            <div class="attached alert alert-success">
                    <h4>{{ trans('main.gAttach') }}</h4>
                    <hr/>
                    <ul style="list-style: none;">
                        @foreach($user->files as $file)
                            <li>
                                    <label for="delId{{ $file->id }}">
                                        <input type="checkbox" value="{{ $file->id }}" name="delId[]" id="delId{{ $file->id }}"> {{ trans('main.delete') }} :- 
                                        <a target="_blank" href="{{ url($user->uploads() . $file->name ) }}">{{ $file->name }}</a>
                                    </label>
                            </li>
                        @endforeach
                    </ul>
                </table>
            </div>

            
        </form><!--#CreateUser .CreateUser --> 
        
        <label for="file_id">{{ trans('main.gFile') }}  
        </label>
        <form id='file_id' class='file_id dropzone' method='POST' action='{{ route('files.store', [])}}' enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type='hidden' name='_method' value='POST'>
            <input type='hidden' name='fileable_id' value='{{ $user->id }}'>
            <input type='hidden' name='uploads_id' value='{{ $user->uploads() }}'>
            <input type='hidden' name='fileable_type' value='App\user'>

            <div class="dz-default dz-message"><span>{{ trans('main.gFileMsg') }}</span></div>
            
        </form><!--#file_id .file_id -->

        <button id="submitFormCreate" type="submit" form="CreateUser" class="btn btn-primary">{{ trans('main.gSave') }}</button>
        

    </div><!-- .panel-body  -->
</div><!-- .panel panel-default  -->
@endsection