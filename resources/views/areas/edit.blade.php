@extends('layout')

@section('content')
@include('areas.partials.breadcrumb', ['ancestros' => $area->getAncestors()])

<h1>Área</h1>
<hr>

{!! Form::model($area, ['route' => ['areas.update', $area], 'method' => 'PATCH']) !!}
@include('areas.partials.edit-fields')
{!! Form::close() !!}

<hr>

@if(count($area->materialesRequeridos)>0)
<table class="table table-condensed table-striped">
    <caption><h3>Artículos Requeridos ({{count($area->materialesRequeridos)}})</h3></caption>
    <thead>
        <tr>
            <th style="width: 20px">Relacionado a Área Tipo*</th>
            <th>No. Parte</th>  
            <th>Descripción</th>
            <th>Unidad</th>
            <th>Cantidad Requerida</th>
            <th>Cantidad Asignada</th>
            <th>Asignaciones Validadas</th>
        </tr>
    </thead>
    <tbody>
        
        @foreach($area->materialesRequeridos as $material)
        <tr>
            <td style="text-align: center">
                @if($material->id_material_requerido > 0)
                Si
                @else
                No
                @endif
            </td>
            <td>{{ $material->material->numero_parte }}</td>
            <td>
                <span data-toggle="tooltip" data-placement="top" title="{{ $material->material->descripcion }}">
                    {{ str_limit($material->material->descripcion, 60) }}
                </span>
            </td>
            <td>{{ $material->material->unidad }}</td>
            <td style="text-align: right">{{ $material->cantidad_requerida }}</td>
            <td style="text-align: right">
                @if($area->materialesAsignados()->where("id_material", $material->id_material)->first())
                {{ $area->materialesAsignados()->where("id_material", $material->id_material)->sum('cantidad_asignada') }}
                @endif
            </td>
            <td style="text-align: right">{{ $material->cantidadAsignacionesValidadas() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="alert alert-info" role="alert">
    <h4><i class="fa fa-fw fa-exclamation"></i>*:</h4>
    <p>
        Si el material requerido del área esta asociado al área tipo éste se modificará / eliminará si se modifica o elimina en la sección de artículos requeridos del área tipo. 
    </p>
</div>
@endif


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