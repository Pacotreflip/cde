@extends('layout')

@section('content')
  <h1>Nueva Recepción</h1>
  <hr>
  
  @include('partials.errors')
  <div id="app">
    <recepcion-screen inline-template>

    <div>
      <form action="{{ route('recepciones.store') }}" method="POST" accept-charset="UTF-8" @submit="recibir">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        
        <div class="row">
          <div class="col-sm-6">
            <div class="row">
              <div class="col-xs-6">
                <!-- Orden Compra Form Input -->
                <div class="form-group">
                  {!! Form::label('orden_compra', '*Folio Orden de Compra:') !!}
                  {!! Form::select('orden_compra', $compras, $id_oc, ['class' => 'form-control', 'required', 
                    'v-model' => 'recepcionForm.orden_compra', 'v-on:change' => 'fetchMateriales']) !!}
                </div>
              </div>
              <div class="col-xs-6">
                <!-- Fecha Recepcion Form Input -->
                <div class="form-group">
                  {!! Form::label('fecha_recepcion', '*Fecha de Recepción:') !!}
                  {!! Form::date('fecha_recepcion', date('Y-m-d'), ['class' => 'form-control', 'required', 
                    'v-model' => 'recepcionForm.fecha_recepcion']) !!}
                </div>
              </div>
            </div>

            <!-- Persona que Recibe Form Input -->
            <div class="form-group">
              {!! Form::label('persona_recibio', '*Persona que Recibe:') !!}
              {!! Form::text('persona_recibio', auth()->user()->present()->nombreCompleto, ['class' => 'form-control', 'v-model' => 'recepcionForm.persona_recibio']) !!}
            </div>

            <!-- No. de Remision ó Factura Form Input -->
            <div class="form-group">
              {!! Form::label('numero_remision_factura', 'No. de Remisión ó Factura:') !!}
              {!! Form::text('numero_remision_factura', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.numero_remision_factura']) !!}
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="col-sm-6">
                <!-- Orden Embarque Form Input -->
                <div class="form-group">
                  {!! Form::label('orden_embarque', 'Orden de Embarque:') !!}
                  {!! Form::text('orden_embarque', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.orden_embarque']) !!}
                </div>
              </div>
              <div class="col-sm-6">
                <!-- No. de Pedido Form Input -->
                <div class="form-group">
                  {!! Form::label('numero_pedimento', 'No. de Pedimento:') !!}
                  {!! Form::text('numero_pedimento', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.numero_pedimento']) !!}
                </div>
              </div>
            </div>

            <!-- Observaciones Form Input -->
            <div class="form-group">
              {!! Form::label('observaciones', 'Observaciones:') !!}
              {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 5, 'v-model' => 'recepcionForm.observaciones']) !!}
            </div>
          </div>
        </div>

        <h4>¿Como recibirá los articulos?</h4>
        <div class="form-group">
          <label class="radio-inline">
            <input type="radio" id="opcion1" name="opcion_recepcion" value="almacenar" checked v-model="recepcionForm.opcion_recepcion"> Se Almacenan
          </label>
          <label class="radio-inline">
            <input type="radio" id="opcion2" name="opcion_recepcion" value="asignar" v-model="recepcionForm.opcion_recepcion"> Se Asignan
          </label>
        </div>
      </form>

      <section class="OrdenCompra" v-cloak>
        <span v-if="cargando"><i class="fa fa-2x fa-spinner fa-spin"></i> Cargando articulos...</span>
        
        <section class="OrdenCompra__content" v-show="compra.materiales.length">
          <section class="OrdenCompra__heading">
            <h3>@{{ compra.proveedor.razon_social }} - <strong>O/C # @{{ compra.numero_folio }}</strong></h3>
            <hr>
          </section>

          <section class="OrdenCompra__items">
            <table class="table table-striped table-condensed">
              <thead>
                <tr>
                  <th></th>
                  <th>No. Parte</th>
                  <th>Descripción</th>
                  <th>Unidad</th>
                  <th>Comprado</th>
                  {{-- <th>Recibido</th> --}}
                  <th>Por Recibir</th>
                  <th>A Recibir</th>
                  <th></th>
                </tr>
              </thead>
              <tbody
                  v-for="material in compra.materiales"
                  v-bind:class="{ 'danger': cantidadARecibir(material) > material.cantidad_por_recibir }"
              >
                <tr>
                  <td>
                    <i
                      class="fa fa-check-circle text-success"
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Artículo totalmente recibido"
                      v-show="! material.cantidad_por_recibir"
                    ></i>
                    <i
                      class="fa fa-exclamation-circle text-danger"
                      data-toggle="tooltip"
                      data-placement="top"
                      title="La cantidad a recibir es mayor a la pendiente"
                      v-show="cantidadARecibir(material) > material.cantidad_por_recibir"
                    ></i>
                  </td>
                  <td>@{{ material.numero_parte }}</td>
                  <td>@{{ material.descripcion }}</td>
                  <td>@{{ material.unidad }}</td>
                  <td>@{{ material.cantidad_adquirida }}</td>
                  <td>@{{ material.cantidad_por_recibir }}</td>
                  <td>
                    <strong>@{{ cantidadARecibir(material) }}</strong>
                  </td>
                  <td>
                    <selector-destinos
                      v-show="material.cantidad_por_recibir"
                      v-if="recepcionForm.opcion_recepcion == 'almacenar'"
                      v-bind:destinos.sync="material.destinos"
                      v-bind:material="material"
                    ></selector-destinos>
                    <div 
                        v-show="material.cantidad_por_recibir"
                        v-if="recepcionForm.opcion_recepcion == 'asignar'">
                        <button v-on:click="fetchDestinos(material)" v-el:showListButton class="btn btn-success btn-xs">
                            
                            <span v-if="material.recibiendo == true"><i class="fa fa-spinner fa-spin"></i> Cargando...</span>
                            <span v-else>Asignar destinos</span>
                        </button>                        
                    </div>
                  </td>  
                </tr>
                <tr class="success" v-el:destinosList
                    v-if="recepcionForm.opcion_recepcion == 'asignar'"
                    v-for="destino in material.areas_destino">
                    <td colspan = "7" align="right"><strong>@{{destino.ruta}}</strong> (Pendientes @{{destino.requiere}})</td>
                    <td><input v-on:keyup="setDestino(destino, material)" v-model="destino.cantidad" type="number" class="form-control"></td>
                </tr>
              </tbody>
            </table>
             {{-- <pre>
              @{{ $data.compra.materiales | json }}
            </pre> --}}
          </section>
        </section>
      </section>
      
      <hr>
      
      <app-errors v-bind:form="recepcionForm"></app-errors>
      
      <div class="form-group">
        <button class="btn btn-primary" type="submit" v-bind:disabled="recibiendo" @click="confirmaRecepcion">
          <span v-show="recibiendo"><i class="fa fa-spinner fa-spin"></i> Recibiendo Articulos</span>
          <span v-else><i class="fa fa-check-circle"></i> Recibir Artículos</span>
        </button>
      </div>
      </div>
    </recepcion-screen>
  </div>
@stop
