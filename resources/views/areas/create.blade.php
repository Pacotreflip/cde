@extends('layout')

@section('content')
  <ol class="breadcrumb">
    <li>
      <a href="{{ route('areas.index', Request::has('dentro_de') ? ['area' => Request::get('dentro_de')] : []) }}">Áreas</a>
    </li>
    <li class="active">Nueva Área</li>
  </ol>

  <h1>Nueva Área</h1>
  <hr>
  
  {!! Form::open(['route' => ['areas.store'], 'method' => 'POST']) !!}
    @include('areas.partials.create-fields')
  {!! Form::close() !!}

  @include('partials.errors')
@stop

@section('scripts')
<script>
    $(document).ready(function () {
        $("#radio_es_almacen").buttonset();
    });
    
</script>
@stop