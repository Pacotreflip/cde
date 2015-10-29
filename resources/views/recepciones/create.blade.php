@extends('layout')

@section('content')
  <h1>Nueva Recepción</h1>
  <hr>
  
  @include('partials.errors')

  <div id="app">
    <recepcion-screen inline-template>
      <form action="{{ route('recepciones.store') }}" method="POST" accept-charset="UTF-8" @submit="recibir">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        
        <div class="row">
          <div class="col-sm-6">
            <!-- Orden Compra Form Input -->
            <div class="form-group">
              {!! Form::label('orden_compra', 'Folio Orden de Compra:') !!}
              {!! Form::select('orden_compra', $compras, null, ['class' => 'form-control', 'required', 
                'v-model' => 'recepcionForm.orden_compra', 'v-on:change' => 'fetchMateriales']) !!}
            </div>
          </div>
          <div class="col-sm-6">
            <!-- Fecha Recepcion Form Input -->
            <div class="form-group">
              {!! Form::label('fecha_recepcion', 'Fecha de Recepción:') !!}
              {!! Form::date('fecha_recepcion', date('Y-m-d'), ['class' => 'form-control', 'required', 
                'v-model' => 'recepcionForm.fecha_recepcion']) !!}
            </div>
          </div>
        </div>

        <!-- Area de Almacenamiento Form Input -->
        <div class="form-group">
            {!! Form::label('area_almacenamiento', 'Area de Almacenamiento:') !!}
            {!! Form::select('area_almacenamiento', $areas, null, ['class' => 'form-control', 
              'v-model' => 'recepcionForm.area_almacenamiento']) !!}
        </div>

        <div class="row">
          <div class="col-sm-6">
            <!-- Persona que Recibe Form Input -->
            <div class="form-group">
              {!! Form::label('persona_recibio', 'Persona que Recibe:') !!}
              {!! Form::text('persona_recibio', auth()->user()->present()->nombreCompleto, ['class' => 'form-control', 'v-model' => 'recepcionForm.persona_recibio']) !!}
            </div>

            <!-- Observaciones Form Input -->
            <div class="form-group">
              {!! Form::label('observaciones', 'Observaciones:') !!}
              {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 5, 'v-model' => 'recepcionForm.observaciones']) !!}
            </div>
          </div>
          <div class="col-sm-6">
            <!-- No. de Remision ó Factura Form Input -->
            <div class="form-group">
              {!! Form::label('numero_remision_factura', 'No. de Remision ó Factura:') !!}
              {!! Form::text('numero_remision_factura', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.numero_remision_factura']) !!}
            </div>
            
            <!-- Orden Embarque Form Input -->
            <div class="form-group">
              {!! Form::label('orden_embarque', 'Orden de Embarque:') !!}
              {!! Form::text('orden_embarque', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.orden_embarque']) !!}
            </div>

            <!-- No. de Pedido Form Input -->
            <div class="form-group">
              {!! Form::label('numero_pedimento', 'No. de Pedimento:') !!}
              {!! Form::text('numero_pedimento', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.numero_pedimento']) !!}
            </div>
          </div>
        </div>

        <section class="orden-compra" v-cloak>
          <span v-if="cargando"><i class="fa fa-2x fa-spinner fa-spin"></i> Cargando articulos...</span>
          
          
          <section class="orden-compra-content" v-show="compra.materiales.length">
            <section class="orden-compra-heading">
              <h3>@{{ compra.proveedor.razon_social }} - <strong>O/C # @{{ compra.numero_folio }}</strong></h3>
            </section>
            <section class="orden-compra-materiales">
              <table class="table table-striped table-condensed">
                <thead>
                  <tr>
                    <th>No. Parte</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Comprado</th>
                    <th>Recibido</th>
                    <th>A Recibir</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="material in compra.materiales">
                    <td>@{{ material.numero_parte }}</td>
                    <td>@{{ material.descripcion }}</td>
                    <td>@{{ material.unidad }}</td>
                    <td>@{{ material.cantidad_adquirida }}</td>
                    <td>@{{ material.cantidad_recibida }}</td>
                    <td>
                        <input type="text" name="materiales[@{{ material.id }}][cantidad]" 
                          class="form-control input-sm"
                          placeholder="Cantidad"
                          value="@{{ material.cantidad_recibir }}"
                          v-model="material.cantidad_recibir">
                    </td>
                  </tr>
                </tbody>
              </table>
            </section>
          </section>
        </section>
        
        <hr>
        
        <app-errors v-bind:form="recepcionForm"></app-errors>
        
        <div class="form-group">
          <button class="btn btn-primary" type="submit" v-bind:disabled="recibiendo" @click="recibir">
            <span v-if="! recibiendo"><i class="fa fa-check-circle"></i> Recibir Artículos</span>
            <span v-if="recibiendo"><i class="fa fa-spinner fa-spin"></i> Recibiendo Articulos</span>
          </button>
        </div>
      </form>
    </recepcion-screen>
  </div>
@stop