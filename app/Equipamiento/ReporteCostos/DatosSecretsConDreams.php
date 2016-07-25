<?php

namespace Ghi\Equipamiento\ReporteCostos;

use Illuminate\Database\Eloquent\Model;

class DatosSecretsConDreams extends Model
{
    protected $table = "Equipamiento.reporte_b_datos_secrets";
    protected $connection = "cadeco";
    protected $fillable = [
       "no"
      ,"proveedor"
      ,"no_oc"
      ,"descripcion_producto_oc"
      ,"id_familia"
      ,"familia"
      ,"id_area_secrets"
      ,"area_secrets"
      ,"id_area_reporte"
      ,"area_reporte"
      ,"id_tipo"
      ,"tipo"
      ,"id_moneda_original"
      ,"moneda_original"
      ,"cantidad_comprada"
      ,"recibidos_por_factura"
      ,"unidad"
      ,"precio"
      ,"moneda"
      ,"importe_sin_iva"
      ,"fecha_factura"
      ,"factura"
      ,"fecha_pago"
      ,"area_amr"
      ,"fecha_entrega"
      ,"pesos"
      ,"dolares"
      ,"euros"
      ,"consolidado_dolares"
      ,"id_material_secrets"
      ,"proveedor_dreams"
      ,"no_oc_dreams"
      ,"descripcion_producto_oc_dreams"
      ,"id_familia_dreams"
      ,"familia_dreams"
      ,"id_area_dreams"
      ,"area_dreams"
      ,"id_area_reporte_p_dreams"
      ,"area_reporte_p_dreams"
      ,"id_tipo_dreams"
      ,"tipo_dreams"
      ,"cantidad_comprada_dreams"
      ,"cantidad_recibida_dreams"
      ,"unidad_dreams"
      ,"precio_unitario_antes_descuento_dreams"
      ,"descuento_dreams"
      ,"precio_unitario_dreams"
      ,"id_moneda_original_dreams"
      ,"moneda_original_dreams"
      ,"importe_sin_iva_dreams"
      ,"fecha_factura_dreams"
      ,"factura_dreams"
      ,"pagado_dreams"
      ,"area_amr_dreams"
      ,"fecha_entrega_dreams"
      ,"presupuesto"
      ,"pesos_dreams"
      ,"dolares_dreams"
      ,"euros_dreams"
      ,"consolidacion_dolares_dreams"
      ,"costo_x_habitacion_dreams"
      ,"consolidado_banco_dreams"
      ,"id_clasificacion"
      ,"clasificacion"
    ];
    public $incrementing = false;
    public $timestamps = false;
}
