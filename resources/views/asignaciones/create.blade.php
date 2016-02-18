@extends('layout')

@section('content')
    <h1>Nueva Asignación de Artículos</h1>
    <hr>
  <div id="app">
    <global-errors></global-errors>

    <asignacion-screen inline-template>
        <div>
      <form action="{{ route('asignaciones.store') }}" method="POST" accept-charset="UTF-8" @submit="asignar">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
      <div class="row">
        <div class="col-md-3">
          <areas-tree v-bind:when-change="fetchMateriales"></areas-tree>
        </div>
        <div class="col-md-9">
          <table class="table table-condensed">
              <caption><div >@{{ruta_area}}</div></caption>
            <thead>
              <tr>
                <th>#Parte</th>
                <th>Descripción</th>
                <th>Unidad</th>
                <th>Almacenados</th>
                <th>Esperados</th>
                <th>Asignandos</th>
                <th>Destinos Seleccionados</th>
                <th>Destino(s)</th>
              </tr>
            </thead>

            <tbody v-for="material in asignacionForm.materiales">
              <tr>
                <td>@{{ material.numero_parte }}</td>
                <td><strong>@{{ material.descripcion }}</strong></td>
                <td>@{{ material.unidad }}</td>
                <td>@{{ material.existencia }}</td>
                <td>@{{ material.esperados }}</td>
                <td>@{{ material.asignados }}</td>
                <td>@{{ material.destinos.length }}</td>
                <td>
                  <areas-modal-tree 
                    v-bind:material="material"
                    v-bind:when-selected="setDestinos" 
                    v-bind:limit="parseInt(material.existencia, 10)">
                  </areas-modal-tree>
                </td>
              </tr>

              <tr v-for="destino in material.destinos" class="active">
                <td colspan="7" class="text-right">
                  <strong>@{{ destino.path }}</strong> 
                </td>
                <td>
                  <input type="text" class="input-sm form-control" placeholder="cantidad" v-model="destino.cantidad">
                </td>
              </tr>
            </tbody>
          </table>
          
          <div class="text-center" v-show="cargando">
            <i class="fa fa-fw fa-2x fa-spin fa-spinner"></i> Cargado inventario...
          </div>
        </div>
      </div>
      </form>
      <hr>

      <app-errors v-bind:form="asignacionForm"></app-errors>
      
      <div class="form-group">
        <button class="btn btn-primary" type="submit" v-bind:disabled="asignando" @click="confirmaAsignacion">
          <span v-show="asignando"><i class="fa fa-spinner fa-spin"></i> Asignando Articulos</span>
          <span v-else><i class="fa fa-check-circle"></i> Asignar Artículos</span>
        </button>
      </div>
      
      <pre>
        @{{ $data.asignacionForm.errors | json 4 }}
      </pre>
      
        </div>
    </asignacion-screen>
  </div>
@stop