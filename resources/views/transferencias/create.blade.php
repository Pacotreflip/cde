@extends('layout')
@section('styles')
<style>
select {
  min-width: 300px;
}
</style>
@stop
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
        <hr>
         <div class="alert alert-info">
          <i class="fa fa-warning"></i> Teclee el nombre o número de parte del artículo, posteriormente seleccionelo de la lista para cargar automáticamente las áreas que lo tienen almacenado.
        </div>
        <div class="row">
          <div class="col-md-6">
             <div class="form-group">
                 <label>Artículo</label>
                 <div class="input-group">
              <input id="filtro" type="text" class="form-control" placeholder="Teclee el nombre o número de parte del artículo">
              <span class="input-group-btn">
                <button id="buscarArticulo" type="Filtrar Áreas" class="btn btn-primary">Filtrar Áreas</button>
              </span>
                 </div>
            </div>
          </div>
         
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('area_origen', 'Area Origen:') !!}
              <select class="form-control" required="required" v-model="transferenciaForm.area_origen" v-on:change="fetchMateriales" id="area_origen" name="area_origen">
                <option value="" selected="selected" disabled="">-- SELECCIONAR --</option>
                <div id="options">
                  @foreach($areas as $area)
                  <option value="{{$area['id']}}">{{$area['ruta']}}</option>
                  @endforeach
                </div>
              </select>
            </div>  
          </div>
        </div>
        
        <hr>
        
        <div class="form-group">
          <label for="observaciones">Observaciones:</label>
          <textarea name="observaciones" id="observaciones" rows="5" class="form-control" v-model="transferenciaForm.observaciones"></textarea>
        </div>
        <div class="row">
        <div class="col-md-1 col-md-offset-8 form-group">
            <label for="chkrecibe">¿Alguien recíbe?:</label>
            <input name="chkrecibe" id="chkrecibe" type="checkbox" v-model="recibe">
        </div>
        <div class="col-md-3 form-group" >        
            <label for="recibe">Persona que recíbe:</label>
            <input v-if="recibe" value="" id="recibe" type="text" class="form-control" name="recibe" v-model="transferenciaForm.recibe">
            <input v-else type="text" value="" v-model="transferenciaForm.recibe" class="form-control" disabled>
        </div>
        </div>
        <hr>

        <h2>Inventario Actual</h2>
        
        <div class="text-center" v-if="cargandoInventarios">
          <span class="h4"><i class="fa fa-spinner fa-spin"></i> Cargando inventario...</span>
        </div>

        <div class="alert alert-info" v-if="! tieneInventarios && ! cargandoInventarios">
          <i class="fa fa-warning"></i> No se ha seleccionado ningún inventario...
        </div>

        <table class="table table-striped" v-if="tieneInventarios">
          <thead>
            <tr>
              <th>No. Parte</th>
              <th>Descripción</th>
              <th>Unidad</th>
              <th>Existencia</th>
              <th>Cantidad</th>
              <th style="width: 20%">Destino</th>
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
                <select style="width: 100%" v-select="material.area_destino" name="material[@{{ material.id }}][area_destino]" class="form-control"
                      v-model="material.area_destino">
                  <option value="@{{ area.id }}" v-for="area in areas_destino">@{{ area.clave }} @{{ area.nombre | depth area.depth }}</option>
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
@section('scripts')   
<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-Token': App.csrfToken
        }
    });
    $("#filtro").autocomplete({
        source: function( request, response ) {
          $.ajax({
              type: 'GET',
              url: "{{ route('transferir.materiales') }}",
              dataType: "jsonp",
              data: {
                q: request.term
              },
              success: function( data ) {
                response( data );
              }
          });
        },
        select: function(){
            $.ajax({
                type: 'POST',
                url: "{{ route('transferir.filtrar') }}",
                data: {b: $('#filtro').val()},
                dataType  : 'JSON',
                beforeSend: function() {
                    $('#area_origen').empty();
                },
                success: function(data) {
                    $('#area_origen').append('<option value="" disabled selected>-- SELECCIONAR --</option>');
                    data.forEach(function(area){
                        $('#area_origen').append('<option value="'+ area.id_area +'">'+ area.ruta + '</option>');
                    });
                },
                afterSend: function(data) {
                    console.log(data);
                },
                error: function(xhr, responseText, thrownError) {   
                    console.log(responseText); 
                }
            });
        }
    });               
    $('#buscarArticulo').off().on('click', function (e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('transferir.filtrar') }}",
            data: {b: $('#filtro').val()},
            dataType  : 'JSON',
            beforeSend: function() {
                $('#area_origen').empty();
            },
            success: function(data) {
                $('#area_origen').append('<option value="" disabled selected>-- SELECCIONAR --</option>');
                data.forEach(function(area){
                    $('#area_origen').append('<option value="'+ area.id_area +'">'+ area.ruta + '</option>');
                });
            },
            error: function(xhr, responseText, thrownError) {   
                console.log(responseText); 
    }
        });
    });
</script>
@stop