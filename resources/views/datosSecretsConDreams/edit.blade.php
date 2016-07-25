@extends('layout')

@section('content')
  <ol class="breadcrumb">
    <li><a href="{{ route('datosSecretsConDreams.index') }}">Datos Secrets Con Dreams</a></li>
    <li class="active">{{ $dato->id }}</li>
  </ol>

  <h1>Datos</h1>
  <hr>
  
  {!! Form::model($dato, array('route' => array('datosSecretsConDreams.update', $dato->id), 'method' => 'PUT')) !!}
  <div class="row">
        <div class="form-group col-xs-3">
            <label for="no">no:</label>
            {!! Form::number('no', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="proveedor">proveedor:</label>
            {!! Form::text('proveedor', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="no_oc">no_oc:</label>
            {!! Form::text('no_oc', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="descripcion_producto_oc">descripcion_producto_oc:</label>
            {!! Form::text('descripcion_producto_oc', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_familia', 'familia:') !!}
            {!! Form::select('id_familia', $familias,null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE FAMILIA --']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_area_secrets', 'area_secrets:') !!}
            {!! Form::select('id_area_secrets', $areas_secrets, null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE AREA SECRETS --']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_area_reporte', 'area_reporte:') !!}
            {!! Form::select('id_area_reporte', $areas_reporte, null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE AREA REPORTE --']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_tipo', 'tipo:') !!}
            {!! Form::select('id_tipo', $tipos, null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE TIPO --']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_moneda_original', 'moneda_original:') !!}
            {!! Form::select('id_moneda_original', $monedas, null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE MONEDA ORIGINAL --']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="cantidad_comprada">cantidad_comprada:</label>
            {!! Form::text('cantidad_comprada', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="recibidos_por_factura">recibidos_por_factura:</label>
            {!! Form::text('recibidos_por_factura', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="unidad">unidad:</label>
            {!! Form::text('unidad', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="precio">precio:</label>
            {!! Form::text('precio', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="moneda">moneda:</label>
            {!! Form::text('moneda', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="importe_sin_iva">importe_sin_iva:</label>
            {!! Form::text('importe_sin_iva', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_factura">fecha_factura:</label>
            {!! Form::text('fecha_factura', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="factura">factura:</label>
            {!! Form::text('factura', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_pago">fecha_pago:</label>
            {!! Form::text('fecha_pago', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="area_amr">fecha_amr:</label>
            {!! Form::text('area_amr', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_entrega">fecha_entrega:</label>
            {!! Form::text('fecha_entrega', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="pesos">pesos:</label>
            {!! Form::text('pesos', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="dolares">dolares:</label>
            {!! Form::text('dolares', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="euros">euros:</label>
            {!! Form::text('euros', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="consolidado_dolares">consolidado_dolares:</label>
            {!! Form::text('consolidado_dolares', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="id_material_secrets">id_material_secrets:</label>
            {!! Form::number('id_material_secrets', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="proveedor_dreams">proveedor_dreams:</label>
            {!! Form::text('proveedor_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="no_oc_dreams">no_oc_dreams:</label>
            {!! Form::text('no_oc_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="descripcion_producto_oc_dreams">descripcion_producto_oc_dreams:</label>
            {!! Form::text('descripcion_producto_oc_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_familia_dreams', 'familia_dreams:') !!}
            {!! Form::select('id_familia_dreams', $familias, null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE FAMILIA DREAMS --']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_area_dreams', 'area_dreams:') !!}
            {!! Form::select('id_area_dreams', $areas_dreams, null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE AREA DREAMS --']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_area_reporte_p_dreams', 'area_reporte_p_dreams:') !!}
            {!! Form::select('id_area_reporte_p_dreams', $areas_reporte, null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE AREA REPORTE P DREAMS --']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_tipo_dreams', 'tipo_dreams:') !!}
            {!! Form::select('id_tipo_dreams', $tipos, null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE TIPO DREAMS --']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="cantidad_comprada_dreams">cantidad_comprada_dreams:</label>
            {!! Form::text('cantidad_comprada_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="cantidad_recibida_dreams">cantidad_recibida_dreams:</label>
            {!! Form::text('cantidad_recibida_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="unidad_dreams">unidad_dreams:</label>
            {!! Form::text('unidad_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="precio_unitario_antes_descuento_dreams">precio_unitario_antes_descuento_dreams:</label>
            {!! Form::text('precio_unitario_antes_descuento_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="descuento_dreams">descuento_dreams:</label>
            {!! Form::text('descuento_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="precio_unitario_dreams">precio_unitario_dreams:</label>
            {!! Form::text('precio_unitario_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_moneda_original_dreams', 'moneda_original_dreams:') !!}
            {!! Form::select('id_moneda_original_dreams', $monedas, null, ['class' => 'form-control input-sm', 'placeholder'=>'-- SELECCIONE MONEDA ORIGINAL DREAMS --']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="importe_sin_iva_dreams">importe_sin_iva_dreams:</label>
            {!! Form::text('importe_sin_iva_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_factura_dreams">fecha_factura_dreams:</label>
            {!! Form::text('fecha_factura_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="factura_dreams">factura_dreams:</label>
            {!! Form::text('factura_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="pagado_dreams">pagado_dreams:</label>
            {!! Form::text('pagado_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="area_amr_dreams">area_amr_dreams:</label>
            {!! Form::text('area_amr_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_entrega_dreams">factura_entrega_dreams:</label>
            {!! Form::text('fecha_entrega_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="presupuesto">presupuesto:</label>
            {!! Form::text('presupuesto', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="pesos_dreams">pesos_dreams:</label>
            {!! Form::text('pesos_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="dolares_dreams">dolares_dreams:</label>
            {!! Form::text('dolares_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="euros_dreams">euros_dreams:</label>
            {!! Form::text('euros_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="consolidacion_dolares_dreams">consolidacion_dolares_dreams:</label>
            {!! Form::text('consolidacion_dolares_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="costo_x_habitacion_dreams">costo_x_habitacion_dreams:</label>
            {!! Form::text('costo_x_habitacion_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="consolidado_banco_dreams">consolidado_banco_dreams:</label>
            {!! Form::text('consolidado_banco_dreams', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="id_clasificacion">id_clasificacion:</label>
            {!! Form::text('id_clasificacion', null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="clasificacion">clasificacion:</label>
            {!! Form::text('clasificacion', null, ['class' => 'form-control input-sm']) !!}
        </div>
    </div>
  {!! Form::text('familia', null, ['class' => 'form-control hidden input-sm']) !!}
  {!! Form::text('area_secrets', null, ['class' => 'form-control hidden input-sm']) !!}
  {!! Form::text('area_reporte', null, ['class' => 'form-control hidden input-sm']) !!}
  {!! Form::text('tipo', null, ['class' => 'form-control hidden input-sm']) !!}
  {!! Form::text('moneda_original', null, ['class' => 'form-control hidden input-sm']) !!}
  {!! Form::text('familia_dreams', null, ['class' => 'form-control hidden input-sm']) !!}
  {!! Form::text('area_dreams', null, ['class' => 'form-control hidden input-sm']) !!}
  {!! Form::text('area_reporte_p_dreams', null, ['class' => 'form-control hidden input-sm']) !!}
  {!! Form::text('tipo_dreams', null, ['class' => 'form-control hidden input-sm']) !!}
  {!! Form::text('moneda_original_dreams', null, ['class' => 'form-control hidden input-sm']) !!}
  
  {!! Form::submit('Actualizar datos', array('class' => 'btn btn-primary')) !!}
  {!! Form::close() !!}
@stop
  
@section('scripts')
<script>
    $('select[name=id_familia]').on('change', function () { $('input[name=familia]').val($('select[name=id_familia] option:selected').text()); });
    $('select[name=id_area_secrets]').on('change', function () { $('input[name=area_secrets]').val($('select[name=id_area_secrets] option:selected').text()); });
    $('select[name=id_area_reporte]').on('change', function () { $('input[name=area_reporte]').val($('select[name=id_area_reporte] option:selected').text()); });
    $('select[name=id_moneda_original]').on('change', function () { $('input[name=moneda_original]').val($('select[name=id_moneda_original] option:selected').text()); });
    $('select[name=id_familia_dreams]').on('change', function () { $('input[name=familia_dreams]').val($('select[name=id_familia_dreams] option:selected').text()); });
    $('select[name=id_area_dreams]').on('change', function () { $('input[name=area_dreams]').val($('select[name=id_area_dreams] option:selected').text()); });
    $('select[name=id_tipo]').on('change', function () { $('input[name=tipo]').val($('select[name=id_tipo] option:selected').text()); });
    $('select[name=id_area_reporte_p_dreams]').on('change', function () { $('input[name=area_reporte_p_dreams]').val($('select[name=id_area_reporte_p_dreams] option:selected').text()); });
    $('select[name=id_tipo_dreams]').on('change', function () { $('input[name=tipo_dreams]').val($('select[name=id_tipo_dreams] option:selected').text()); });
    $('select[name=id_moneda_original_dreams]').on('change', function () { $('input[name=moneda_original_dreams]').val($('select[name=id_moneda_original_dreams] option:selected').text()); });</script>
@stop
  