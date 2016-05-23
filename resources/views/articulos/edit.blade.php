@extends('layout')

@section('content')
  <ol class="breadcrumb">
    <li><a href="{{ route('articulos.index') }}">Artículos</a></li>
    <li class="active">{{ $material->descripcion }}</li>
  </ol>

  <h1>Artículo</h1>
  <hr>

  <div class="row">
    <div class="col-md-6">
      @include('partials.errors')
      {!! Form::model($material, ['route' => ['articulos.update', $material], 'method' => 'PATCH', 'files' => true]) !!}
        @include('articulos.partials.edit-fields')
      {!! Form::close() !!}
      <br>
    </div>
    <div class="col-md-6 gallery">
        <div class="row">
      @include('articulos.partials.fotos')
        </div>
        <div class="row">
      <h3>Orden de Compra</h3>
      <hr>
      <ul>
      @foreach($ordenes_compra as $orden_compra)
      <li>
          <a href="{{ route('compras.show', $orden_compra->id_transaccion) }}">{{ $orden_compra->present()->numero_folio }}</a>
          
      </li>
      @endforeach
      </ul>
        </div>
    </div>
  </div>

  <hr>

  <form action="{{ route('articulos.fotos', [$material]) }}" class="dropzone" 
    id="dropzone" method="POST" enctype="multipart/form-data">
      {{ csrf_field() }}
  </form>

  <br>
  <hr>
  <div class="row">
      <div class="col-md-6">
          <div class="row">
              <div class="col-md-6">
                  <h4><strong>Estado de suministro:</strong>
              </div>
              <div class="col-md-6">
                  <h4>
                  <small class="text-muted">
          [
          Articulos Esperados: {{$material->getTotalEsperado($idobra)}}  
          Articulos Suministrados: {{$material->getTotalRecibido()}}
          ]</small>
                  </h4> </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                @if($material->getTotalEsperado($idobra) > 0)
                
                
                <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped{{ round($material->porcentaje_suministro($idobra)) == 100 ? ' progress-bar-success' : '' }}" 
                      role="progressbar"
                      aria-valuenow="{{ $material->porcentaje_suministro($idobra) }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round($material->porcentaje_suministro($idobra)) }}%;">
                      {{ round($material->porcentaje_suministro($idobra)) }}%
                    </div>
                  </div>
          
                @endif
              </div>
              
          
      </div>
      
      </div>
      
      
      
      
      
      
      
      
      <div class="col-md-6">
          <div class="row">
              <div class="col-md-6">
                 <h4>
                        <strong>Estado de asignacion:</strong> 
                    </h4>
              </div>
              <div class="col-md-6">
                  <h4>
                  <small class="text-muted">
          [
          Articulos Requeridos: {{$material->cantidad_esperada()}}  
          Articulos Asignados: {{$material->cantidad_asignada()}}
          ]</small>
                  </h4> </div>
          </div>
          
           <div class="row">  
               <div class="col-md-12">
                   @if($material->cantidad_esperada() > 0)
          
                   
                   <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped{{ round($material->porcentaje_asignacion()) == 100 ? ' progress-bar-success' : '' }}" 
                      role="progressbar"
                      aria-valuenow="{{ $material->porcentaje_asignacion() }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round($material->porcentaje_asignacion()) }}%;">
                      {{ round($material->porcentaje_asignacion()) }}%
                    </div>
                  </div>
                   
                   
                   
                   @endif
               </div>
           </div>
      
      </div>
      
      
      
      
      
      
      
      
      
      
  </div>
  
  
    <hr>
  
  
  <div class="row">
      @if(count($material->areas_requerido()))
      <div class="col-md-5">
      <h3>Requerido / Asignado En:</h3>
<hr>
<br>

<table class="table table-condensed table-striped">
    <thead>
        <tr>
            <th>Área</th>  
            <th>Cantidad Requerida</th>
            <th>Cantidad Asignada</th>
            <th>Cantidad Pendiente</th>
        </tr>
    </thead>
    <tbody>
        @foreach($material->areas_requerido() as $area)
        <tr>
            <td>{{$area->ruta}}</td>
            <td style="text-align: right">{{$area->cantidad_requerida($material->id_material)}}</td>
            <td style="text-align: right">{{$area->cantidad_asignada($material->id_material)}}</td>
            <td style="text-align: right">{{$area->cantidad_requerida($material->id_material) - $area->cantidad_asignada($material->id_material)}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    </div>
      @endif
      @if(count($material->areas_almacenacion()))
      
      
      <div class="col-md-5">
      <h3>Almacenado en: </h3>
<hr>
<br>
<table class="table table-condensed table-striped">
    <thead>
        <tr>
            <th>Área</th>  
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        @foreach($material->areas_almacenacion() as $area)
        <tr>
            <td>{{$area->ruta}}</td>
            <td style="text-align: right">{{$area->getInventarioDeMaterial($material)->cantidad_existencia}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
    </div>
      @endif
  </div>
  
@stop

@section('scripts')
  <script>
    Dropzone.options.dropzone = {
      paramName: "foto",
      dictDefaultMessage: "<h2 style='color:#bbb'><span class='glyphicon glyphicon-picture' style='padding-right:5px'></span>Arraste las fotografías a esta zona para asociarlas al artículo.</h2>"
    };
  </script>
@stop