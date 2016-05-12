<?php

namespace Ghi\Equipamiento\Reporte;
use Illuminate\Support\Facades\DB;
use \Ghi\Equipamiento\Areas\AreaTipo;
class Reporte {
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
//        $resultados = DB::connection("cadeco")->select("
//            select reporte_materiales_requeridos_area.*, 
//            STUFF((
//            SELECT ',' + error
//            FROM Equipamiento.reporte_materiales_requeridos_area as mra
//            WHERE mra.idmateriales_requeridos_area = Equipamiento.reporte_materiales_requeridos_area.idmateriales_requeridos_area
//            FOR XML PATH (''))
//            , 1, 1, '') as error_concat
//            
//            from Equipamiento.reporte_materiales_requeridos_area where 1=1 ". $filtros ."
//                
//                ");
        
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
caso
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
			 cantidad_comparativa, precio_proyecto_comparativo, moneda_comparativa, precio_comparativa_moneda_comparativa,
			 grado_variacion, estilo_grado_variacion,caso
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
