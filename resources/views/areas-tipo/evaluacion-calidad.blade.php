@extends('areas-tipo.layout')

@section('main-content')
  <form action="{{ '/areas-tipo/'.$tipo->id.'/evaluacion-calidad' }}" method="POST" accept-charset="UTF-8">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
  
    <h4>Artículos que Pueden Evaluarse</h4>
    <table class="table table-striped table-condensed">
      <thead>
        <tr>
          <th>No. Parte</th>
          <th>Descripción</th>
          <th>Se Evalua?</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tipo->materialesRequeridos as $material)
          <tr>
            <td>{{ $material->material->numero_parte }}</td>
            <td>{{ str_limit($material->material->descripcion, 70) }}</td>
            <td>
              <label class="radio-inline">
                {!! Form::radio('materiales['.$material->id_material.'][se_evalua]', 1, $material->se_evalua) !!} Si
              </label>
              <label class="radio-inline">
                {!! Form::radio('materiales['.$material->id_material.'][se_evalua]', 0, !$material->se_evalua) !!} No
              </label>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="form-group">
        {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
    </div>
  </form>
@stop
