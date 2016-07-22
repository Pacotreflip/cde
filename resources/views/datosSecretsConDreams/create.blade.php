@extends('layout')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('datosSecretsConDreams.index') }}">Datos Secrets Con Dreams</a></li>
    <li class="active">Nueva entrada de Datos</li>
</ol>

<h1>Nuevo Registro de Datos (Secrets con Dreams)</h1>
<hr>
  
@include('partials.errors')
{!! Form::open(array('url' => 'datosSecretsConDreams')) !!}    
    <div class="row">
        <div class="form-group col-xs-3">
            <label for="no">no:</label>
            <input name="no" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="proveedor">proveedor:</label>
            <input name="proveddor" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="no_oc">no_oc:</label>
            <input name="no_oc" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="descripcion_producto_oc">descripcion_producto_oc:</label>
            <input name="descripcion_producto_oc" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_familia', 'familia:') !!}
            {!! Form::select('id_familia', $familias, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_area_secrets', 'area_secrets:') !!}
            {!! Form::select('id_area_secrets', $areas_secrets, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_area_reporte', 'area_reporte:') !!}
            {!! Form::select('id_area_reporte', $areas_reporte, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_tipo', 'tipo:') !!}
            {!! Form::select('id_tipo', $tipos, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_moneda_original', 'moneda_original:') !!}
            {!! Form::select('id_moneda_original', $monedas, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="cantidad_comprada">cantidad_comprada:</label>
            <input name="cantidad_comprada" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="recibidos_por_factura">recibidos_por_factura:</label>
            <input name="recibidos_por_factura" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="unidad">unidad:</label>
            <input name="unidad" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="precio">precio:</label>
            <input name="precio" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="moneda">moneda:</label>
            <input name="moneda" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="importe_sin_iva">importe_sin_iva:</label>
            <input name="importe_sin_iva" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_factura">fecha_factura:</label>
            <input name="fecha_factura" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="factura">factura:</label>
            <input name="factura" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_pago">fecha_pago:</label>
            <input name="fecha_pago" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_amr">fecha_amr:</label>
            <input name="fecha_amr" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_entrega">fecha_entrega:</label>
            <input name="fecha_entrega" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="pesos">pesos:</label>
            <input name="pesos" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="dolares">dolares:</label>
            <input name="dolares" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="euros">euros:</label>
            <input name="euros" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="consolidado_dolares">consolidado_dolares:</label>
            <input name="consolidado_dolares" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="id_material_secrets">id_material_secrets:</label>
            <input name="id_material_secrets" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="proveedor_dreams">proveedor_dreams:</label>
            <input name="proveedor_dreams" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="no_oc_dreams">no_oc_dreams:</label>
            <input name="no_oc_dreams" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="descripcion_producto_oc_dreams">descripcion_producto_oc_dreams:</label>
            <input name="descripcion_producto_oc_dreams" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_familia_dreams', 'familia_dreams:') !!}
            {!! Form::select('id_familia_dreams', $familias, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_area_dreams', 'area_dreams:') !!}
            {!! Form::select('id_area_dreams', $areas_dreams, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_area_reporte_p_dreams', 'area_reporte_p_dreams:') !!}
            {!! Form::select('id_area_reporte_p_dreams', $areas_reporte, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_tipo_dreams', 'tipo_dreams:') !!}
            {!! Form::select('id_tipo_dreams', $tipos, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="cantidad_comprada_dreams">cantidad_comprada_dreams:</label>
            <input name="cantidad_comprada_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="cantidad_recibida_dreams">cantidad_recibida_dreams:</label>
            <input name="cantidad_recibida_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="unidad_dreams">unidad_dreams:</label>
            <input name="unidad_dreams" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="precio_unitario_antes_descuento_dreams">precio_unitario_antes_descuento_dreams:</label>
            <input name="precio_unitario_antes_descuento_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="descuento_dreams">descuento_dreams:</label>
            <input name="descuento_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="precio_unitario_dreams">precio_unitario_dreams:</label>
            <input name="precio_unitario_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            {!! Form::Label('id_moneda_original_dreams', 'moneda_original_dreams:') !!}
            {!! Form::select('id_moneda_original_dreams', $monedas, null, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group col-xs-3">
            <label for="importe_sin_iva_dreams">importe_sin_iva_dreams:</label>
            <input name="importe_sin_iva_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="fecha_factura_dreams">fecha_factura_dreams:</label>
            <input name="fecha_factura_dreams" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="factura_dreams">factura_dreams:</label>
            <input name="factura_dreams" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="pagado_dreams">pagado_dreams:</label>
            <input name="pagado_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="area_amr_dreams">area_amr_dreams:</label>
            <input name="area_amr_dreams" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="factura_entrega_dreams">factura_entrega_dreams:</label>
            <input name="factura_entrega_dreams" class="form-control input-sm" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label for="presupuesto">presupuesto:</label>
            <input name="presupuesto" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="pesos_dreams">pesos_dreams:</label>
            <input name="pesos_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="dolares_dreams">dolares_dreams:</label>
            <input name="dolares_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="euros_dreams">euros_dreams:</label>
            <input name="euros_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="consolidacion_dolares_dreams">consolidacion_dolares_dreams:</label>
            <input name="consolidacion_dolares_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="costo_x_habitacion_dreams">costo_x_habitacion_dreams:</label>
            <input name="costo_x_habitacion_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="consolidado_banco_dreams">consolidado_banco_dreams:</label>
            <input name="consolidado_banco_dreams" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="id_clasificacion">id_clasificacion:</label>
            <input name="id_clasificacion" class="form-control input-sm" type="number">
        </div>
        <div class="form-group col-xs-3">
            <label for="clasificacion">clasificacion:</label>
            <input name="clasificacion" class="form-control input-sm" type="text">
        </div>
    </div>

<input value="" id="familia" hidden name="familia" type="text">
<input value="" id="area_secrets" hidden name="area_secrets" type="text">
<input value="" id="area_reporte" hidden name="area_reporte" type="text">
<input value="" id="tipo" hidden name="tipo" type="text">
<input value="" id="moneda_original" hidden name="moneda_original" type="text">
<input value="" id="familia_dreams" hidden name="familia_dreams" type="text">
<input value="" id="area_dreams" hidden name="area_dreams" type="text">
<input value="" id="area_reporte_p_dreams" hidden name="area_reporte_p_dreams" type="text">
<input value="" id="tipo_dreams" hidden name="tipo_dreams" type="text">
<input value="" id="moneda_original_dreams" hidden name="moneda_original_dreams" type="text">

{!! Form::submit('Guardar', array('class' => 'btn btn-primary')) !!}
{!! Form::close() !!}
@stop
@section('scripts')
<!--<script>
    $('form').on('submit', function(e){
        e.preventDefault();
        var data = $('form').serialize();
        data.push({name: 'familia', value: $('select[name=id_familia] option:selected').text()});
        data.push({name: 'area_secrets', value: $('select[name=id_familia] option:selected').text()});
        data.push({name: 'area_reporte', value: $('select[name=id_familia] option:selected').text()});
        data.push({name: 'area_secrets', value: $('select[name=id_familia] option:selected').text()});
        data.push({name: 'area_secrets', value: $('select[name=id_familia] option:selected').text()});
        data.push({name: 'area_secrets', value: $('select[name=id_familia] option:selected').text()});
        data.push({name: 'area_secrets', value: $('select[name=id_familia] option:selected').text()});
        data.push({name: 'area_secrets', value: $('select[name=id_familia] option:selected').text()});
        data.push({name: 'area_secrets', value: $('select[name=id_familia] option:selected').text()});
            
        });
        $.ajax({
            bedoreSend: function() {
                
            },
            url: '{{ route("datosSecretsConDreams.store") }}',
            data: $('form').serialize() + [familia = 1500] ,
            type: 'POST',
            success: function() {
                window.reload;
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
//    $(function() {
//        $('#form').submit(function() {
//            $('#familia').val($('select[name=id_familia] option:selected').text());
//            $('#area_secrets').val($('select[name=id_area_secrets] option:selected').text());
//            $('#area_reporte').val($('select[name=id_area_reporte] option:selected').text());
//            $('#tipo').val($('select[name=id_tipo] option:selected').text());
//            $('#moneda_original').val($('select[name=id_moneda_original] option:selected').text());
//            $('#familia_dreams').val($('select[name=id_familia_dreams] option:selected').text());
//            $('#area_dreams').val($('select[name=id_area_dreams] option:selected').text());
//            $('#area_reporte_p_dreams').val($('select[name=id_area_reporte_p_dreams] option:selected').text());
//            $('#tipo_dreams').val($('select[name=id_tipo_dreams] option:selected').text());
//            $('#moneda_original_dreams').val($('select[name=id_moneda_original_dreams] option:selected').text());  
//            return false;
//        });
//    });
</script>
@stop-->