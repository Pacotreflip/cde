<?php

namespace Ghi\Equipamiento\Reporte;
use Illuminate\Support\Facades\DB;
use \Ghi\Equipamiento\Areas\AreaTipo;

class Reporte {
    public static function getMaterialesOC($id_obra){
        $resultados = DB::connection("cadeco")->select("
            select id_material,material, unidad, sum(cantidad_compra) as cantidad_compra, sum(precio_compra)/count(id_material) as precio_compra, 
            moneda_compra,
            sum(precio_compra_moneda_comparativa)/count(id_material) as precio_compra_moneda_comparativa,
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
                Equipamiento.reporte_materiales_orden_compra.familia
            from Equipamiento.reporte_materiales_orden_compra
            where id_obra = {$id_obra}
                group by 
                id_material,material, unidad, moneda_compra, id_familia, familia
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
$salida[$col[0]->caso] = $col[0]->size;
$salida[$col[1]->caso] = $col[1]->size;
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
            select id_material,material,id_familia, familia, unidad, sum(cantidad_compra) as cantidad_compra, sum(precio_compra)/count(id_material) as precio_compra, 
            moneda_compra,
            sum(precio_compra_moneda_comparativa)/count(id_material) as precio_compra_moneda_comparativa,
            sum(importe_compra_moneda_comparativa) as importe_compra_moneda_comparativa,
			 STUFF((
                    SELECT ',' + cast(numero_folio_orden_compra as varchar)
                    FROM Equipamiento.reporte_materiales_orden_compra as materiales_oc2 
                    WHERE materiales_oc2.id_material = Equipamiento.reporte_materiales_orden_compra.id_material
                FOR XML PATH (''))
                , 1, 1, '') as ordenes_compra
            from Equipamiento.reporte_materiales_orden_compra
            where id_obra = {$id_obra}
                group by 
                id_material,material, unidad, moneda_compra,id_familia, familia
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
