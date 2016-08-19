<?php

namespace Ghi\Equipamiento\Reporte;
use Illuminate\Support\Facades\DB;
use \Ghi\Equipamiento\Areas\AreaTipo;
use PDO;

class Reporte {
    public static function getFFE(){
        $resultados = DB::connection("cadeco")->select("
            SELECT PresupuestoConDreamsCotCom.id_tipo,
            case when PresupuestoConDreamsCotCom.id_tipo is null then 0
            else PresupuestoConDreamsCotCom.id_tipo end id_tipo_agrupar,
            
            case when PresupuestoConDreamsCotCom.id_area_reporte is null then 0
            else PresupuestoConDreamsCotCom.id_area_reporte end id_area_reporte_agrupar,

            case when PresupuestoConDreamsCotCom.familia is null then 'sin_familia'
            else PresupuestoConDreamsCotCom.familia end familia_agrupar,

       PresupuestoConDreamsCotCom.id_area_reporte,
       PresupuestoConDreamsCotCom.id_familia,
       PresupuestoConDreamsCotCom.tipo,
       PresupuestoConDreamsCotCom.area_reporte,
       PresupuestoConDreamsCotCom.familia,
       PresupuestoConDreamsCotCom.secrets,
       PresupuestoConDreamsCotCom.presupuesto,
       PresupuestoConDreamsCotCom.cotizado_para_acumular,
       PresupuestoConDreamsCotCom.importe_dolares,
       PresupuestoConDreamsCotCom.importe_sin_cotizar,
       PresupuestoConDreamsCotCom.total_dreams,
       PresupuestoConDreamsCotCom.var_tp,
       PresupuestoConDreamsCotCom.var_tp_p
       

       
       
  FROM SAO1814_HOTEL_DREAMS_PM.Equipamiento.PresupuestoConDreamsCotCom PresupuestoConDreamsCotCom
 WHERE (PresupuestoConDreamsCotCom.id_tipo = 25)
ORDER BY PresupuestoConDreamsCotCom.id_area_reporte ASC,
         PresupuestoConDreamsCotCom.importe_dolares DESC");

            
        $collection =  collect($resultados);//->unique();
        $unique = $collection->unique(function ($item) {
            return $item->id_tipo_agrupar.$item->familia_agrupar.$item->id_area_reporte_agrupar;
        });
        //dd($unique);
        return $unique;
    }
    public static function getOSE(){
        $resultados = DB::connection("cadeco")->select("
            SELECT PresupuestoConDreamsCotCom.id_tipo,
            case when PresupuestoConDreamsCotCom.id_tipo is null then 0
            else PresupuestoConDreamsCotCom.id_tipo end id_tipo_agrupar,
            
            case when PresupuestoConDreamsCotCom.id_area_reporte is null then 0
            else PresupuestoConDreamsCotCom.id_area_reporte end id_area_reporte_agrupar,

            case when PresupuestoConDreamsCotCom.id_familia is null then 0
            else PresupuestoConDreamsCotCom.id_familia end id_familia_agrupar,

       PresupuestoConDreamsCotCom.id_area_reporte,
       PresupuestoConDreamsCotCom.id_familia,
       PresupuestoConDreamsCotCom.tipo,
       PresupuestoConDreamsCotCom.area_reporte,
       PresupuestoConDreamsCotCom.familia,
       PresupuestoConDreamsCotCom.secrets,
       PresupuestoConDreamsCotCom.presupuesto,
       PresupuestoConDreamsCotCom.cotizado_para_acumular,
       PresupuestoConDreamsCotCom.importe_dolares,
       PresupuestoConDreamsCotCom.importe_sin_cotizar,
       PresupuestoConDreamsCotCom.total_dreams,
       PresupuestoConDreamsCotCom.var_tp,
       PresupuestoConDreamsCotCom.var_tp_p
       

       
       
  FROM SAO1814_HOTEL_DREAMS_PM.Equipamiento.PresupuestoConDreamsCotCom PresupuestoConDreamsCotCom
 WHERE (PresupuestoConDreamsCotCom.id_tipo = 24)
ORDER BY PresupuestoConDreamsCotCom.id_area_reporte ASC,
         PresupuestoConDreamsCotCom.importe_dolares DESC");
            
        $collection =  collect($resultados);//->unique();
        $unique = $collection->unique(function ($item) {
            return $item->id_tipo_agrupar.$item->id_familia_agrupar.$item->id_area_reporte_agrupar;
        });
        //dd($unique);
        return $unique;
    }
    public static function getNULL(){
        $resultados = DB::connection("cadeco")->select("
            SELECT PresupuestoConDreamsCotCom.id_tipo,
            case when PresupuestoConDreamsCotCom.id_tipo is null then 0
            else PresupuestoConDreamsCotCom.id_tipo end id_tipo_agrupar,
            
            case when PresupuestoConDreamsCotCom.id_area_reporte is null then 0
            else PresupuestoConDreamsCotCom.id_area_reporte end id_area_reporte_agrupar,

            case when PresupuestoConDreamsCotCom.id_familia is null then 0
            else PresupuestoConDreamsCotCom.id_familia end id_familia_agrupar,

       PresupuestoConDreamsCotCom.id_area_reporte,
       PresupuestoConDreamsCotCom.id_familia,
       PresupuestoConDreamsCotCom.tipo,
       PresupuestoConDreamsCotCom.area_reporte,
       PresupuestoConDreamsCotCom.familia,
       PresupuestoConDreamsCotCom.secrets,
       PresupuestoConDreamsCotCom.presupuesto,
       PresupuestoConDreamsCotCom.cotizado_para_acumular,
       PresupuestoConDreamsCotCom.importe_dolares,
       PresupuestoConDreamsCotCom.importe_sin_cotizar,
       PresupuestoConDreamsCotCom.total_dreams,
       PresupuestoConDreamsCotCom.var_tp,
       PresupuestoConDreamsCotCom.var_tp_p
       

       
       
  FROM SAO1814_HOTEL_DREAMS_PM.Equipamiento.PresupuestoConDreamsCotCom PresupuestoConDreamsCotCom
 WHERE (PresupuestoConDreamsCotCom.id_tipo is null)
ORDER BY PresupuestoConDreamsCotCom.id_area_reporte ASC,
         PresupuestoConDreamsCotCom.importe_dolares DESC");
            
        $collection =  collect($resultados);//->unique();
        $unique = $collection->unique(function ($item) {
            return $item->id_tipo_agrupar.$item->id_familia_agrupar.$item->id_area_reporte_agrupar;
        });
        //dd($unique);
        return $unique;
    }
        public static function getTotal(){
            //DB::setFetchMode(PDO::FETCH_ASSOC);
        $resultados = DB::connection("cadeco")->select("
            SELECT PresupuestoConDreamsCotCom.id_tipo,
            
            case when PresupuestoConDreamsCotCom.id_tipo is null then 0
            else PresupuestoConDreamsCotCom.id_tipo end id_tipo_agrupar,
            
            case when PresupuestoConDreamsCotCom.id_area_reporte is null then 0
            else PresupuestoConDreamsCotCom.id_area_reporte end id_area_reporte_agrupar,

            case when PresupuestoConDreamsCotCom.familia is null then 'sin_familia'
            else PresupuestoConDreamsCotCom.familia end familia_agrupar,

       PresupuestoConDreamsCotCom.id_area_reporte,
       PresupuestoConDreamsCotCom.id_familia,
       PresupuestoConDreamsCotCom.tipo,
       PresupuestoConDreamsCotCom.area_reporte,
       PresupuestoConDreamsCotCom.familia,
       PresupuestoConDreamsCotCom.secrets,
       PresupuestoConDreamsCotCom.presupuesto,
       PresupuestoConDreamsCotCom.cotizado_para_acumular,
       PresupuestoConDreamsCotCom.importe_dolares,
       PresupuestoConDreamsCotCom.importe_sin_cotizar,
       PresupuestoConDreamsCotCom.total_dreams,
       PresupuestoConDreamsCotCom.var_tp,
       PresupuestoConDreamsCotCom.var_tp_p
              
       
  FROM SAO1814_HOTEL_DREAMS_PM.Equipamiento.PresupuestoConDreamsCotCom PresupuestoConDreamsCotCom

ORDER BY PresupuestoConDreamsCotCom.id_area_reporte ASC,
         PresupuestoConDreamsCotCom.importe_dolares DESC");
        
//        foreach($resultados as $object)
//        {
//            $resultados_arreglo[] =  (array) $object;
//        }
        
        $collection =  collect($resultados);//->unique();
        $unique = $collection->unique(function ($item) {
            return $item->id_tipo_agrupar.$item->familia_agrupar.$item->id_area_reporte_agrupar;
        });
        //dd($unique);
        return $unique;
    }
    public static function getMaterialesDreams($id_tipo, $id_familia, $id_area_reporte){
        $filtros = "(importe_dolares > 0 or cotizado_para_acumular  > 0 or importe_sin_cotizar  > 0)";
        if($id_tipo == "null"){
            $filtros .= " and reporte_b_materiales_dreams.id_clasificador is null";
        }elseif($id_tipo > 0){
            $filtros .= " and reporte_b_materiales_dreams.id_clasificador={$id_tipo}";
        }
        if($id_familia == "null"){
            $filtros .= " and reporte_b_materiales_dreams.id_familia is null";
        }elseif($id_familia > 0){
            $filtros .= " and reporte_b_materiales_dreams.id_familia={$id_familia}";
        }
        if($id_area_reporte == "null"){
            $filtros .= " and reporte_b_materiales_dreams.id_area_reporte is null";
        }elseif($id_area_reporte > 0){
            $filtros .= " and reporte_b_materiales_dreams.id_area_reporte={$id_area_reporte}";
        }
        
       $resultados = DB::connection("cadeco")->select("SELECT reporte_b_materiales_dreams.clasificador,
       reporte_b_materiales_dreams.familia,
       reporte_b_materiales_dreams.area_reporte,
       reporte_b_materiales_dreams.material,
       reporte_b_materiales_dreams.id_material,
       reporte_b_datos_secrets_validacion_dreams.consolidado_dolares as secrets,
       reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22 AS presupuesto,
       reporte_b_materiales_dreams.cotizado_para_acumular,
       reporte_b_materiales_dreams.importe_dolares,
       reporte_b_materiales_dreams.importe_sin_cotizar,
       
       (reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar) as total_dreams,
       
CASE WHEN reporte_b_datos_secrets_validacion_dreams.consolidado_dolares IS NULL THEN 
       (reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
      ELSE 
      ((reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
       - (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22))
      END var_tp,
       
       CASE WHEN reporte_b_datos_secrets_validacion_dreams.consolidado_dolares IS NULL THEN NULL
       ELSE

       ((reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
       - (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22))/
       (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22) * 100

       END var_tp_p,
       
       reporte_b_materiales_dreams.id_clasificador,
       reporte_b_materiales_dreams.id_familia,
       reporte_b_materiales_dreams.id_area_reporte,
       reporte_b_datos_secrets_validacion_dreams.descripcion_producto_oc AS material_secrets
  FROM SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_materiales_dreams reporte_b_materiales_dreams
       LEFT OUTER JOIN
       SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_datos_secrets_validacion_dreams reporte_b_datos_secrets_validacion_dreams
          ON (reporte_b_materiales_dreams.id_material_secrets =
                 reporte_b_datos_secrets_validacion_dreams.id_material_secrets)
 WHERE     {$filtros}
       ");
 
        $collection =  collect($resultados);
        $unique = $collection->unique(function ($item) {
            return $item->id_material.".".$item->id_familia.".".$item->id_clasificador.".".$item->id_area_reporte;
        });
        return $unique;
 
    }
    public static function getMaterialesSecretsDreams($id_tipo, $id_familia, $id_area_reporte){
        $filtros_secrets = "(consolidado_dolares > 0)";
        if($id_tipo == "null"){
            $filtros_secrets .= " and reporte_b_datos_secrets_validacion_dreams.id_tipo is null";
        }elseif($id_tipo > 0){
            $filtros_secrets .= " and reporte_b_datos_secrets_validacion_dreams.id_tipo={$id_tipo}";
        }
        if($id_familia == "null"){
            $filtros_secrets .= " and reporte_b_datos_secrets_validacion_dreams.id_familia is null";
        }elseif($id_familia > 0){
            $filtros_secrets .= " and reporte_b_datos_secrets_validacion_dreams.id_familia={$id_familia}";
        }
        if($id_area_reporte == "null"){
            $filtros_secrets .= " and reporte_b_datos_secrets_validacion_dreams.id_area_reporte is null";
        }elseif($id_area_reporte > 0){
            $filtros_secrets .= " and reporte_b_datos_secrets_validacion_dreams.id_area_reporte={$id_area_reporte}";
        }
        
        $filtros_dreams = "(importe_dolares > 0 or cotizado_para_acumular  > 0 or importe_sin_cotizar  > 0)";
        if($id_tipo == "null"){
            $filtros_dreams .= " and reporte_b_materiales_dreams.id_clasificador is null";
        }elseif($id_tipo > 0){
            $filtros_dreams .= " and reporte_b_materiales_dreams.id_clasificador={$id_tipo}";
        }
        if($id_familia == "null"){
            $filtros_dreams .= " and reporte_b_materiales_dreams.id_familia is null";
        }elseif($id_familia > 0){
            $filtros_dreams .= " and reporte_b_materiales_dreams.id_familia={$id_familia}";
        }
        if($id_area_reporte == "null"){
            $filtros_dreams .= " and reporte_b_materiales_dreams.id_area_reporte is null";
        }elseif($id_area_reporte > 0){
            $filtros_dreams .= " and reporte_b_materiales_dreams.id_area_reporte={$id_area_reporte}";
        }
        $consulta = "
           SELECT reporte_b_datos_secrets_validacion_dreams.consolidado_dolares AS secrets,
       reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22 AS presupuesto,
       reporte_b_materiales_dreams.cotizado_para_acumular,
       reporte_b_materiales_dreams.importe_dolares,
       reporte_b_datos_secrets_validacion_dreams.tipo as clasificador,
       reporte_b_datos_secrets_validacion_dreams.familia,
       reporte_b_datos_secrets_validacion_dreams.area_reporte,
       reporte_b_datos_secrets_validacion_dreams.descripcion_producto_oc as material_secrets,
       reporte_b_datos_secrets_validacion_dreams.id_material_secrets as id_material,
       reporte_b_datos_secrets_validacion_dreams.id_tipo as id_clasificador,
       reporte_b_datos_secrets_validacion_dreams.id as id_secrets,
       reporte_b_materiales_dreams.importe_sin_cotizar,
       
       (reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar) as total_dreams,
       
CASE WHEN reporte_b_datos_secrets_validacion_dreams.consolidado_dolares IS NULL THEN 
       (reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
      ELSE 
      ((reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
       - (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22))
      END var_tp,
       
       CASE WHEN reporte_b_datos_secrets_validacion_dreams.consolidado_dolares IS NULL THEN NULL
       ELSE

       ((reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
       - (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22))/
       (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22) * 100

       END var_tp_p,

       reporte_b_datos_secrets_validacion_dreams.id_familia,
       reporte_b_datos_secrets_validacion_dreams.id_area_reporte,
       reporte_b_materiales_dreams.material AS material_dreams,
       reporte_b_materiales_dreams.id_material AS id_material_dreams
  FROM SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_materiales_dreams reporte_b_materiales_dreams
       RIGHT OUTER JOIN
       SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_datos_secrets_validacion_dreams reporte_b_datos_secrets_validacion_dreams
          ON (reporte_b_materiales_dreams.id_material_secrets =
                 reporte_b_datos_secrets_validacion_dreams.id_material_secrets)
 WHERE     {$filtros_secrets}
     UNION 
     SELECT reporte_b_datos_secrets_validacion_dreams.consolidado_dolares AS secrets,
       reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22 AS presupuesto,
       reporte_b_materiales_dreams.cotizado_para_acumular,
       reporte_b_materiales_dreams.importe_dolares,
       reporte_b_datos_secrets_validacion_dreams.tipo as clasificador,
       reporte_b_datos_secrets_validacion_dreams.familia,
       reporte_b_datos_secrets_validacion_dreams.area_reporte,
       reporte_b_datos_secrets_validacion_dreams.descripcion_producto_oc as material_secrets,
       reporte_b_datos_secrets_validacion_dreams.id_material_secrets as id_material,
       reporte_b_datos_secrets_validacion_dreams.id_tipo as id_clasificador,
       reporte_b_datos_secrets_validacion_dreams.id as id_secrets,
       reporte_b_materiales_dreams.importe_sin_cotizar,
       
       (reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar) as total_dreams,
       
CASE WHEN reporte_b_datos_secrets_validacion_dreams.consolidado_dolares IS NULL THEN 
       (reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
      ELSE 
      ((reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
       - (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22))
      END var_tp,
       
       CASE WHEN reporte_b_datos_secrets_validacion_dreams.consolidado_dolares IS NULL THEN NULL
       ELSE

       ((reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
       - (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22))/
       (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22) * 100

       END var_tp_p,

       reporte_b_datos_secrets_validacion_dreams.id_familia,
       reporte_b_datos_secrets_validacion_dreams.id_area_reporte,
       reporte_b_materiales_dreams.material AS material_dreams,
       reporte_b_materiales_dreams.id_material AS id_material_dreams
  FROM SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_materiales_dreams reporte_b_materiales_dreams
       LEFT OUTER JOIN
       SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_datos_secrets_validacion_dreams reporte_b_datos_secrets_validacion_dreams
          ON (reporte_b_materiales_dreams.id_material_secrets =
                 reporte_b_datos_secrets_validacion_dreams.id_material_secrets)
 WHERE     {$filtros_dreams}
     
       ";
       $resultados = DB::connection("cadeco")->select($consulta);
 
       $collection =  collect($resultados);
        $unique = $collection->unique(function ($item) {
            return $item->id_secrets."_".$item->id_material."_".$item->id_material_dreams.".".$item->id_familia.".".$item->id_clasificador.".".$item->id_area_reporte;
        });
        return $unique;
    }
    
    public static function getMaterialesSecrets($id_tipo, $id_familia, $id_area_reporte){
        $filtros = "(consolidado_dolares > 0)";
        if($id_tipo == "null"){
            $filtros .= " and reporte_b_datos_secrets_validacion_dreams.id_tipo is null";
        }elseif($id_tipo > 0){
            $filtros .= " and reporte_b_datos_secrets_validacion_dreams.id_tipo={$id_tipo}";
        }
        if($id_familia == "null"){
            $filtros .= " and reporte_b_datos_secrets_validacion_dreams.id_familia is null";
        }elseif($id_familia > 0){
            $filtros .= " and reporte_b_datos_secrets_validacion_dreams.id_familia={$id_familia}";
        }
        if($id_area_reporte == "null"){
            $filtros .= " and reporte_b_datos_secrets_validacion_dreams.id_area_reporte is null";
        }elseif($id_area_reporte > 0){
            $filtros .= " and reporte_b_datos_secrets_validacion_dreams.id_area_reporte={$id_area_reporte}";
        }
        
       $resultados = DB::connection("cadeco")->select("
           SELECT reporte_b_datos_secrets_validacion_dreams.consolidado_dolares AS secrets,
       reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22 AS presupuesto,
       reporte_b_materiales_dreams.cotizado_para_acumular,
       reporte_b_materiales_dreams.importe_dolares,
       reporte_b_datos_secrets_validacion_dreams.tipo as clasificador,
       reporte_b_datos_secrets_validacion_dreams.familia,
       reporte_b_datos_secrets_validacion_dreams.area_reporte,
       reporte_b_datos_secrets_validacion_dreams.descripcion_producto_oc as material_secrets,
       reporte_b_datos_secrets_validacion_dreams.id as id_material,
       reporte_b_datos_secrets_validacion_dreams.id_tipo as id_clasificador,

       reporte_b_materiales_dreams.importe_sin_cotizar,
       
       (reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar) as total_dreams,
       
CASE WHEN reporte_b_datos_secrets_validacion_dreams.consolidado_dolares IS NULL THEN 
       (reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
      ELSE 
      ((reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
       - (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22))
      END var_tp,
       
       CASE WHEN reporte_b_datos_secrets_validacion_dreams.consolidado_dolares IS NULL THEN NULL
       ELSE

       ((reporte_b_materiales_dreams.cotizado_para_acumular+
       reporte_b_materiales_dreams.importe_dolares_dreams+
       reporte_b_materiales_dreams.importe_sin_cotizar)
       - (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22))/
       (reporte_b_datos_secrets_validacion_dreams.consolidado_dolares * 1.22) * 100

       END var_tp_p,

       reporte_b_datos_secrets_validacion_dreams.id_familia,
       reporte_b_datos_secrets_validacion_dreams.id_area_reporte,
       reporte_b_materiales_dreams.material AS material_dreams,
       reporte_b_materiales_dreams.id_material AS id_material_dreams
  FROM SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_materiales_dreams reporte_b_materiales_dreams
       RIGHT OUTER JOIN
       SAO1814_HOTEL_DREAMS_PM.Equipamiento.reporte_b_datos_secrets_validacion_dreams reporte_b_datos_secrets_validacion_dreams
          ON (reporte_b_materiales_dreams.id_material_secrets =
                 reporte_b_datos_secrets_validacion_dreams.id_material_secrets)
 WHERE     {$filtros}
       ");
 
        $collection =  collect($resultados);
        $unique = $collection->unique(function ($item) {
            return $item->id_material.".".$item->id_familia.".".$item->id_clasificador.".".$item->id_area_reporte;
        });
        return $unique;
    }
    public static function getMaterialesOC($id_obra){
        $resultados = DB::connection("cadeco")->select("
            select reporte_materiales_orden_compra.id_material,material, unidad, sum(cantidad_compra) as cantidad_compra, sum(precio_compra)/count(Equipamiento.reporte_materiales_orden_compra.id_material) as precio_compra, 
            moneda_compra,
            sum(precio_compra_moneda_comparativa)/count(Equipamiento.reporte_materiales_orden_compra.id_material) as precio_compra_moneda_comparativa,
            sum(importe_compra_moneda_comparativa) as importe_compra_moneda_comparativa,
			 STUFF((
                    SELECT ',' + cast(numero_folio_orden_compra as varchar)
                    FROM Equipamiento.reporte_materiales_orden_compra as materiales_oc2 
                    WHERE materiales_oc2.id_material = Equipamiento.reporte_materiales_orden_compra.id_material
                FOR XML PATH (''))
                , 1, 1, '') as ordenes_compra,
			 STUFF((
                    SELECT ',' + cast(id_orden_compra as varchar)
                    FROM Equipamiento.reporte_materiales_orden_compra as materiales_oc2 
                    WHERE materiales_oc2.id_material = Equipamiento.reporte_materiales_orden_compra.id_material
                FOR XML PATH (''))
                , 1, 1, '') as id_orden_compra,
                Equipamiento.reporte_materiales_orden_compra.id_familia,
                Equipamiento.reporte_materiales_orden_compra.familia,
				STUFF((
                    SELECT ',' + cast(fecha_entrega as varchar)
                    FROM Equipamiento.materiales_fechas_entrega as materiales_oc2 
                    WHERE materiales_oc2.id_material = Equipamiento.materiales_fechas_entrega.id_material
                FOR XML PATH (''))
                , 1, 1, '') as fechas_entrega
            from Equipamiento.reporte_materiales_orden_compra left join Equipamiento.materiales_fechas_entrega
on(materiales_fechas_entrega.id_material = Equipamiento.reporte_materiales_orden_compra.id_material)
            where id_obra = {$id_obra}
                group by 
                reporte_materiales_orden_compra.id_material,Equipamiento.materiales_fechas_entrega.id_material,material, unidad, moneda_compra, id_familia, familia
            order by material");
            
        return collect($resultados);
    }
    
    public static function getMaterialesOCVSREQVENN($id_obra){
        $resultados = DB::connection("cadeco")->select("select caso, count(*) as size from (
select 

CASE 
		WHEN  
		materiales_oc.id_material_compra IS NULL AND 
		materiales_requeridos.id_material_requerido IS NOT NULL
		THEN 'REQUERIDO'
		WHEN  
		materiales_oc.id_material_compra IS NOT NULL AND 
		materiales_requeridos.id_material_requerido IS NULL
		THEN 'COMPRADO'
		ELSE 'REQUERIDO Y COMPRADO'
	END caso,
 * from ( 
SELECT 
	Equipamiento.reporte_materiales_orden_compra.id_material AS id_material_compra,

    Equipamiento.reporte_materiales_orden_compra.material as material_compra,
	Equipamiento.reporte_materiales_orden_compra.unidad,

	Equipamiento.reporte_materiales_orden_compra.unidad AS unidad_compra, 
	SUM(Equipamiento.reporte_materiales_orden_compra.cantidad_compra) AS cantidad_compra, 
	SUM(Equipamiento.reporte_materiales_orden_compra.precio_compra)/count(*) AS precio_compra, 
    Equipamiento.reporte_materiales_orden_compra.moneda_compra, 
	SUM(Equipamiento.reporte_materiales_orden_compra.precio_compra_moneda_comparativa)/count(*) AS precio_compra_moneda_comparativa, 
    SUM(Equipamiento.reporte_materiales_orden_compra.importe_compra_moneda_comparativa) AS importe_compra_moneda_comparativa 
FROM            
    Equipamiento.reporte_materiales_orden_compra 
WHERE id_obra = {$id_obra}
GROUP BY 
	Equipamiento.reporte_materiales_orden_compra.id_material, 
	Equipamiento.reporte_materiales_orden_compra.material, 
	Equipamiento.reporte_materiales_orden_compra.unidad, 
	Equipamiento.reporte_materiales_orden_compra.moneda_compra
) as materiales_oc  FULL OUTER JOIN ( 
SELECT 
    Equipamiento.reporte_materiales_requeridos_area.id_material as id_material_requerido, 
	Equipamiento.reporte_materiales_requeridos_area.material AS material_requerido, 
    Equipamiento.reporte_materiales_requeridos_area.unidad AS unidad_requerida, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.cantidad_requerida) AS cantidad_requerida, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.precio_estimado)/count(*) AS precio_requerido, 
	Equipamiento.reporte_materiales_requeridos_area.moneda_requerida, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.precio_requerido_moneda_comparativa)/count(*) AS precio_requerido_moneda_comparativa, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.importe_requerido_moneda_comparativa) AS importe_requerido_moneda_comparativa
FROM            
	Equipamiento.reporte_materiales_requeridos_area 
GROUP BY 
	Equipamiento.reporte_materiales_requeridos_area.id_material, 
	Equipamiento.reporte_materiales_requeridos_area.material, 
	Equipamiento.reporte_materiales_requeridos_area.unidad, 
	Equipamiento.reporte_materiales_requeridos_area.moneda_requerida 
) AS materiales_requeridos ON(materiales_requeridos.id_material_requerido = materiales_oc.id_material_compra)
) as resultado
group by caso");
$col =  collect($resultados);
$salida[$col[0]->caso] = $col[0]->size + $col[2]->size;
$salida[$col[1]->caso] = $col[1]->size + $col[2]->size;
$salida[$col[2]->caso] = $col[2]->size;

return $salida;
    }

    public static function getMaterialesOCVSREQ($id_obra){
        $resultados = DB::connection("cadeco")->select("
           
select 
CASE WHEN  materiales_oc.id_material_compra IS NULL THEN materiales_requeridos.id_material_requerido
ELSE materiales_oc.id_material_compra END id_material,
 CASE WHEN  materiales_oc.material_compra IS NULL THEN materiales_requeridos.material_requerido
ELSE materiales_oc.material_compra END material,

CASE WHEN  materiales_oc.id_familia_compra IS NULL THEN materiales_requeridos.id_familia_requerida
ELSE materiales_oc.id_familia_compra END id_familia,
 CASE WHEN  materiales_oc.familia_compra IS NULL THEN materiales_requeridos.familia_requerida
ELSE materiales_oc.familia_compra END familia,

CASE WHEN  materiales_oc.unidad_compra IS NULL THEN materiales_requeridos.unidad_requerida
ELSE materiales_oc.unidad_compra END unidad,
CASE 
		WHEN  
		materiales_oc.id_material_compra IS NULL AND 
		materiales_requeridos.id_material_requerido IS NOT NULL
		THEN 'REQUERIDO'
		WHEN  
		materiales_oc.id_material_compra IS NOT NULL AND 
		materiales_requeridos.id_material_requerido IS NULL
		THEN 'COMPRADO'
		ELSE 'REQUERIDO Y COMPRADO'
	END caso,
 * from ( 
SELECT 
	Equipamiento.reporte_materiales_orden_compra.id_material AS id_material_compra,
        Equipamiento.reporte_materiales_orden_compra.id_familia AS id_familia_compra,
Equipamiento.reporte_materiales_orden_compra.familia AS familia_compra,
    Equipamiento.reporte_materiales_orden_compra.material as material_compra,
	Equipamiento.reporte_materiales_orden_compra.unidad,

	Equipamiento.reporte_materiales_orden_compra.unidad AS unidad_compra, 
	SUM(Equipamiento.reporte_materiales_orden_compra.cantidad_compra) AS cantidad_compra, 
	SUM(Equipamiento.reporte_materiales_orden_compra.precio_compra)/count(*) AS precio_compra, 
    Equipamiento.reporte_materiales_orden_compra.moneda_compra, 
	SUM(Equipamiento.reporte_materiales_orden_compra.precio_compra_moneda_comparativa)/count(*) AS precio_compra_moneda_comparativa, 
    SUM(Equipamiento.reporte_materiales_orden_compra.importe_compra_moneda_comparativa) AS importe_compra_moneda_comparativa 
FROM            
    Equipamiento.reporte_materiales_orden_compra 
WHERE id_obra = {$id_obra}
GROUP BY 
	Equipamiento.reporte_materiales_orden_compra.id_material, 
	Equipamiento.reporte_materiales_orden_compra.material, 
        Equipamiento.reporte_materiales_orden_compra.id_familia, 
	Equipamiento.reporte_materiales_orden_compra.familia, 
	Equipamiento.reporte_materiales_orden_compra.unidad, 
	Equipamiento.reporte_materiales_orden_compra.moneda_compra
) as materiales_oc  FULL OUTER JOIN ( 
SELECT 
    Equipamiento.reporte_materiales_requeridos_area.id_material as id_material_requerido, 
	Equipamiento.reporte_materiales_requeridos_area.material AS material_requerido,
        Equipamiento.reporte_materiales_requeridos_area.id_familia as id_familia_requerida, 
	Equipamiento.reporte_materiales_requeridos_area.familia AS familia_requerida, 
    Equipamiento.reporte_materiales_requeridos_area.unidad AS unidad_requerida, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.cantidad_requerida) AS cantidad_requerida, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.precio_estimado)/count(*) AS precio_requerido, 
	Equipamiento.reporte_materiales_requeridos_area.moneda_requerida, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.precio_requerido_moneda_comparativa)/count(*) AS precio_requerido_moneda_comparativa, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.importe_requerido_moneda_comparativa) AS importe_requerido_moneda_comparativa
FROM            
	Equipamiento.reporte_materiales_requeridos_area 
GROUP BY 
	Equipamiento.reporte_materiales_requeridos_area.id_material, 
	Equipamiento.reporte_materiales_requeridos_area.material,
        Equipamiento.reporte_materiales_requeridos_area.id_familia, 
	Equipamiento.reporte_materiales_requeridos_area.familia,
	Equipamiento.reporte_materiales_requeridos_area.unidad, 
	Equipamiento.reporte_materiales_requeridos_area.moneda_requerida 
) AS materiales_requeridos ON(materiales_requeridos.id_material_requerido = materiales_oc.id_material_compra)
order by material; ");
            
        return collect($resultados);
    }
    
    public static function getMaterialesOCXLS($id_obra){
        $resultados = DB::connection("cadeco")->select("
            select Equipamiento.reporte_materiales_orden_compra.id_material,material,id_familia, familia, unidad, sum(cantidad_compra) as cantidad_compra, sum(precio_compra)/count(Equipamiento.reporte_materiales_orden_compra.id_material) as precio_compra, 
            moneda_compra,
            sum(precio_compra_moneda_comparativa)/count(Equipamiento.reporte_materiales_orden_compra.id_material) as precio_compra_moneda_comparativa,
            sum(importe_compra_moneda_comparativa) as importe_compra_moneda_comparativa,
			 STUFF((
                    SELECT ',' + cast(numero_folio_orden_compra as varchar)
                    FROM Equipamiento.reporte_materiales_orden_compra as materiales_oc2 
                    WHERE materiales_oc2.id_material = Equipamiento.reporte_materiales_orden_compra.id_material
                FOR XML PATH (''))
                , 1, 1, '') as ordenes_compra, 
				STUFF((
                    SELECT ',' + cast(fecha_entrega as varchar)
                    FROM Equipamiento.materiales_fechas_entrega as materiales_oc2 
                    WHERE materiales_oc2.id_material = Equipamiento.materiales_fechas_entrega.id_material
                FOR XML PATH (''))
                , 1, 1, '') as fechas_entrega
            from Equipamiento.reporte_materiales_orden_compra left join Equipamiento.materiales_fechas_entrega
on(materiales_fechas_entrega.id_material = Equipamiento.reporte_materiales_orden_compra.id_material)
            where id_obra = {$id_obra}
                group by Equipamiento.materiales_fechas_entrega.id_material,
                Equipamiento.reporte_materiales_orden_compra.id_material,material, unidad, moneda_compra,id_familia, familia
            order by material");
            //dd(json_decode(json_encode($resultados), true));
        return  json_decode(json_encode($resultados), true);
    }
    
    public static function getMaterialesOCVSREQXLS($id_obra){
        $resultados = DB::connection("cadeco")->select("
           
select 
CASE WHEN  materiales_oc.id_material_compra IS NULL THEN materiales_requeridos.id_material_requerido
ELSE materiales_oc.id_material_compra END id_material,
 CASE WHEN  materiales_oc.material_compra IS NULL THEN materiales_requeridos.material_requerido
ELSE materiales_oc.material_compra END material,

CASE WHEN  materiales_oc.id_familia_compra IS NULL THEN materiales_requeridos.id_familia_requerida
ELSE materiales_oc.id_familia_compra END id_familia,
 CASE WHEN  materiales_oc.familia_compra IS NULL THEN materiales_requeridos.familia_requerida
ELSE materiales_oc.familia_compra END familia,

CASE WHEN  materiales_oc.unidad_compra IS NULL THEN materiales_requeridos.unidad_requerida
ELSE materiales_oc.unidad_compra END unidad,
 cantidad_compra, precio_compra, moneda_compra, precio_compra_moneda_comparativa,
 importe_compra_moneda_comparativa,
 cantidad_requerida,
 precio_requerido, moneda_requerida, precio_requerido_moneda_comparativa, importe_requerido_moneda_comparativa,
CASE 
		WHEN  
		materiales_oc.id_material_compra IS NULL AND 
		materiales_requeridos.id_material_requerido IS NOT NULL
		THEN 'REQUERIDO'
		WHEN  
		materiales_oc.id_material_compra IS NOT NULL AND 
		materiales_requeridos.id_material_requerido IS NULL
		THEN 'COMPRADO'
		ELSE 'REQUERIDO Y COMPRADO'
	END caso  from ( 
SELECT 
	Equipamiento.reporte_materiales_orden_compra.id_material AS id_material_compra,
        Equipamiento.reporte_materiales_orden_compra.material as material_compra,

        Equipamiento.reporte_materiales_orden_compra.id_familia AS id_familia_compra,
        Equipamiento.reporte_materiales_orden_compra.familia as familia_compra,

	Equipamiento.reporte_materiales_orden_compra.unidad,

	Equipamiento.reporte_materiales_orden_compra.unidad AS unidad_compra, 
	SUM(Equipamiento.reporte_materiales_orden_compra.cantidad_compra) AS cantidad_compra, 
	SUM(Equipamiento.reporte_materiales_orden_compra.precio_compra)/count(*) AS precio_compra, 
    Equipamiento.reporte_materiales_orden_compra.moneda_compra, 
	SUM(Equipamiento.reporte_materiales_orden_compra.precio_compra_moneda_comparativa)/count(*) AS precio_compra_moneda_comparativa, 
    SUM(Equipamiento.reporte_materiales_orden_compra.importe_compra_moneda_comparativa) AS importe_compra_moneda_comparativa 
FROM            
    Equipamiento.reporte_materiales_orden_compra 
WHERE id_obra = {$id_obra}
GROUP BY 
	Equipamiento.reporte_materiales_orden_compra.id_material, 
	Equipamiento.reporte_materiales_orden_compra.material,
        Equipamiento.reporte_materiales_orden_compra.id_familia, 
	Equipamiento.reporte_materiales_orden_compra.familia, 
	Equipamiento.reporte_materiales_orden_compra.unidad, 
	Equipamiento.reporte_materiales_orden_compra.moneda_compra
) as materiales_oc  FULL OUTER JOIN ( 
SELECT 
    Equipamiento.reporte_materiales_requeridos_area.id_material as id_material_requerido, 
	Equipamiento.reporte_materiales_requeridos_area.material AS material_requerido,

    Equipamiento.reporte_materiales_requeridos_area.id_familia as id_familia_requerida, 
    Equipamiento.reporte_materiales_requeridos_area.familia AS familia_requerida, 

    Equipamiento.reporte_materiales_requeridos_area.unidad AS unidad_requerida, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.cantidad_requerida) AS cantidad_requerida, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.precio_estimado)/count(*) AS precio_requerido, 
	Equipamiento.reporte_materiales_requeridos_area.moneda_requerida, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.precio_requerido_moneda_comparativa)/count(*) AS precio_requerido_moneda_comparativa, 
    SUM(Equipamiento.reporte_materiales_requeridos_area.importe_requerido_moneda_comparativa) AS importe_requerido_moneda_comparativa
FROM            
	Equipamiento.reporte_materiales_requeridos_area 
GROUP BY 
	Equipamiento.reporte_materiales_requeridos_area.id_material, 
	Equipamiento.reporte_materiales_requeridos_area.material, 
        Equipamiento.reporte_materiales_requeridos_area.id_familia, 
	Equipamiento.reporte_materiales_requeridos_area.familia, 
	Equipamiento.reporte_materiales_requeridos_area.unidad, 
	Equipamiento.reporte_materiales_requeridos_area.moneda_requerida 
) AS materiales_requeridos ON(materiales_requeridos.id_material_requerido = materiales_oc.id_material_compra)
order by material; ");
            
        return json_decode(json_encode($resultados), true);
    }

    public static function getDatosXLS($id_moneda_esperada, $tipos_cambio, $filtros_consulta){
        $filtros = " and 1 = 1 ";
        if(count($filtros_consulta["areas_tipo"])>0){
            $filtros .= " and id_tipo_area in(".  implode(",", $filtros_consulta["areas_tipo"])." ) ";
        }
        if(count($filtros_consulta["areas"])>0){
            $filtros .= " and id_area in(".  implode(",", $filtros_consulta["areas"])." ) ";
        }
//        if(count($filtros_consulta["areas"])>0){
//            $filtros .= " and id_area in(".  implode(",", $filtros_consulta["areas"])." ) ";
//        }
        if(count($filtros_consulta["casos"])>0){
            $filtros .= " and id_caso in(".  implode(",", $filtros_consulta["casos"])." ) ";
        }
        if(count($filtros_consulta["clasificadores"])){
            $filtros .= " and id_clasificador in(".  implode(",", $filtros_consulta["clasificadores"])." ) ";
        }
        if($filtros_consulta["descripcion"] != ""){
            $filtros .= " and material like '%".$filtros_consulta["descripcion"]."%' ";
        }
        if(count($filtros_consulta["errores"])>0){
            $errores_finales = [];
            $tiene_error_nulo = false;
            foreach($filtros_consulta["errores"] as $error){
                if($error > 1){
                    $errores_finales[] = $error;
                }else{
                    $tiene_error_nulo = true;
                    
                }
            }
            if(count($errores_finales)>0){
                if($tiene_error_nulo === true){
                    $filtros .= " and (id_error is null or( id_error in(".  implode(",", $errores_finales)." ) ))";
                }else{
                    $filtros .= " and id_error in(".  implode(",", $errores_finales)." ) ";
                }
                
            }else{
                if($tiene_error_nulo){
                    $filtros .= " and id_error is null";
                }
            }
        }
        if(count($filtros_consulta["grados_variacion"])>0){
            $filtros .= " and id_grado_variacion in(".  implode(",", $filtros_consulta["grados_variacion"])." ) ";
        }
        if(count($filtros_consulta["familias"])>0){
            $filtros .= " and id_familia in(".  implode(",", $filtros_consulta["familias"])." ) ";
        }

        
        $resultados = DB::connection("cadeco")->select("
            select
id_material,
count(idmateriales_requeridos_area) as veces_requerida,
clasificador, familia, material, unidad, 
sum(cantidad_requerida) as cantidad_requerida, precio_estimado,moneda_requerida, precio_requerido_moneda_comparativa, 
sum(importe_requerido_moneda_comparativa) as importe_requerido_moneda_comparativa,
sum(cantidad_comparativa) as cantidad_comparativa, precio_proyecto_comparativo, moneda_comparativa, precio_comparativa_moneda_comparativa,
sum(importe_comparativa_moneda_comparativa) as importe_comparativa_moneda_comparativa,
sum(sobrecosto) as sobrecosto,
sum(ahorro) as ahorro,
sum(indice_variacion)/count(idmateriales_requeridos_area) as indice_variacion,
grado_variacion,
estilo_grado_variacion,
STUFF((
            SELECT ',' + caso
            FROM Equipamiento.reporte_materiales_requeridos_area as mra
            WHERE mra.id_material = Equipamiento.reporte_materiales_requeridos_area.id_material
            group by caso
            FOR XML PATH (''))
            , 1, 1, '') as caso
,
            STUFF((
            SELECT ',' + error
            FROM Equipamiento.reporte_materiales_requeridos_area as mra
            WHERE mra.id_material = Equipamiento.reporte_materiales_requeridos_area.id_material
            group by error
            FOR XML PATH (''))
            , 1, 1, '') as error_concat
            
            from Equipamiento.reporte_materiales_requeridos_area where 1=1 ". $filtros ."
			group by 
			id_material,
			 clasificador, familia, material, unidad, precio_estimado,moneda_requerida, precio_requerido_moneda_comparativa,
			  precio_proyecto_comparativo, moneda_comparativa, precio_comparativa_moneda_comparativa,
			 grado_variacion, estilo_grado_variacion
			 order by material 
                
                ");
        
        return  json_decode(json_encode($resultados), true);
    }
    public static function getPartidasComparativa(){
        $resultados = DB::connection("cadeco")->select("
            SELECT        tipo, cantidad, CASE WHEN cantidad_comparativa > 0 THEN (cantidad - cantidad_comparativa) / cantidad_comparativa * 100 ELSE 0 END AS variacion_cantidad, 
                         numero_modulos, CASE WHEN numero_modulos_comparativa > 0 THEN (numero_modulos - numero_modulos_comparativa) 
                         / numero_modulos_comparativa * 100 ELSE 0 END AS variacion_modulos, pax, CASE WHEN pax_comparativa > 0 THEN (pax - pax_comparativa) 
                         / pax_comparativa * 100 ELSE 0 END AS variacion_pax, importe_presupuesto_manual * cantidad AS importe_presupuesto_manual_x_cantidad, 
                         importe_presupuesto_manual * cantidad / numero_modulos AS importe_presupuesto_manual_x_cantidad_s_modulos, 
                         importe_presupuesto_manual * cantidad / pax AS importe_presupuesto_manual_x_cantidad_s_pax, 
                         importe_presupuesto_calculado * cantidad AS importe_presupuesto_calculado_x_cantidad, 
                         importe_presupuesto_calculado * cantidad / numero_modulos AS importe_presupuesto_calculado_x_cantidad_s_modulos, 
                         importe_presupuesto_calculado * cantidad / pax AS importe_presupuesto_calculado_x_cantidad_s_pax, 
                         importe_compras_emitidas * cantidad AS importe_compras_emitidas_x_cantidad, 
                         importe_compras_emitidas * cantidad / numero_modulos AS importe_compras_emitidas_x_cantidad_s_modulos, 
                         importe_compras_emitidas * cantidad / pax AS importe_compras_emitidas_x_cantidad_s_pax, cantidad_comparativa, numero_modulos_comparativa, 
                         pax_comparativa, importe_presupuesto_comparativa_manual * cantidad_comparativa AS importe_presupuesto_manual_x_cantidad_comparativa, 
                         CASE WHEN numero_modulos_comparativa > 0 THEN importe_presupuesto_comparativa_manual * cantidad_comparativa / numero_modulos_comparativa ELSE 0 END
                          AS importe_presupuesto_manual_x_cantidad_comparativa_s_nm, 
                         CASE WHEN pax_comparativa > 0 THEN importe_presupuesto_comparativa_manual * cantidad_comparativa / pax_comparativa ELSE 0 END AS importe_presupuesto_manual_x_cantidad_comparativa_s_pax,
                          importe_presupuesto_comparativa_calculado * cantidad_comparativa AS importe_presupuesto_calculado_x_cantidad_comparativa, 
                         CASE WHEN numero_modulos_comparativa > 0 THEN importe_presupuesto_comparativa_calculado * cantidad_comparativa / numero_modulos_comparativa ELSE 0
                          END AS importe_presupuesto_calculado_x_cantidad_comparativa_s_nm, 
                         CASE WHEN pax_comparativa > 0 THEN importe_presupuesto_comparativa_calculado * cantidad_comparativa / pax_comparativa ELSE 0 END AS importe_presupuesto_calculado_x_cantidad_comparativa_s_pax
FROM            Equipamiento.reporte_tipo
            ");
            
        return collect($resultados);
    }
    public static function getPartidasComparativaXLS(){
        $resultados = DB::connection("cadeco")->select("
            SELECT        tipo, cantidad, CASE WHEN cantidad_comparativa > 0 THEN (cantidad - cantidad_comparativa) / cantidad_comparativa * 100 ELSE 0 END AS variacion_cantidad, 
                         numero_modulos, CASE WHEN numero_modulos_comparativa > 0 THEN (numero_modulos - numero_modulos_comparativa) 
                         / numero_modulos_comparativa * 100 ELSE 0 END AS variacion_modulos, pax, CASE WHEN pax_comparativa > 0 THEN (pax - pax_comparativa) 
                         / pax_comparativa * 100 ELSE 0 END AS variacion_pax, importe_presupuesto_manual * cantidad AS importe_presupuesto_manual_x_cantidad, 
                         importe_presupuesto_manual * cantidad / numero_modulos AS importe_presupuesto_manual_x_cantidad_s_modulos, 
                         importe_presupuesto_manual * cantidad / pax AS importe_presupuesto_manual_x_cantidad_s_pax, 
                         importe_presupuesto_calculado * cantidad AS importe_presupuesto_calculado_x_cantidad, 
                         importe_presupuesto_calculado * cantidad / numero_modulos AS importe_presupuesto_calculado_x_cantidad_s_modulos, 
                         importe_presupuesto_calculado * cantidad / pax AS importe_presupuesto_calculado_x_cantidad_s_pax, 
                         importe_compras_emitidas * cantidad AS importe_compras_emitidas_x_cantidad, 
                         importe_compras_emitidas * cantidad / numero_modulos AS importe_compras_emitidas_x_cantidad_s_modulos, 
                         importe_compras_emitidas * cantidad / pax AS importe_compras_emitidas_x_cantidad_s_pax, cantidad_comparativa, numero_modulos_comparativa, 
                         pax_comparativa, importe_presupuesto_comparativa_manual * cantidad_comparativa AS importe_presupuesto_manual_x_cantidad_comparativa, 
                         CASE WHEN numero_modulos_comparativa > 0 THEN importe_presupuesto_comparativa_manual * cantidad_comparativa / numero_modulos_comparativa ELSE 0 END
                          AS importe_presupuesto_manual_x_cantidad_comparativa_s_nm, 
                         CASE WHEN pax_comparativa > 0 THEN importe_presupuesto_comparativa_manual * cantidad_comparativa / pax_comparativa ELSE 0 END AS importe_presupuesto_manual_x_cantidad_comparativa_s_pax,
                          importe_presupuesto_comparativa_calculado * cantidad_comparativa AS importe_presupuesto_calculado_x_cantidad_comparativa, 
                         CASE WHEN numero_modulos_comparativa > 0 THEN importe_presupuesto_comparativa_calculado * cantidad_comparativa / numero_modulos_comparativa ELSE 0
                          END AS importe_presupuesto_calculado_x_cantidad_comparativa_s_nm, 
                         CASE WHEN pax_comparativa > 0 THEN importe_presupuesto_comparativa_calculado * cantidad_comparativa / pax_comparativa ELSE 0 END AS importe_presupuesto_calculado_x_cantidad_comparativa_s_pax
FROM            Equipamiento.reporte_tipo
            ");
            
        return  json_decode(json_encode($resultados), true);
    }
    
    
    
    public static function getDatos($id_moneda_esperada, $tipos_cambio, $filtros_consulta){
        
        $filtros = " and 1 = 1 ";
        if(count($filtros_consulta["areas_tipo"])>0){
            $filtros .= " and id_tipo_area in(".  implode(",", $filtros_consulta["areas_tipo"])." ) ";
        }
        if(count($filtros_consulta["areas"])>0){
            $filtros .= " and id_area in(".  implode(",", $filtros_consulta["areas"])." ) ";
        }
//        if(count($filtros_consulta["areas"])>0){
//            $filtros .= " and id_area in(".  implode(",", $filtros_consulta["areas"])." ) ";
//        }
        if(count($filtros_consulta["casos"])>0){
            $filtros .= " and id_caso in(".  implode(",", $filtros_consulta["casos"])." ) ";
        }
        if(count($filtros_consulta["clasificadores"])){
            $filtros .= " and id_clasificador in(".  implode(",", $filtros_consulta["clasificadores"])." ) ";
        }
        if($filtros_consulta["descripcion"] != ""){
            $filtros .= " and material like '%".$filtros_consulta["descripcion"]."%' ";
        }
        if(count($filtros_consulta["errores"])>0){
            $errores_finales = [];
            $tiene_error_nulo = false;
            foreach($filtros_consulta["errores"] as $error){
                if($error > 1){
                    $errores_finales[] = $error;
                }else{
                    $tiene_error_nulo = true;
                    
                }
            }
            if(count($errores_finales)>0){
                if($tiene_error_nulo === true){
                    $filtros .= " and (id_error is null or( id_error in(".  implode(",", $errores_finales)." ) ))";
                }else{
                    $filtros .= " and id_error in(".  implode(",", $errores_finales)." ) ";
                }
                
            }else{
                if($tiene_error_nulo){
                    $filtros .= " and id_error is null";
                }
            }
        }
        if(count($filtros_consulta["grados_variacion"])>0){
            $filtros .= " and id_grado_variacion in(".  implode(",", $filtros_consulta["grados_variacion"])." ) ";
        }
        if(count($filtros_consulta["familias"])>0){
            $filtros .= " and id_familia in(".  implode(",", $filtros_consulta["familias"])." ) ";
        }

        
        $resultados = DB::connection("cadeco")->select("
            select
id_material,
count(idmateriales_requeridos_area) as veces_requerida,
clasificador, familia, material, unidad, 
sum(cantidad_requerida) as cantidad_requerida, precio_estimado,moneda_requerida, precio_requerido_moneda_comparativa, 
sum(importe_requerido_moneda_comparativa) as importe_requerido_moneda_comparativa,
sum(cantidad_comparativa) as cantidad_comparativa, precio_proyecto_comparativo, moneda_comparativa, precio_comparativa_moneda_comparativa,
sum(importe_comparativa_moneda_comparativa) as importe_comparativa_moneda_comparativa,
sum(sobrecosto) as sobrecosto,
sum(ahorro) as ahorro,
sum(indice_variacion)/count(idmateriales_requeridos_area) as indice_variacion,
grado_variacion,
estilo_grado_variacion,


            STUFF((
            SELECT ',' + caso
            FROM Equipamiento.reporte_materiales_requeridos_area as mra
            WHERE mra.id_material = Equipamiento.reporte_materiales_requeridos_area.id_material
            group by caso
            FOR XML PATH (''))
            , 1, 1, '') as caso
,
            STUFF((
            SELECT ',' + error
            FROM Equipamiento.reporte_materiales_requeridos_area as mra
            WHERE mra.id_material = Equipamiento.reporte_materiales_requeridos_area.id_material
            group by error
            FOR XML PATH (''))
            , 1, 1, '') as error_concat
            
            from Equipamiento.reporte_materiales_requeridos_area where 1=1 ". $filtros ."
			group by 
			id_material,
			 clasificador, familia, material, unidad, precio_estimado,moneda_requerida, precio_requerido_moneda_comparativa,
			  precio_proyecto_comparativo, moneda_comparativa, precio_comparativa_moneda_comparativa,
			 grado_variacion, estilo_grado_variacion
			 order by material 
                
                ");
        
        
        $costo_total_proyecto = 0;
        $costo_total_proyecto_comparativa = 0;
        $sobrecosto_total = 0;
        $ahorro_total = 0;
        $resumen_casos = [];
        foreach($resultados as $resultado){
            if(is_numeric($resultado->precio_estimado)){
                $resultado->precio_estimado_f = number_format($resultado->precio_estimado,2,".",",");
            }else{
                $resultado->precio_estimado_f = "";
            }
            $resultado->precio_proyecto_comparativo_f = number_format($resultado->precio_proyecto_comparativo,2,".",",");
            
            $resultado->importe_requerido_moneda_comparativa_f = number_format($resultado->importe_requerido_moneda_comparativa,2,".",",");
            $resultado->importe_comparativa_moneda_comparativa_f = number_format($resultado->importe_comparativa_moneda_comparativa,2,".",",");
            $resultado->precio_requerido_moneda_comparativa_f = number_format($resultado->precio_requerido_moneda_comparativa,2,".",",");
            $resultado->precio_comparativa_moneda_comparativa_f = number_format($resultado->precio_comparativa_moneda_comparativa,2,".",",");
            $resultado->sobrecosto_f = number_format($resultado->sobrecosto,2,".",",");
            $resultado->ahorro_f = number_format($resultado->ahorro,2,".",",");
            
            if(!($resultado->indice_variacion == -1000000001)){
                $resultado->indice_variacion_f = number_format($resultado->indice_variacion,2,".",",");
            }else{
                $resultado->indice_variacion_f = "";
            }
            
            $costo_total_proyecto = $costo_total_proyecto+$resultado->importe_requerido_moneda_comparativa;
            $resultado->costo_total_proyecto = $costo_total_proyecto;
            $resultado->costo_total_proyecto_f = number_format($resultado->costo_total_proyecto,2,".",",");
            
            $costo_total_proyecto_comparativa = $costo_total_proyecto_comparativa+$resultado->importe_comparativa_moneda_comparativa;
            $resultado->costo_total_proyecto_comparativa = $costo_total_proyecto_comparativa;
            $resultado->costo_total_proyecto_comparativa_f = number_format($resultado->costo_total_proyecto_comparativa,2,".",",");
            
            $sobrecosto_total = $sobrecosto_total+$resultado->sobrecosto;
            $resultado->sobrecosto_total = $sobrecosto_total;
            $resultado->sobrecosto_total_f = number_format($resultado->sobrecosto_total,2,".",",");
            
            $ahorro_total = $ahorro_total+$resultado->ahorro;
            $resultado->ahorro_total = $ahorro_total;
            $resultado->ahorro_total_f = number_format($resultado->ahorro_total,2,".",",");
        }
        $informacion_articulos_esperados["articulos_esperados"] = $resultados;
        $informacion_articulos_esperados["resumen"]["casos"] = $resumen_casos;
        return $informacion_articulos_esperados;
    }
    
    public static function getFiltros(){
        $resultados = DB::connection("cadeco")->select("
            select * from Equipamiento.reporte_grado_variacion 
        ");
        $filtros["grados_variacion"] = $resultados;
        
        $casos = DB::connection("cadeco")->select("
           select * from Equipamiento.reporte_casos where id in(1,2,3,5,7)
        ");
        $filtros["casos"] = $casos;
        
        $errores = DB::connection("cadeco")->select("
            select * from Equipamiento.reporte_errores as errores order by id
        ");
        $filtros["errores"] = $errores;
        
        $clasificadores = DB::connection("cadeco")->select("
            select * from Equipamiento.material_clasificadores
        ");
        $filtros["clasificadores"] = $clasificadores;
        $areas_tipo = DB::connection("cadeco")->select("
            select distinct(id_tipo_area) as id, ruta_tipo_area as nombre from Equipamiento.reporte_materiales_requeridos_area
            order by ruta_tipo_area;
        ");
        
        $filtros["areas_tipo"] = $areas_tipo;
        $familias = DB::connection("cadeco")->select("
            select distinct(id_familia) as id, familia from Equipamiento.reporte_materiales_requeridos_area
            order by familia;
        ");
        $filtros["familias"] = $familias;
        return $filtros;
    }
}
