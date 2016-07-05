<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ghi\Equipamiento\Transferencias;

use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Areas\Almacen;
use Ghi\Equipamiento\Areas\Concepto;
use Ghi\Equipamiento\Recepciones\TransferenciaAlmacen;
use Ghi\Equipamiento\Recepciones\TransferenciaAlmacenItem;
/**
 * Description of AuxiliaresTransaccionesSAO
 *
 * @author EMartinez
 */
trait AuxiliaresTransaccionesSAO {
    
    protected $transacciones = [];
    
    protected $preparacion_transferencias = [];
    
    protected $preparacion_salidas = [];
    
    protected $preparacion_entrada = [];
    
    protected $items_ids = [];
    
    protected function orden_compra_cumplida(){
        $resultado = DB::connection('cadeco')->select('select items_oc.id_item, items_oc.cantidad as cantidad_esperada, sum(items_ea.cantidad) as cantidad_entrada
            , (items_oc.cantidad - sum(items_ea.cantidad)) AS pendiente
            from items as items_oc join items as items_ea on(items_oc.id_item = items_ea.item_antecedente)
            where items_oc.id_transaccion = ' . $this->data["orden_compra"] .' 
            group by items_oc.id_item, items_oc.cantidad');
        $cumplida = true;
        
        foreach($resultado as $item){
            if($item->pendiente > 0){
                $cumplida = false;
                break;
            }
        }
        
        if($cumplida){
            DB::connection('cadeco')->table("transacciones")->where("id_transaccion", $this->data["orden_compra"])->update(["estado"=>2]);
        }
    }
    protected function preparaDatosTransacciones(){
        
        $items_a_procesar = $this->data["materiales"];
        
        foreach($items_a_procesar as $item_a_procesar){
            $this->cantidad_procesar = $item_a_procesar["cantidad"];
            $this->cantidad_disponible_entrada = $this->getCantidadDisponibleEntrada($item_a_procesar);
            $this->existencia_almacen = $this->getExistenciasAlmacen($item_a_procesar);
            $this->existencias_por_almacen = $this->getExistenciasXAlmacen($item_a_procesar);
            //dd($this->existencias_por_almacen);
            if(($this->cantidad_procesar-( $this->existencia_almacen))>0.01){
               throw new \Exception("No es posible transferir la cantidad indicada para el articulo {$item_a_procesar['descripcion']} no hay existencias suficientes en el SAO: Disponible Entrada: {$this->cantidad_disponible_entrada}; Disponible Transferencia: {$this->existencia_almacen}");
            }
            //dd($this->cantidad_procesar, $this->cantidad_disponible_entrada, $this->existencia_almacen, $this->existencias_por_almacen);
            while($this->cantidad_procesar > 0){
                //$datos_partida_entrada_sao = $this->getPartidasDisponiblesEntradaSAO($item_a_procesar["id_item"]);
                
                    
                $iex_al = 0;
                foreach($this->existencias_por_almacen as $existencia_por_almacen){
                    if($this->cantidad_procesar<= $existencia_por_almacen["existencias"] && $this->cantidad_procesar > 0 && $existencia_por_almacen["existencias"]>0){

                        $this->preparacion_transferencias["items"][$existencia_por_almacen["id_almacen"]][] = [
                            "id_almacen_origen"=>$existencia_por_almacen["id_almacen"],
                            "id_material"=>$item_a_procesar["id"],
                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                            "cantidad"=>$this->cantidad_procesar,
                            "id_almacen"=>$this->getIdAlmacenSAODeArea($item_a_procesar["area_destino"]),
                            "id_destino"=>$item_a_procesar["area_destino"],
                            "generada_en"=>3
                        ];
                        //$this->cantidad_procesar = $this->cantidad_procesar - $this->cantidad_procesar;

                        $this->existencias_por_almacen[$iex_al]["existencias"] = $this->existencias_por_almacen[$iex_al]["existencias"] - $this->cantidad_procesar;
                        $this->cantidad_procesar = 0;
                    }elseif($this->cantidad_procesar > $existencia_por_almacen["existencias"] && $this->cantidad_procesar > 0 && $existencia_por_almacen["existencias"]){

                        $this->preparacion_transferencias["items"][$existencia_por_almacen["id_almacen"]][] = [
                            "id_almacen_origen"=>$existencia_por_almacen["id_almacen"],
                            "id_material"=>$item_a_procesar["id"],
                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                            "cantidad"=>$existencia_por_almacen["existencias"],
                            "id_almacen"=>$this->getIdAlmacenSAODeArea($item_a_procesar["area_destino"]),
                            "id_destino"=>$item_a_procesar["area_destino"],
                            "generada_en"=>4
                        ];
                        $this->cantidad_procesar = $this->cantidad_procesar - $existencia_por_almacen["existencias"];
                        //$this->cantidad_procesar = $this->cantidad_procesar - $existencia_por_almacen["existencias"];
                        $this->existencias_por_almacen[$iex_al]["existencias"] = 0;
                    }
                    $iex_al++;
                }
            }
        }
        
        if(array_key_exists("items", $this->preparacion_transferencias)){
            $i = 0;
            foreach($this->preparacion_transferencias["items"] as $k=>$v){
                $this->transacciones["transferencias"][$i]["datos"] = [
                    "tipo_transaccion"=>34,
                    "fecha"=>$this->data["fecha_transferencia"],
                    "id_obra"=>$this->obra->id_obra,
                    "id_almacen"=>$k,
                    "referencia"=>"transferencia automática",
                    "observaciones"=>"transferencia automática",
                    "opciones"=>65537,
                ];
                $this->transacciones["transferencias"][$i]["items"] = $v;
                $i++;
            }
        }
        //dd($this->preparacion_transferencias, $this->transacciones);
    }
    protected function getIdConceptoRaiz(){
        $concepto_raiz = Concepto::whereRaw("id_obra = {$this->obra->id_obra} and len(nivel)=4  and control_equipamiento = 1")->first();
        return $concepto_raiz->id_concepto;
    }
    protected function getIdAlmacenSAODeArea($id_area){
        $area = Area::findOrFail($id_area);
        $almacen = $area->almacen;
        if($almacen){
            $id_almacen = $almacen->id_almacen;
        }else{
            $nombre_almacen = strtoupper(substr($area->soloRuta(),0,(255-(strlen($area->nombre)))) . " / " .$area->nombre);
            $almacen = new Almacen([
                "descripcion"=>$nombre_almacen,
                "tipo_almacen"=>"0",
            ]);
            $almacen->obra()->associate($this->obra);
            $almacen->save();
            $area->almacen()->associate($almacen);
            $area->save();
            $id_almacen = $area->id_almacen;
        }
        return $id_almacen;
    }
    protected function getIdConceptoSAODeArea($id_area){
        $area = Area::findOrFail($id_area);
        $concepto = $area->concepto;
        if($concepto){
            $id_concepto = $concepto->id_concepto;
        }else{
            $id_concepto = $area->setConcepto();
        }
        return $id_concepto;
    }
    protected function getUnidadMaterial($id_material){
        $resultado = DB::connection("cadeco")->select('
        SELECT
            unidad
        FROM
            materiales
        WHERE
            id_material = '.$id_material.' 
        ');
        return $resultado[0]->unidad;
    }
    protected function getMonedaOC($id_oc){
        $resultado = DB::connection("cadeco")->select('
        SELECT
            id_moneda
        FROM
            transacciones
        WHERE
            id_transaccion = '.$id_oc.' 
        ');
        return $resultado[0]->id_moneda;
    }
    protected function getEmpresaOC($id_oc){
        $resultado = DB::connection("cadeco")->select('
        SELECT
            id_empresa
        FROM
            transacciones
        WHERE
            id_transaccion = '.$id_oc.' 
        ');
        return $resultado[0]->id_empresa;
    }
    protected function getSucursalOC($id_oc){
        $resultado = DB::connection("cadeco")->select('
        SELECT
            id_sucursal
        FROM
            transacciones
        WHERE
            id_transaccion = '.$id_oc.' 
        ');
        return $resultado[0]->id_sucursal;
    }
    protected function getCantidadDisponibleEntrada($item_procesar){
        
         $resultado = DB::connection("cadeco")->select('
        SELECT
            c.[cantidad] - c.[surtida] AS cantidad_pendiente
        FROM
            ItemsComprados as c 
        WHERE
            c.id_obra = '.$this->obra->id_obra.' and
            c.id_material = '.$item_procesar["id"].' 
        ');
        return $resultado[0]->cantidad_pendiente;
    }
    protected function getExistenciasAlmacen($item_procesar){
        $resultado = DB::connection("cadeco")->select('
            SELECT
                  sum(inventarios.saldo) as existencias
            FROM
                inventarios 
                join almacenes on(almacenes.id_almacen = inventarios.id_almacen)
            WHERE
                almacenes.id_obra = '.$this->obra->id_obra.' AND
                inventarios.saldo > 0.01 AND
                inventarios.id_material = '.$item_procesar["id"].' 
        ');
        return $resultado[0]->existencias;
    }
    
    protected function getExistenciasXAlmacen($item_procesar){
        $resultado = DB::connection("cadeco")->select('
            SELECT
                inventarios.id_almacen as id_almacen,
                sum(inventarios.saldo) as existencias
            FROM
                inventarios 
                join almacenes on(almacenes.id_almacen = inventarios.id_almacen)
            WHERE
                almacenes.id_obra = '.$this->obra->id_obra.' AND
                inventarios.id_material = '.$item_procesar["id"].' AND
                inventarios.saldo > 0.01
            GROUP BY inventarios.id_almacen
            ORDER BY inventarios.id_almacen
        ');
        $existencias = null;
        $i = 0;
        foreach($resultado as $row){
            $existencias[$i]["id_almacen"] = $row->id_almacen;
            $existencias[$i]["existencias"] = $row->existencias;
            $i++;
        }
        return $existencias;
    }
    
    protected function getPartidasDisponilesTransferenciaSAO($item_procesar){
        
    }
    protected function acumuladoCantidadRecibir($item_procesar){
        $acumulado = 0;
        foreach ($item_procesar["destinos"] as $destinos){
            $acumulado += $destinos["cantidad"];
        }
        return $acumulado;
    }
    protected function getPartidasDisponiblesEntradaSAO($id_item_oc){
        
        $resultado = DB::connection("cadeco")->select('
        SELECT
              c.[numero_parte]
            , c.[descripcion]
            , c.[unidad_compra]
            , CONVERT(VARCHAR(10), c.fecha, 105) as fecha
            , c.[cantidad] - c.[surtida] AS cantidad_pendiente
            , c.surtida as cantidad_surtida
            , i.id_material as id_material
            , i.unidad as unidad
            , i.cantidad_material
            , i.precio_unitario
            , i.anticipo
            , i.saldo
            , i.id_item
        FROM
            ItemsComprados as c join items as i on(i.id_item = c.id_item)
        WHERE
            i.id_item = '.$id_item_oc.' 
            AND ( c.cantidad > c.surtida )
        ORDER BY
            c.id_material
        , c.numero_entrega;  
            ');
        return $resultado;
    }
    protected function creaEntradaAlmacen($datos_entrada)
    {
        $entrada_almacen = new EntradaAlmacen($datos_entrada["datos"]);
        
        $entrada_almacen->obra()->associate($this->obra);
        $entrada_almacen->save();
        
        $partidas_entrada = $this->creaPartidasEntradaAlmacen($datos_entrada["items"]);
        $entrada_almacen->items()->saveMany($partidas_entrada);
        $this->ejecuta_procedimiento_entrada($entrada_almacen);
        $this->actualiza_entregas($entrada_almacen);
        return $entrada_almacen;
    }
    
    protected function creaPartidasEntradaAlmacen($datos_partidas_entrada){
        $partidas = null;
        foreach($datos_partidas_entrada as $datos_partida_entrada){
            $partidas[] = new EntradaAlmacenItem($datos_partida_entrada);
        }
        return $partidas;
    }
    
    protected function ejecuta_procedimiento_entrada($objEntrada){
        foreach($objEntrada->items as $item){
            $resultado = DB::connection("cadeco")->select('DECLARE @RC int
                    DECLARE @id_item int
                    EXECUTE @RC = [sp_entrada_material] 
                    '.$item->id_item.' 
                    SELECT @RC as res');
            if($resultado[0]->res != 0){
                throw new \Exception("Hubo un error al aplicar el procedimiento de entrada de almacén para el material:" . $item->material->descripcion);
            }
            
        }
    }
    
    protected function actualiza_entregas($objEntrada){
        foreach($objEntrada->items as $item){
            DB::connection("cadeco")->table("entregas")
                ->where("id_item", $item->item_antecedente)
                ->increment("surtida", $item->cantidad);
        }
    }
    
    protected function creaTransferenciaAlmacen($datos_transferencia)
    {
        $transferencia_almacen = new TransferenciaAlmacen($datos_transferencia["datos"]);
        $transferencia_almacen->obra()->associate($this->obra);
        $transferencia_almacen->save();
        $partidas_transferencia = $this->creaPartidasTransferenciaAlmacen($datos_transferencia["items"]);
        $transferencia_almacen->items()->saveMany($partidas_transferencia);
        $this->ejecuta_procedimiento_salida_transferencia($transferencia_almacen);
        return $transferencia_almacen;
    }
    
    protected function creaPartidasTransferenciaAlmacen($datos_partidas_transferencia){
        $partidas = null;
        foreach($datos_partidas_transferencia as $datos_partida_transferencia){
            $partidas[] = new TransferenciaAlmacenItem($datos_partida_transferencia);
        }
        return $partidas;
    }
    
    protected function ejecuta_procedimiento_salida_transferencia($objTransferencia){
        foreach($objTransferencia->items as $item){
            $resultado = DB::connection("cadeco")->select('
                DECLARE @RC int
                DECLARE @id_item int
                EXECUTE @RC = [dbo].[sp_salida_material] 
                    '.$item->id_item.' 
                SELECT @RC as res
            ');
            if($resultado[0]->res != 0){
                throw new \Exception("Hubo un error al aplicar el procedimiento de salida /  transferencia de almacén para el material:" . $item->material->descripcion);
            }
        }
    }
    
    
    protected function creaSalidaAlmacen($datos_transferencia)
    {
        $transferencia_almacen = new TransferenciaAlmacen($datos_transferencia["datos"]);
        $transferencia_almacen->obra()->associate($this->obra);
        $transferencia_almacen->save();
        $partidas_transferencia = $this->creaPartidasSalidaAlmacen($datos_transferencia["items"]);
        $transferencia_almacen->items()->saveMany($partidas_transferencia);
        $this->ejecuta_procedimiento_salida_transferencia($transferencia_almacen);
        return $transferencia_almacen;
    }
    
    protected function creaPartidasSalidaAlmacen($datos_partidas_transferencia){
        $partidas = null;
        foreach($datos_partidas_transferencia as $datos_partida_transferencia){
            $partidas[] = new TransferenciaAlmacenItem($datos_partida_transferencia);
        }
        return $partidas;
    }
    protected function elimina_transaccion($objTransaccion){
        $resultado = DB::connection("cadeco")->select('
            DECLARE @RC int
            DECLARE @id_transaccion int
            EXECUTE @RC = [sp_borra_transaccion] 
            '.$objTransaccion->id_transaccion.'
            Select @RC as res     
        ');
        if($resultado[0]->res != 0){
            throw new \Exception("Hubo un error al aplicar el procedimiento de eliminación para la transacción:" . $objTransaccion->id_transaccion);
        }
    }
    
}



