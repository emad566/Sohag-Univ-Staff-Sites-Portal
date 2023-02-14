
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

    <meta http-equiv="refresh" content="7;url={!! $url !!}" />

    <meta property="og:image" content="{{ url('images/soLogo.png') }}" />

    <link rel="icon" href="{{ url('images/soLogo.png') }}" sizes="32x32" />
    <link rel="icon" href="{{ url('images/soLogo.png') }}" sizes="192x192" />
    <link rel="apple-touch-icon-precomposed" href="{{ url('images/soLogo.png') }}" />
    <meta name="msapplication-TileImage" content="{{ url('images/soLogo.png') }}" />

@endsection

@section('content')   

<div class="alert alert-info">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong>{{ $msg }}</strong>
</div>

@endsection


