@extends('layout')

@section('content')
    <h1>Nueva Asignación de Artículos</h1>
    <hr>
  <div id="app">
    <global-errors></global-errors>

    <asignacion-screen inline-template>
      <div class="row">
        <div class="col-md-3">
          <areas-tree when-selected="@{{ fetchMateriales }}"></areas-tree>
        </div>
        <div class="col-md-9">
          <table class="table table-condensed">
            <thead>
              <tr>
                <th>#Parte</th>
                <th>Descripción</th>
                <th>Unidad</th>
                <th>Existencia</th>
                <th>Asignando</th>
                <th>Destino(s)</th>
              </tr>
            </thead>

            {{-- <tbody v-show="asignacionForm.materiales.length"> --}}
            <tbody v-repeat="material in asignacionForm.materiales">
              <tr>
                <td>@{{ material.numero_parte }}</td>
                <td><strong>@{{ material.descripcion }}</strong></td>
                <td>@{{ material.unidad }}</td>
                <td>@{{ material.existencia }}</td>
                <td>@{{ material.destinos.length }}</td>
                <td>
                  <areas-modal-tree 
                    material="@{{ material }}"
                    when-selected="@{{ setDestinos }}" 
                    limit="@{{ parseInt(material.existencia, 10) }}">
                  </areas-modal-tree>
                </td>
              </tr>

              <tr v-repeat="destino in material.destinos" class="active">
                <td colspan="5" class="text-right">
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
      
      <hr>

      <app-errors form="@{{ asignacionForm }}"></app-errors>
      
      <div class="form-group">
        <button class="btn btn-primary" type="submit" v-attr="disabled: asignando" v-on="click: asignar">
          <span v-if="! asignando"><i class="fa fa-check-circle"></i> Asignar Artículos</span>
          <span v-if="asignando"><i class="fa fa-spinner fa-spin"></i> Asignando Articulos</span>
        </button>
      </div>
      
      <pre>
        @{{ $data.asignacionForm.errors | json 4}}
      </pre>
    </asignacion-screen>
  </div>
@stop