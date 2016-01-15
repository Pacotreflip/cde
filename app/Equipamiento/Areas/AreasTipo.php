<?php

namespace Ghi\Equipamiento\Areas;

use Ghi\Core\Contracts\Context;
use Illuminate\Support\Facades\DB;
class AreasTipo
{
    /**
     * Contexto de la aplicacion.
     *
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Obtiene un tipo de area por su id.
     *
     * @param int $id
     * @return AreaTipo
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return AreaTipo::with('materialesRequeridos')->findOrFail($id);
    }

    /**
     * Obtiene la estructura completa de tipos.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return AreaTipo::where('id_obra', $this->context->getId())
            ->defaultOrder()
            ->withDepth()
            ->get();
    }

    /**
     * Obtiene los tipos que son raiz.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getNivelesRaiz()
    {
        return AreaTipo::where('id_obra', $this->context->getId())
            ->whereIsRoot()
            ->defaultOrder()
            ->get();
    }

    /**
     * Obtiene una lista de tipos como un arreglo.
     *
     * @return array
     */
    public function getListaTipos()
    {
        $tipos = $this->getAll();

        return $tipos->keyBy('id')->map(function ($area_tipo) {
            return str_repeat('-', $area_tipo->depth + 1).' '.$area_tipo->nombre;
        })->all();
    }

    /**
     * Obtiene una lista de los niveles hoja con su ruta completa.
     * 
     * @return array
     */
    public function getListaUltimosNiveles()
    {
        return AreaTipo::where('id_obra', $this->context->getId())
            ->onlyLeafs()
            ->defaultOrder()
            ->get()
            ->lists('ruta', 'id')
            ->all();
    }

    /**
     * Elimina un area tipo.
     *
     * @param AreaTipo $tipo
     * @return bool
     */
    public function delete(AreaTipo $tipo)
    {
        return $tipo->delete();
    }

    /**
     * Persiste los cambios de un area tipo.
     *
     * @param AreaTipo $tipo
     * @return bool|mixed
     */
    public function save(AreaTipo $tipo)
    {
        return $tipo->save();
    }
    
