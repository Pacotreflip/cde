@extends('layout')

@section('content')
  @include('areas-tipo.partials.breadcrumb')

  <h1>{{ $tipo->nombre }}</h1>
  <hr>

  <div class="row">
    <div class="col-md-3">
      @include('areas-tipo.nav')
    </div>

    <div class="col-md-9">
      @yield('main-content')
    </div>
  </div>
@stop
