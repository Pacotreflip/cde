@extends('layout')

@section('content')
  @include('areas.partials.breadcrumb', ['ancestros' => $area->getAncestors()])

  <h1>Área</h1>
  <hr>

  {!! Form::model($area, ['route' => ['areas.update', $area], 'method' => 'PATCH']) !!}
    @include('areas.partials.edit-fields')
  {!! Form::close() !!}

  <hr>
  
  {{-- <ul class="nav nav-tabs">
    <li role="presentation" class="active"><a href="#">Articulos Requeridos</a></li>
    <li role="presentation"><a href="#">Articulos Adquiridos</a></li>
    <li role="presentation"><a href="#">Articulos Almacenados</a></li>
  </ul> --}}

  @if($area->tipo)
    <table class="table table-condensed table-striped">
      <thead>
        <tr>
          <th>Descripción</th>
          <th>Unidad</th>
          <th>Cantidad Requerida</th>
        </tr>
      </thead>
      <tbody>
        @foreach($area->tipo->materiales as $material)
          <tr>
            <td>
              <span data-toggle="tooltip" data-placement="top" title="{{ $material->descripcion }}">
                {{ str_limit($material->descripcion, 60) }}
              </span>
            </td>
            <td>{{ $material->unidad }}</td>
            <td>{{ $material->pivot->cantidad }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  <hr>
  
  <div class="alert alert-danger" role="alert">
    <h4><i class="fa fa-fw fa-exclamation"></i>Atención:</h4>
    <p>
      Al borrar esta area, todas las subareas contenidas también seran borradas.
    </p>
    <p>
      {!! Form::open(['route' => ['areas.delete', $area], 'method' => 'DELETE']) !!}
        {!! Form::submit('Borrar esta área', ['class' => 'btn btn-danger']) !!}
      {!! Form::close() !!}
    </p>
  </div>
@stop

@section('scripts')
  <script>
    $('[data-toggle="tooltip"]').tooltip();
  </script>
@stop