    public static function getArticulosEsperados($id_tipo_area, $id_moneda_esperada, $tipos_cambio, $filtros_consulta){
        
        
//        $resultados = DB::connection("cadeco")->select("SELECT        dbo.materiales.descripcion, dbo.materiales.unidad, Equipamiento.materiales_requeridos.cantidad_requerida, 
//                         Equipamiento.materiales_requeridos.precio_estimado AS importe_estimado, dbo.monedas.id_moneda AS idmoneda_requerida, 
//                         dbo.monedas.nombre AS moneda_requerida, Equipamiento.materiales_requeridos.cantidad_comparativa, 
//                         Equipamiento.materiales_requeridos.precio_comparativa AS importe_comparativa, monedas_1.id_moneda AS id_moneda_comparativa, 
//                         monedas_1.nombre AS moneda_comparativa, Equipamiento.materiales_requeridos.id_tipo_area, Equipamiento.material_clasificadores.id AS id_clasificador, 
//                         Equipamiento.material_clasificadores.nombre AS clasificador
//FROM            Equipamiento.material_clasificadores RIGHT OUTER JOIN
//                         dbo.materiales ON Equipamiento.material_clasificadores.id = dbo.materiales.id_clasificador RIGHT OUTER JOIN
//                         Equipamiento.materiales_requeridos LEFT OUTER JOIN
//                         dbo.monedas AS monedas_1 ON Equipamiento.materiales_requeridos.id_moneda_comparativa = monedas_1.id_moneda LEFT OUTER JOIN
//                         dbo.monedas ON Equipamiento.materiales_requeridos.id_moneda = dbo.monedas.id_moneda ON 
//                         dbo.materiales.id_material = Equipamiento.materiales_requeridos.id_material
//WHERE        (Equipamiento.materiales_requeridos.id_tipo_area = ?)", array($id_tipo_area));
        
        $filtros = " and 1 = 1 ";
        
        if(count($filtros_consulta["casos"])>0){
            $filtros .= " and casos.idcaso in(".  implode(",", $filtros_consulta["casos"])." ) ";
        }
        if(count($filtros_consulta["errores"])>0){
            $errores_finales = [];
            $tiene_error_nulo = false;
            foreach($filtros_consulta["errores"] as $error){
                if($error > 0){
                    $errores_finales[] = $error;
                }else{
                    $tiene_error_nulo = true;
                    
                }
            }
            if(count($errores_finales)>0){
                if($tiene_error_nulo === true){
                    $filtros .= " and (errores.iderror is null or( errores.iderror in(".  implode(",", $errores_finales)." ) ))";
                }else{
                    $filtros .= " and errores.iderror in(".  implode(",", $errores_finales)." ) ";
                }
                
            }else{
                if($tiene_error_nulo){
                    $filtros .= " and errores.iderror is null";
                }
            }
        }
        if(count($filtros_consulta["grados_variacion"])>0){
            $filtros .= " and grados_variacion.idgrado_variacion in(".  implode(",", $filtros_consulta["grados_variacion"])." ) ";
        }
        
        $resultados = DB::connection("cadeco")->select("
            SELECT        
                materiales.id_material
                , dbo.materiales.descripcion
                , dbo.materiales.unidad
                , Equipamiento.materiales_requeridos.cantidad_requerida
                , Equipamiento.materiales_requeridos.precio_estimado AS importe_estimado
                , dbo.monedas.id_moneda AS idmoneda_requerida
                , dbo.monedas.nombre AS moneda_requerida
                , Equipamiento.materiales_requeridos.cantidad_comparativa
                , Equipamiento.materiales_requeridos.precio_comparativa AS importe_comparativa
                , monedas_1.id_moneda AS id_moneda_comparativa
                , monedas_1.nombre AS moneda_comparativa
                , Equipamiento.materiales_requeridos.id_tipo_area
                , Equipamiento.material_clasificadores.id AS id_clasificador
                , Equipamiento.material_clasificadores.nombre AS clasificador
                , importes.importe_requerido_moneda_comparativa
                , importes.importe_comparativa_moneda_comparativa
                , importes.precio_requerido_moneda_comparativa
                , importes.precio_comparativa_moneda_comparativa
                , analisis.diferencia
                , analisis.sobrecosto
                , analisis.ahorro
                , analisis.indice_variacion
                , grados_variacion.grado_variacion
                , grados_variacion.estilo_grado_variacion
                , casos.caso,
                STUFF((
                    SELECT ',' + error
                    FROM Equipamiento.materiales_requeridos as materiales_requeridos9 LEFT JOIN
			(select id, case when (materiales_requeridos_5.cantidad_requerida > 0) and (not(materiales_requeridos_5.precio_estimado>0) or materiales_requeridos_5.precio_estimado is null) then 1 else 0 end iderror  from Equipamiento.materiales_requeridos as materiales_requeridos_5 union
                        select id, case when (materiales_requeridos_6.precio_estimado > 0) and (not(materiales_requeridos_6.cantidad_requerida>0) or materiales_requeridos_6.cantidad_requerida is null) then 2 else 0 end iderror from Equipamiento.materiales_requeridos as materiales_requeridos_6  union
                        select id, case when (materiales_requeridos_7.cantidad_comparativa > 0) and (not(materiales_requeridos_7.precio_comparativa>0) or materiales_requeridos_7.precio_comparativa is null) then 3 else 0 end iderror from Equipamiento.materiales_requeridos as materiales_requeridos_7  union
                        select id, case when (materiales_requeridos_8.precio_comparativa > 0) and (not(materiales_requeridos_8.cantidad_comparativa>0) or materiales_requeridos_8.cantidad_comparativa is null) then 4 else 0 end iderror from Equipamiento.materiales_requeridos as materiales_requeridos_8 
                        ) as error_partida ON(error_partida.id = Equipamiento.materiales_requeridos.id and error_partida.iderror>0)

                        LEFT JOIN (
                        select 'Existencia En Proyecto Sin Precio' as error,
                        cast(1 as int) as iderror union

                        select 'Sin Existencia en Proyecto Con Precio' as error,
                        cast(2 as int) as iderror union

                        select 'Existencia En Proyecto Comparativa Sin Precio' as error,
                        cast(3 as int) as iderror union

                        select 'Sin Existencia En Proyecto Comparativa Con Precio' as error,
                        cast(4 as int) as iderror
                        ) as errores on(errores.iderror = error_partida.iderror)
                    WHERE materiales_requeridos9.id = materiales_requeridos.id
                FOR XML PATH (''))
                , 1, 1, '') as error_concat

						 
            FROM
                Equipamiento.material_clasificadores RIGHT OUTER JOIN
                dbo.materiales ON Equipamiento.material_clasificadores.id = dbo.materiales.id_clasificador RIGHT OUTER JOIN
                Equipamiento.materiales_requeridos LEFT OUTER JOIN
                dbo.monedas AS monedas_1 ON Equipamiento.materiales_requeridos.id_moneda_comparativa = monedas_1.id_moneda LEFT OUTER JOIN
                dbo.monedas ON Equipamiento.materiales_requeridos.id_moneda = dbo.monedas.id_moneda ON 
                dbo.materiales.id_material = Equipamiento.materiales_requeridos.id_material
            JOIN(
                select 
                    Equipamiento.materiales_requeridos.id ,
                    case  when Equipamiento.materiales_requeridos.id_moneda > 0 and $id_moneda_esperada > 0 then 
                    (Equipamiento.materiales_requeridos.precio_estimado * Equipamiento.materiales_requeridos.cantidad_requerida) * (
                    (select tipo_cambio from (
                    select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
                    select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
                    select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio 
                    where idmoneda = Equipamiento.materiales_requeridos.id_moneda) 
                    /
                    (select tipo_cambio from (
                    select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
                    select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
                    select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio where idmoneda = $id_moneda_esperada)
                    ) else null end importe_requerido_moneda_comparativa,

                    case  when Equipamiento.materiales_requeridos.id_moneda_comparativa > 0 and $id_moneda_esperada > 0 then 
                    (Equipamiento.materiales_requeridos.precio_comparativa * Equipamiento.materiales_requeridos.cantidad_comparativa ) * (
                    (select tipo_cambio from (
                    select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
                    select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
                    select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio 
                    where idmoneda = Equipamiento.materiales_requeridos.id_moneda_comparativa) 
                    /
                    (select tipo_cambio from (
                    select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
                    select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
                    select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio where idmoneda = $id_moneda_esperada)
                    ) 
                    else null end importe_comparativa_moneda_comparativa,
                    
                    case  when Equipamiento.materiales_requeridos.id_moneda > 0 and $id_moneda_esperada > 0 then 
                    (Equipamiento.materiales_requeridos.precio_estimado ) * (
                    (select tipo_cambio from (
                    select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
                    select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
                    select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio 
                    where idmoneda = Equipamiento.materiales_requeridos.id_moneda) 
                    /
                    (select tipo_cambio from (
                    select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
                    select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
                    select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio where idmoneda = $id_moneda_esperada)
                    ) else null end precio_requerido_moneda_comparativa,

                    case  when Equipamiento.materiales_requeridos.id_moneda_comparativa > 0 and $id_moneda_esperada > 0 then 
                    (Equipamiento.materiales_requeridos.precio_comparativa ) * (
                    (select tipo_cambio from (
                    select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
                    select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
                    select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio 
                    where idmoneda = Equipamiento.materiales_requeridos.id_moneda_comparativa) 
                    /
                    (select tipo_cambio from (
                    select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
                    select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
                    select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio where idmoneda = $id_moneda_esperada)
                    ) 
                    else null end precio_comparativa_moneda_comparativa
		from Equipamiento.materiales_requeridos
            ) AS importes on(importes.id = Equipamiento.materiales_requeridos.id)
                                                 
JOIN (
							   select 
                                                           
							   case 
							   when not(importes_an.cantidad_comparativa > 0) or importes_an.cantidad_comparativa is null then 1
							   when not(importes_an.cantidad_requerida > 0) or importes_an.cantidad_requerida is null then 2
							   when importes_an.cantidad_comparativa>0 and importes_an.cantidad_requerida>0 
							   and (importes_an.importe_requerido_moneda_comparativa/importes_an.cantidad_requerida)
							   >
							   (importes_an.importe_comparativa_moneda_comparativa/importes_an.cantidad_comparativa)  then 3
							   
							   when (not(importes_an.cantidad_comparativa>0) or not(importes_an.cantidad_requerida>0) or importes_an.cantidad_comparativa is null
							   or importes_an.cantidad_requerida is null) 
							   and ((importes_an.importe_requerido_moneda_comparativa)
							   >
							   (importes_an.importe_comparativa_moneda_comparativa))  then 3

							   when importes_an.cantidad_comparativa>0 and importes_an.cantidad_requerida>0 
							   and (importes_an.importe_requerido_moneda_comparativa/importes_an.cantidad_requerida)
							   <
							   (importes_an.importe_comparativa_moneda_comparativa/importes_an.cantidad_comparativa)  then 5

							   when (not(importes_an.cantidad_comparativa>0) or not(importes_an.cantidad_requerida>0) or importes_an.cantidad_comparativa is null
							   or importes_an.cantidad_requerida is null) 
							   and (importes_an.importe_requerido_moneda_comparativa)
							   <
							   (importes_an.importe_comparativa_moneda_comparativa)  then 5
                                                           
                                                           when importes_an.cantidad_comparativa>0 and importes_an.cantidad_requerida>0 
							   and abs(importes_an.importe_requerido_moneda_comparativa - importes_an.importe_comparativa_moneda_comparativa)<0.1
							     then 7
							   else 0

							    end idcaso,
							   importes_an.id,
							   importes_an.importe_requerido_moneda_comparativa - importes_an.importe_comparativa_moneda_comparativa AS diferencia,
															   CASE WHEN importes_an.importe_requerido_moneda_comparativa - importes_an.importe_comparativa_moneda_comparativa > 0 THEN importes_an.importe_requerido_moneda_comparativa
                          - importes_an.importe_comparativa_moneda_comparativa ELSE 0.0 END AS sobrecosto, 
                         CASE WHEN importes_an.importe_requerido_moneda_comparativa - importes_an.importe_comparativa_moneda_comparativa < 0 THEN abs(importes_an.importe_requerido_moneda_comparativa
                          - importes_an.importe_comparativa_moneda_comparativa) ELSE 0.0 END AS ahorro,
						  
						case when importes_an.precio_requerido_moneda_comparativa>0 and importes_an.precio_comparativa_moneda_comparativa > 0
                                                    then((importes_an.precio_requerido_moneda_comparativa - importes_an.precio_comparativa_moneda_comparativa)/importes_an.precio_comparativa_moneda_comparativa) * 100
                                                    
                                                    else -1000000001  
                                                end indice_variacion
						  from (
						  
						  select materiales_requeridos_2.id , materiales_requeridos_2.cantidad_requerida,materiales_requeridos_2.cantidad_comparativa,
                                                 case  when materiales_requeridos_2.id_moneda > 0 and $id_moneda_esperada > 0 then 
						 (materiales_requeridos_2.precio_estimado * materiales_requeridos_2.cantidad_requerida ) * (
						 (select tipo_cambio from (
  select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
  select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
  select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio 
  where idmoneda = materiales_requeridos_2.id_moneda) 
  /
  (select tipo_cambio from (
  select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
  select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
  select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio where idmoneda = $id_moneda_esperada)
  ) else null end importe_requerido_moneda_comparativa,

  						 case  when materiales_requeridos_2.id_moneda_comparativa > 0 and $id_moneda_esperada > 0 then 
						 (materiales_requeridos_2.precio_comparativa * materiales_requeridos_2.cantidad_comparativa ) * (
						 (select tipo_cambio from (
  select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
  select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
  select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio 
  where idmoneda = materiales_requeridos_2.id_moneda_comparativa) 
  /
  (select tipo_cambio from (
  select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
  select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
  select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio where idmoneda = $id_moneda_esperada)
  ) 
  else null end importe_comparativa_moneda_comparativa,
  
  case  when materiales_requeridos_2.id_moneda > 0 and $id_moneda_esperada > 0 then 
						 (materiales_requeridos_2.precio_estimado ) * (
						 (select tipo_cambio from (
  select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
  select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
  select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio 
  where idmoneda = materiales_requeridos_2.id_moneda) 
  /
  (select tipo_cambio from (
  select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
  select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
  select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio where idmoneda = $id_moneda_esperada)
  ) else null end precio_requerido_moneda_comparativa,

  						 case  when materiales_requeridos_2.id_moneda_comparativa > 0 and $id_moneda_esperada > 0 then 
						 (materiales_requeridos_2.precio_comparativa ) * (
						 (select tipo_cambio from (
  select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
  select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
  select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio 
  where idmoneda = materiales_requeridos_2.id_moneda_comparativa) 
  /
  (select tipo_cambio from (
  select cast(".$tipos_cambio[1]." as float) as tipo_cambio, 1 as idmoneda union
  select cast(".$tipos_cambio[2]." as float) as tipo_cambio, 2 as idmoneda union
  select cast(".$tipos_cambio[3]." as float) as tipo_cambio, 3 as idmoneda) as tipos_cambio where idmoneda = $id_moneda_esperada)
  ) 
  else null end precio_comparativa_moneda_comparativa
						 from Equipamiento.materiales_requeridos as materiales_requeridos_2
						  
						  
						  ) as importes_an
							   ) 
							   as analisis on(analisis.id = importes.id)
 left JOIN (
 
    select 
    
'No Aplica' as grado_variacion,
0 as idgrado_variacion,
-1000000001  as limite_inferior,
-1000000001  as limite_superior,
'D8D8D8' as estilo_grado_variacion
 union


    select 'Más de 5 Veces' as grado_variacion,
            1 as idgrado_variacion,
cast(400 as float) as limite_inferior,
cast(100000000 as float) as limite_superior,
'f00' as estilo_grado_variacion
 union

select 'Más del Doble' as grado_variacion,
2 as idgrado_variacion,
cast(100 as float) limite_inferior,
cast(399 as float) as limite_superior,
'FF5500' as estilo_grado_variacion union

select 'Más Caro' as grado_variacion,
3 as idgrado_variacion,
cast(0.1 as float) limite_inferior,
cast(99.99  as float)as limite_superior,
'e6b800' as estilo_grado_variacion union

select 'Mismo Precio' as grado_variacion,
4 as idgrado_variacion,
cast(0 as float) limite_inferior,
cast(0 as float) as limite_superior,
'9FF781' as estilo_grado_variacion  union

select 'Más Barato' as grado_variacion,
5 as idgrado_variacion,
cast(-100000000 as float) limite_inferior,
cast(-0.1 as float) as limite_superior,
'99ff33' as estilo_grado_variacion
) AS grados_variacion on( analisis.indice_variacion between grados_variacion.limite_inferior and grados_variacion.limite_superior )

left join (
							  select 'Sólo Existe en el Proyecto' as caso,
cast(1 as int) as idcaso

 union

select 'Sólo Existe en el Proyecto de Comparativa' as caso,
cast(2 as int) as idcaso union

select 'Más Caro en Proyecto' as caso,
cast(3 as int) as idcaso union

select 'Más Caro en Proyecto (error cantidad)' as caso,
cast(4 as int) as idcaso union

select 'Más Barato en Proyecto' as caso,
cast(5 as int) as idcaso  union

select 'Más Barato en Proyecto (error cantidad)' as caso,
cast(6 as int) as idcaso  union

select 'Mismo Precio' as caso,
cast(7 as int) as idcaso) as casos on(casos.idcaso = analisis.idcaso)

left JOIN(
select id, case when (materiales_requeridos_5.cantidad_requerida > 0) and (not(materiales_requeridos_5.precio_estimado>0) or materiales_requeridos_5.precio_estimado is null) then 1 else 0 end iderror  from Equipamiento.materiales_requeridos as materiales_requeridos_5 union
select id, case when (materiales_requeridos_6.precio_estimado > 0) and (not(materiales_requeridos_6.cantidad_requerida>0) or materiales_requeridos_6.cantidad_requerida is null) then 2 else 0 end iderror from Equipamiento.materiales_requeridos as materiales_requeridos_6  union
select id, case when (materiales_requeridos_7.cantidad_comparativa > 0) and (not(materiales_requeridos_7.precio_comparativa>0) or materiales_requeridos_7.precio_comparativa is null) then 3 else 0 end iderror from Equipamiento.materiales_requeridos as materiales_requeridos_7  union
select id, case when (materiales_requeridos_8.precio_comparativa > 0) and (not(materiales_requeridos_8.cantidad_comparativa>0) or materiales_requeridos_8.cantidad_comparativa is null) then 4 else 0 end iderror from Equipamiento.materiales_requeridos as materiales_requeridos_8 
) as error_partida ON(error_partida.id = Equipamiento.materiales_requeridos.id and error_partida.iderror>0)

left JOIN (

select 'Sin Errores' as error,
cast(0 as int) as iderror union

select 'Existencia En Proyecto Sin Precio' as error,
cast(1 as int) as iderror union

select 'Sin Existencia en Proyecto Con Precio' as error,
cast(2 as int) as iderror union

select 'Existencia En Proyecto Comparativa Sin Precio' as error,
cast(3 as int) as iderror union

select 'Sin Existencia En Proyecto Comparativa Con Precio' as error,
cast(4 as int) as iderror
) as errores on(errores.iderror = error_partida.iderror)

WHERE        (Equipamiento.materiales_requeridos.id_tipo_area = ?) $filtros
group by materiales.id_material,Equipamiento.materiales_requeridos.id,dbo.materiales.descripcion, dbo.materiales.unidad, Equipamiento.materiales_requeridos.cantidad_requerida, 
                         Equipamiento.materiales_requeridos.precio_estimado , dbo.monedas.id_moneda , 
                         dbo.monedas.nombre , Equipamiento.materiales_requeridos.cantidad_comparativa, 
                         Equipamiento.materiales_requeridos.precio_comparativa , monedas_1.id_moneda , 
                         monedas_1.nombre , Equipamiento.materiales_requeridos.id_tipo_area, Equipamiento.material_clasificadores.id , 
                         Equipamiento.material_clasificadores.nombre , importes.importe_requerido_moneda_comparativa, 
                         importes.importe_comparativa_moneda_comparativa, 
                         importes.precio_requerido_moneda_comparativa, 
                         importes.precio_comparativa_moneda_comparativa, 
                         analisis.diferencia, 
                         analisis.sobrecosto, 
                         analisis.ahorro,
						 analisis.indice_variacion,
						 grados_variacion.grado_variacion,
						 grados_variacion.estilo_grado_variacion,
						 casos.caso
						 
;  
",array($id_tipo_area));
        $costo_total_proyecto = 0;
        $costo_total_proyecto_comparativa = 0;
        $sobrecosto_total = 0;
        $ahorro_total = 0;
        $resumen_casos = [];
        foreach($resultados as $resultado){
            
            if(is_numeric($resultado->importe_estimado)){
                $resultado->importe_estimado_f = number_format($resultado->importe_estimado,2,".",",");
            }else{
                $resultado->importe_estimado_f = "";
            }
            $resultado->importe_comparativa_f = number_format($resultado->importe_comparativa,2,".",",");
            
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
            
//            $resumen_casos[$resultado->caso]["caso"] = $resultado->caso;
//            $resumen_casos[$resultado->caso]["resultado"] = $resultado->caso;
            
                
        }
        $informacion_articulos_esperados["articulos_esperados"] = $resultados;
        $informacion_articulos_esperados["resumen"]["casos"] = $resumen_casos;
        return $informacion_articulos_esperados;
        
    }
    public static function getFiltros(){
        $resultados = DB::connection("cadeco")->select("
            select 'No Aplica' as grado_variacion,
            0 as idgrado_variacion,
            -1000000001  as limite_inferior,
            -1000000001  as limite_superior,
            'D8D8D8' as estilo_grado_variacion
             union
             
            select 'Más de 5 Veces' as grado_variacion,
            1 as idgrado_variacion,
            cast(400 as float) as limite_inferior,
            cast(100000000 as float) as limite_superior,
            'f00' as estilo_grado_variacion
             union

            select 'Más del Doble' as grado_variacion,
            2 as idgrado_variacion,
            cast(100 as float) limite_inferior,
            cast(399 as float) as limite_superior,
            'ff3300' as estilo_grado_variacion union

            select 'Más Caro' as grado_variacion,
            3 as idgrado_variacion,
            cast(0.1 as float) limite_inferior,
            cast(99.99  as float)as limite_superior,
            'e6b800' as estilo_grado_variacion union

            select 'Mismo Precio' as grado_variacion,
            4 as idgrado_variacion,
            cast(0 as float) limite_inferior,
            cast(0 as float) as limite_superior,
            '9FF781' as estilo_grado_variacion  union

            select 'Más Barato' as grado_variacion,
            5 as grado_variacion,
            cast(-100000000 as float) limite_inferior,
            cast(-0.1 as float) as limite_superior,
            '99ff33' as estilo_grado_variacion  
        ");
        $filtros["grados_variacion"] = $resultados;
        
        $casos = DB::connection("cadeco")->select("
            select 'Sólo Existe en el Proyecto' as caso,
            cast(1 as int) as idcaso union

            select 'Sólo Existe en el Proyecto de Comparativa' as caso,
            cast(2 as int) as idcaso union

            select 'Más Caro en Proyecto' as caso,
            cast(3 as int) as idcaso union

            

            select 'Más Barato en Proyecto' as caso,
            cast(5 as int) as idcaso  union

           

            select 'Mismo Precio' as caso,
            cast(7 as int) as idcaso
        ");
        $filtros["casos"] = $casos;
        
        $errores = DB::connection("cadeco")->select("
            select * from (
            select 'Sin Errores' as error,
            cast(0 as int) as iderror union

            select 'Existencia En Proyecto Sin Precio' as error,
            cast(1 as int) as iderror union

            select 'Sin Existencia en Proyecto Con Precio' as error,
            cast(2 as int) as iderror union

            select 'Existencia En Proyecto Comparativa Sin Precio' as error,
            cast(3 as int) as iderror union

            select 'Sin Existencia En Proyecto Comparativa Con Precio' as error,
            cast(4 as int) as iderror) as errores order by iderror
        ");
        $filtros["errores"] = $errores;
        return $filtros;
    }
}
