@extends('layout')

@section('content')
  <h1>Nueva Transferencia</h1>
  <hr>
  
  <div id="app">
    <transferencias-screen inline-template>
      <form action="{{ route('transferencias.store') }}" method="POST" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group">
          <label for="fecha_transferencia">Fecha:</label>
          <input type="date" name="fecha_transferencia" value="{{ date('Y-m-d') }}" class="form-control" v-model="transferenciaForm.fecha_transferencia">
        </div>

        <div class="form-group">
          {!! Form::label('area_origen', 'Area Origen:') !!}
          {!! Form::select('area_origen', $areas, null, [
            'class' => 'form-control', 'required',
            'v-model' => 'transferenciaForm.area_origen',
            'v-on:change' => 'fetchMateriales']) !!}
        </div>

        <div class="form-group">
          <label for="observaciones">Observaciones:</label>
          <textarea name="observaciones" id="observaciones" rows="5" class="form-control" v-model="transferenciaForm.observaciones"></textarea>
        </div>

        <hr>

        <h2>Inventario Actual</h2>
        
        <div class="text-center" v-if="cargandoInventarios">
          <span class="h4"><i class="fa fa-spinner fa-spin"></i> Cargando inventario...</span>
        </div>

        <div class="alert alert-info" v-if="! tieneInventarios && ! cargandoInventarios">
          <i class="fa fa-warning"></i> El area seleccionada no tiene inventario...
        </div>

        <table class="table table-striped" v-if="tieneInventarios">
          <thead>
            <tr>
              <th>No. Parte</th>
              <th>Descripción</th>
              <th>Unidad</th>
              <th>Existencia</th>
              <th>Cantidad</th>
              <th>Destino</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="material in inventarios">
              <td>@{{ material.numero_parte }}</td>
              <td>@{{ material.descripcion }}</td>
              <td>@{{ material.unidad }}</td>
              <td>@{{ material.existencia }}</td>
              <td>
                <input type="hidden" name="material[@{{ material.id }}][id_material]" value="@{{ material.id }}">
                <input type="text" name="material[@{{ material.id }}][cantidad]" class="form-control"
                       v-model="material.cantidad">
              </td>
              <td>
                <select name="material[@{{ material.id }}][area_destino]" class="form-control"
                        v-model="material.area_destino">
                  <option value="@{{ area.id }}" v-for="area in areas_destino">@{{ area.nombre | depth area.depth }}</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
        
        <app-errors v-bind:form="transferenciaForm"></app-errors>

        <div class="form-group">
          <button class="btn btn-primary" type="submit" v-bind:disabled="transfiriendo" v-on:click="transferir">
            <span v-if="! transfiriendo"><i class="fa fa-check-circle"></i> Transferir</span>
            <span v-if="transfiriendo"><i class="fa fa-spinner fa-spin"></i> Transfiriendo</span>
          </button>
        </div>
      </form>
    </transferencias-screen>
  </div>
@stop