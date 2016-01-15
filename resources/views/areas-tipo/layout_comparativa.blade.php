@extends('layout')

@section('content')
  @include('areas-tipo.partials.breadcrumb')

  <h1>{{ $tipo->nombre }}</h1>
  <hr>

  <div class="row">
    <div class="col-md-12">
      @include('areas-tipo.nav-comparativa')
    </div>
   
  </div>
  <div class="row">
    <div class="col-md-12">
      @yield('main-content')
    </div>
  </div>
@stop
