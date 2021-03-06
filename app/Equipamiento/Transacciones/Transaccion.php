<?php

namespace Ghi\Equipamiento\Transacciones;
use Illuminate\Support\Facades\DB;
use Ghi\Core\Models\Obra;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Laracasts\Presenter\PresentableTrait;
use Ghi\Equipamiento\Presenters\TransaccionPresenter;
use Carbon\Carbon;
use Ghi\Equipamiento\ReporteCostos\DatosSecretsConDreams;

class Transaccion extends Model
{
    use PresentableTrait;
    protected $presenter = TransaccionPresenter::class;
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'transacciones';

    /**
     * @var string
     */
    protected $primaryKey = 'id_transaccion';

    /**
     * @var array
     */
    protected $dates = ['fecha', 'cumplimiento', 'vencimiento'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Obra relacionada con esta adquisicion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Empresa relacionada con esta adquisicion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo(Proveedor::class, 'id_empresa', 'id_empresa');
    }

    /**
     * Items relacionados con esta transaccion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'id_transaccion', 'id_transaccion');
    }

    /**
     * Transacciones de tipo orden compra materiales
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenesCompraMateriales($query)
    {
        return $query->where('tipo_transaccion', Tipo::ORDEN_COMPRA)
            ->where('opciones', 1)
            ->where('equipamiento', 1)
            ->where('transacciones.estado','<>', -2);
    }
    
    public function recibido()
    {
        return (float) DB::connection($this->connection)
            ->table('Equipamiento.recepciones')
            ->join('Equipamiento.recepcion_items', 'recepciones.id', '=', 'recepcion_items.id_recepcion')
            ->where('id_orden_compra', $this->id_transaccion)
            ->sum('cantidad_recibida');
    }
    public function getCantidadRecibidaAttribute()
    {
        return $this->recibido();
    }
    
    public function getCantidadRecibidaMaterial($id_material)
    {
        return (float) DB::connection($this->connection)
            ->table('Equipamiento.recepciones')
            ->join('Equipamiento.recepcion_items', 'recepciones.id', '=', 'recepcion_items.id_recepcion')
            ->where('id_orden_compra', $this->id_transaccion)
            ->where('Equipamiento.recepcion_items.id_material', $id_material)
            ->sum('cantidad_recibida');
    }
    
    public function progressbar_estado_recepcion_class()
    {
        $clase = "";
        if(round($this->cantidad_recibida / $this->items()->sum('cantidad')*100) == 100){
            $clase = "progress-bar-success";
        }else if(round($this->cantidad_recibida / $this->items()->sum('cantidad')*100) < 100 && round($this->cantidad_recibida / $this->items()->sum('cantidad')*100)>=70){
            $clase = "";
        }else if(round($this->cantidad_recibida / $this->items()->sum('cantidad')*100) < 70 && round($this->cantidad_recibida / $this->items()->sum('cantidad')*100)>=50){
            $clase = "progress-bar-warning";
        }else{
            $clase = "progress-bar-danger";
        }
        return $clase;
    }
    public function getProgressBarEstadoRecepcionClassAttribute()
    {
        return $this->progressbar_estado_recepcion_class();
    }
    
    public static function arregloXLS($idobra){
        $resultados = DB::connection("cadeco")->select("
            SELECT     TOP (100) PERCENT dbo.transacciones.numero_folio, dbo.materiales.descripcion, 
                      dbo.materiales.descripcion_larga, dbo.materiales.unidad, dbo.items.cantidad, dbo.items.precio_unitario, dbo.items.importe, dbo.items.descuento
FROM         dbo.items INNER JOIN
                      dbo.transacciones ON dbo.items.id_transaccion = dbo.transacciones.id_transaccion INNER JOIN
                      dbo.materiales ON dbo.items.id_material = dbo.materiales.id_material
WHERE     (dbo.transacciones.tipo_transaccion = 19) AND (dbo.transacciones.equipamiento = 1) and transacciones.id_obra = {$idobra}
ORDER BY dbo.transacciones.numero_folio
                            ");
        
        return  json_decode(json_encode($resultados), true);
    }
    
    public function antecedente(){
        return $this->hasOne(Transaccion::class, "id_transaccion", "id_antecedente");
    }
    
    public function getComparativaXLS(){
        $resultados = DB::connection("cadeco")->select("
            SELECT reporte_b_datos_secrets.proveedor,
       reporte_b_datos_secrets.no_oc,
       reporte_b_materiales_secrets.descripcion AS descripcion_producto_oc,
       reporte_b_datos_secrets.cantidad_comprada AS cantidad_comprada_oc,
       reporte_b_datos_secrets.unidad,
       reporte_b_datos_secrets.precio AS precio,
       reporte_b_datos_secrets.moneda,
       cast (
          round (reporte_b_datos_secrets.importe_sin_iva, 2) AS NUMERIC (36, 2))
          AS importe_sin_iva,
       empresas.razon_social AS proveedor_dreams,
       materiales.descripcion,
       items.cantidad AS cantidad_solicitada_amr,
       items.unidad AS unidad_dreams,
       cast (round (items.precio_unitario, 2) AS NUMERIC (36, 2))
          AS precio_unitario_mo,
       monedas.nombre AS moneda_original,
       cast (round (items.importe, 2) AS NUMERIC (36, 2)) AS importe,
       cast (round (dbo.ConversionTC (items.precio_unitario,
                                      transacciones.id_moneda,
                                      2,
                                      0,
                                      18.20,
                                      0),
                    2) AS NUMERIC (36, 2))
          AS precio_unitario_dolares,
       cast (round (dbo.ConversionTC (items.importe,
                                      transacciones.id_moneda,
                                      2,
                                      0,
                                      18.20,
                                      0),
                    2) AS NUMERIC (36, 2))
          AS importe_dolares,
       cast (
          round ( (reporte_b_datos_secrets.importe_sin_iva * 1.22), 2) AS NUMERIC (36, 2))
          AS presupuesto,
       cast (round (  dbo.ConversionTC (items.importe,
                                        transacciones.id_moneda,
                                        2,
                                        0,
                                        18.20,
                                        0)
                    - (reporte_b_datos_secrets.importe_sin_iva * 1.22),
                    2) AS NUMERIC (36, 2))
          AS diferencial,
         (items.cantidad - reporte_b_datos_secrets.cantidad_comprada)
       / reporte_b_datos_secrets.cantidad_comprada
          AS crecimiento_amr,
       cast (
          round (
               (reporte_b_datos_secrets.importe_sin_iva * 1.22)
             / reporte_b_datos_secrets.precio,
             2) AS NUMERIC (36, 2))
          AS piezas_por_presupuesto,
       cast (round (  (reporte_b_datos_secrets.importe_sin_iva * 1.22)
                    / reporte_b_datos_secrets.precio
                    * dbo.ConversionTC (items.precio_unitario,
                                        transacciones.id_moneda,
                                        2,
                                        0,
                                        18.20,
                                        0),
                    2) AS NUMERIC (36, 2))
          AS costo_con_crecimiento
  FROM ((((((SAO1814_HOTEL_DREAMS_PM.dbo.transacciones transacciones
             INNER JOIN SAO1814_HOTEL_DREAMS_PM.dbo.monedas monedas
                ON (transacciones.id_moneda = monedas.id_moneda))
            INNER JOIN SAO1814_HOTEL_DREAMS_PM.dbo.items items
               ON (transacciones.id_transaccion = items.id_transaccion))
           INNER JOIN SAO1814_HOTEL_DREAMS_PM.dbo.materiales materiales
              ON (items.id_material = materiales.id_material))
          LEFT OUTER JOIN
          SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_ms_md reporte_b_ms_md
             ON (reporte_b_ms_md.id_material_dreams = materiales.id_material))
         LEFT OUTER JOIN
         SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_materiales_secrets reporte_b_materiales_secrets
            ON (reporte_b_ms_md.id_material_secrets =
                   reporte_b_materiales_secrets.id))
        LEFT OUTER JOIN
        SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_datos_secrets reporte_b_datos_secrets
           ON (reporte_b_datos_secrets.id_material_secrets =
                  reporte_b_materiales_secrets.id))
       INNER JOIN SAO1814_HOTEL_DREAMS_PM.dbo.empresas empresas
          ON (transacciones.id_empresa = empresas.id_empresa)
 WHERE transacciones.id_transaccion = ".$this->id_transaccion."
                            ");
        
        return  $resultados;
    }
    
    public function pagosProgramados(){
        return $this->hasMany(PagoProgramado::class, 'id_transaccion', 'id_transaccion');
    }
    
    public function totalProgramado() {
        $result = 0;
        foreach($this->pagosProgramados as $pagoProgramado) {
            $result += $pagoProgramado->monto;
        }
        return $result;
    }
    
    public function getAnioMesDiaPagoAttribute(){
        $dias = DB::connection("cadeco")->select(" select 
            dbo.zerofill(4,transacciones.numero_folio) as folio_oc,
            transacciones.id_transaccion as id_oc,
        Equipamiento.pagos_programados.fecha,
        year( Equipamiento.pagos_programados.fecha) as anio,
        month( Equipamiento.pagos_programados.fecha) as mes,
        day( Equipamiento.pagos_programados.fecha) as dia,
          convert(varchar(4),year( Equipamiento.pagos_programados.fecha)) + 
        case when len(month( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),month( Equipamiento.pagos_programados.fecha))
        else convert(varchar(4),month( Equipamiento.pagos_programados.fecha)) end +
        case when len(day( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),day( Equipamiento.pagos_programados.fecha))
        else convert(varchar(4),day( Equipamiento.pagos_programados.fecha)) end
          anio_mes_dia, Equipamiento.pagos_programados.monto
         from [Equipamiento].[pagos_programados] join transacciones
         on(transacciones.id_transaccion = pagos_programados.id_transaccion )
        where transacciones.id_transaccion = {$this->id_transaccion};");
    $dias_arr = [];
    $cantidad_recibida = $this->totalProgramado();
    foreach($dias as $dia){
        $date = Carbon::createFromFormat('Y-m-d', $dia->fecha);
        $dias_arr[$dia->anio_mes_dia]["fecha"] = $dia->anio_mes_dia;
        $dias_arr[$dia->anio_mes_dia]["folio_oc"] = $dia->folio_oc;
        $dias_arr[$dia->anio_mes_dia]["id_oc"] = $dia->id_oc;
        $dias_arr[$dia->anio_mes_dia]["fecha"] = $date->format("d-m-Y");
        $dias_arr[$dia->anio_mes_dia]["monto"] = $dia->monto;

        if($cantidad_recibida>= $dia->monto){
                $dias_arr[$dia->anio_mes_dia]["monto"] = $dia->monto;
            $cantidad_recibida -= $dia->monto;
        }else{
            $dias_arr[$dia->anio_mes_dia]["monto"] = $cantidad_recibida;
            $cantidad_recibida = 0;
        }
        if($dia->monto > 0){
            $dias_arr[$dia->anio_mes_dia]["indice_pago"] = number_format(($dias_arr[$dia->anio_mes_dia]["monto"]/Transaccion::find($this->id_transaccion)->monto*100),2,".","");
        }else{
            $dias_arr[$dia->anio_mes_dia]["indice_pago"] = "";
        }
    }
    
    return $dias_arr;
    }
    
    public function datosSecretsDreams() {
        return $this->belongsToMany(DatosSecretsConDreams::class, 'Equipamiento.reporte_b_compra_vs_presupuesto', 'id_transaccion', 'id_reporte_b_datos_secrets');
    }
    public function getTotalDolaresAttribute(){
        $resultados = DB::connection("cadeco")->select("
            SELECT 
       
       
       cast (round (dbo.ConversionTC (items.importe,
                                      transacciones.id_moneda,
                                      2,
                                      0,
                                      18.20,
                                      0),
                    2) AS NUMERIC (36, 2))
          AS importe_dolares
  FROM SAO1814_HOTEL_DREAMS_PM.dbo.transacciones transacciones
             
            INNER JOIN SAO1814_HOTEL_DREAMS_PM.dbo.items items
               ON (transacciones.id_transaccion = items.id_transaccion)
           
          
         
        
       
 WHERE transacciones.id_transaccion = ".$this->id_transaccion."
                            ");
        $col =collect($resultados);
        $total = $col->sum("importe_dolares");
        return $total;
    }
    public function getTotalPresupuestoAttribute(){
        $resultados = DB::connection("cadeco")->select("
            SELECT SUM (reporte_b_datos_secrets.consolidado_dolares*1.22) as total_presupuesto
  FROM (SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_compra_vs_presupuesto reporte_b_compra_vs_presupuesto
        INNER JOIN SAO1814_HOTEL_DREAMS_PM.dbo.transacciones transacciones
           ON (reporte_b_compra_vs_presupuesto.id_transaccion =
                  transacciones.id_transaccion))
       INNER JOIN
       SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_datos_secrets reporte_b_datos_secrets
          ON (reporte_b_compra_vs_presupuesto.id_reporte_b_datos_secrets =
                 reporte_b_datos_secrets.id)
 WHERE (transacciones.id_transaccion = ".$this->id_transaccion.")
                            ");
        $col =collect($resultados);
        $total = $col->sum("total_presupuesto");
        return $total;
    }
    public function getVariacionAttribute(){
        return $this->total_dolares-$this->total_presupuesto;
    }
    public function getPorcentajeVariacionAttribute(){
        if($this->total_presupuesto>0)
            return number_format(($this->total_dolares-$this->total_presupuesto)/$this->total_presupuesto*100,2,".",",")." %";
        else 
            return "-";
    }
}
