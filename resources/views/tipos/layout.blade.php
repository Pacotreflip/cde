@extends('layout')

@section('content')
  @include('tipos.partials.breadcrumb')

  <h1>{{ $tipo->nombre }}</h1>
  <hr>

  <div class="row">
    <div class="col-md-3">
      @include('tipos.nav')
    </div>

    <div class="col-md-9">
      @yield('main-content')
    </div>
  </div>
@stop
