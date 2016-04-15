<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Transacciones\Item;
use Ghi\Equipamiento\Recepciones\Exceptions\RecepcionSinArticulosException;
use Ghi\Equipamiento\Areas\Almacen;

class RecibeArticulosAlmacen
{
    protected $data;

    protected $obra;
    
    protected $transacciones = null;
    
    protected $preparacion_transferencias = null;
    
    

    /**
     * @param array $data
     * @param Obra  $obra
     */
    public function __construct(array $data, Obra $obra)
    {
        $this->data = $data;
        $this->obra = $obra;
    }

    /**
     * Genera una recepcion de articulos.
     *
     * @return Recepcion
     * @throws \Exception
     */
    public function save()
    {
        try {
            DB::connection('cadeco')->beginTransaction();
            $proceso_sao = $this->procesoSAO();
            
            $recepcion = $this->creaRecepcion();
            foreach($proceso_sao as $transaccion){
                
            }
            foreach ($this->data['materiales'] as $item) {
                $material = Material::where('id_material', $item['id'])->first();
                $itemOrdenCompra = Item::findOrFail($item['id_item']);

                foreach ($item['destinos'] as $destino) {
                    $area = Area::findOrFail($destino['id']);

                    if (! $itemOrdenCompra->puedeRecibir($destino['cantidad'])) {
                        throw new \Exception("No es posible recibir la cantidad indicada para el articulo {$item['descripcion']}");
                    }

                    $recepcion->agregaMaterial($material, $destino['cantidad'], $itemOrdenCompra->id_item, $area);
                }
            }

            if ($recepcion->items->count() === 0) {
                throw new RecepcionSinArticulosException;
            }

            $recepcion->save();
            
            DB::connection('cadeco')->commit();
        } catch (\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }

        return $recepcion;
    }

    /**
     * Crea una nueva recepcion.
     * 
     * @return Recepcion
     */
    protected function creaRecepcion()
    {
        $recepcion = new Recepcion($this->data);
        $recepcion->obra()->associate($this->obra);
        $recepcion->id_empresa = $this->data['proveedor'];
        $recepcion->id_orden_compra = $this->data['orden_compra'];
        $recepcion->creado_por = Auth::user()->usuario;
        $recepcion->id_usuario = Auth::user()->idusuario;
        $recepcion->save();

        return $recepcion;
    }
    
    protected function procesoSAO(){
        $this->preparaDatosTransacciones();
        dd($this->data,$this->transacciones);
        
        foreach($datos_transacciones["entradas_almacen"] as $datos_entrada){
            $transacciones [] = $this->creaEntradaAlmacen($datos_entrada);
        }
        foreach($datos_transacciones["transferencias_almacen"] as $datos_transferencia){
            $transacciones [] = $this->creaTransferenciaAlmacen($datos_transferencia);
        }
        return $transacciones;
    }
    protected function preparaDatosTransacciones(){
        $no_transferencia = 0;
        $items_a_procesar = $this->data["materiales"];
        
        foreach($items_a_procesar as $item_a_procesar){
            $this->cantidad_procesar = $this->acumuladoCantidadRecibir($item_a_procesar);
            $this->cantidad_disponible_entrada = $this->getCantidadDisponibleEntrada($item_a_procesar);
            $this->existencia_almacen = $this->getExistenciasAlmacen($item_a_procesar);
            $this->existencias_por_almacen = $this->getExistenciasXAlmacen($item_a_procesar);
            if(($this->cantidad_procesar-($this->cantidad_disponible_entrada + $this->existencia_almacen))>0.01){
               throw new \Exception("No es posible recibir la cantidad indicada para el articulo {$item_a_procesar['descripcion']} no hay existencias suficientes en el SAO: Disponible Entrada: {$this->cantidad_disponible_entrada}; Disponible Transferencia: {$this->existencia_almacen}");
            }
            //dd($this->cantidad_procesar,$this->cantidad_disponible_entrada,$this->existencia_almacen );
            while($this->cantidad_procesar > 0){
                $datos_partida_entrada_sao = $this->getPartidasDisponiblesEntradaSAO($item_a_procesar["id_item"]);
                if($this->cantidad_procesar <= $this->cantidad_disponible_entrada){
                    foreach($item_a_procesar["destinos"] as $destinos){
                        $this->destino_cantidad = $destinos["cantidad"];
                        $this->transacciones["entrada"]["items"][] = [
                            "id_antecedente"=>$this->data["orden_compra"],
                            "item_antecedente"=>$datos_partida_entrada_sao[0]->id_item,
                            "id_material"=>$datos_partida_entrada_sao[0]->id_material,
                            "unidad"=>$datos_partida_entrada_sao[0]->unidad,
                            "numero"=>1,
                            "cantidad"=>$destinos["cantidad"],
                            "cantidad_material"=>$datos_partida_entrada_sao[0]->cantidad_pendiente,
                            "saldo"=>$destinos["cantidad"] * $datos_partida_entrada_sao[0]->precio_unitario,
                            "precio_unitario"=> $datos_partida_entrada_sao[0]->precio_unitario,
                            "anticipo"=>$datos_partida_entrada_sao[0]->anticipo,
                            "cantidad_original"=>$this->destino_cantidad,
                            "estado"=>0,
                            "importe"=>$destinos["cantidad"] * $datos_partida_entrada_sao[0]->precio_unitario,
                            "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                        ];
                        
                        $this->cantidad_procesar = $this->cantidad_procesar - $this->destino_cantidad;
                        $this->cantidad_disponible_entrada = $this->cantidad_disponible_entrada - $destinos["cantidad"];
                        $this->destino_cantidad  = 0;
                    }
                }else if($this->cantidad_procesar > $this->cantidad_disponible_entrada && $this->cantidad_disponible_entrada > 0){
                    $iides = 0;
                    foreach($item_a_procesar["destinos"] as $destinos){
                        
                        $this->destino_cantidad = $destinos["cantidad"];
//                        if($iides >0 ){
//                            dd($destinos,$this->destino_cantidad,$this->cantidad_disponible_entrada, $this->cantidad_procesar);
//                        }
                        while ($this->destino_cantidad>0){
                            if($this->destino_cantidad<=$this->cantidad_disponible_entrada){

                                $this->transacciones["entrada"]["items"][] = [
                                    "id_antecedente"=>$this->data["orden_compra"],
                                    "item_antecedente"=>$datos_partida_entrada_sao[0]->id_item,
                                    "id_material"=>$datos_partida_entrada_sao[0]->id_material,
                                    "unidad"=>$datos_partida_entrada_sao[0]->unidad,
                                    "numero"=>1,
                                    "cantidad"=>$this->destino_cantidad,
                                    "cantidad_material"=>$datos_partida_entrada_sao[0]->cantidad_pendiente,
                                    "saldo"=>$this->destino_cantidad * $datos_partida_entrada_sao[0]->precio_unitario,
                                    "precio_unitario"=> $datos_partida_entrada_sao[0]->precio_unitario,
                                    "anticipo"=>$datos_partida_entrada_sao[0]->anticipo,
                                    "cantidad_original"=>$this->destino_cantidad,
                                    "estado"=>0,
                                    "importe"=>$this->destino_cantidad * $datos_partida_entrada_sao[0]->precio_unitario,
                                    "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                ];

                                $this->cantidad_procesar = $this->cantidad_procesar - $this->destino_cantidad;
                                $this->cantidad_disponible_entrada = $this->cantidad_disponible_entrada - $this->destino_cantidad;
                                $this->destino_cantidad = 0;
                            }elseif($this->destino_cantidad > $this->cantidad_disponible_entrada && $this->cantidad_disponible_entrada>0){

                                $this->transacciones["entrada"]["items"][] = [
                                    "id_antecedente"=>$this->data["orden_compra"],
                                    "item_antecedente"=>$datos_partida_entrada_sao[0]->id_item,
                                    "id_material"=>$datos_partida_entrada_sao[0]->id_material,
                                    "unidad"=>$datos_partida_entrada_sao[0]->unidad,
                                    "numero"=>1,
                                    "cantidad"=>$this->cantidad_disponible_entrada,
                                    "cantidad_material"=>$datos_partida_entrada_sao[0]->cantidad_pendiente,
                                    "saldo"=>$this->cantidad_disponible_entrada * $datos_partida_entrada_sao[0]->precio_unitario,
                                    "precio_unitario"=> $datos_partida_entrada_sao[0]->precio_unitario,
                                    "anticipo"=>$datos_partida_entrada_sao[0]->anticipo,
                                    "cantidad_original"=>$this->cantidad_disponible_entrada,
                                    "estado"=>0,
                                    "importe"=>$this->cantidad_disponible_entrada * $datos_partida_entrada_sao[0]->precio_unitario,
                                    "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                ];
                                $this->destino_cantidad = $this->destino_cantidad - $this->cantidad_disponible_entrada;//6-4: 2
                                $this->cantidad_procesar = $this->cantidad_procesar - $this->cantidad_disponible_entrada;//8-4: 4
                                $this->cantidad_disponible_entrada = 0;
                                $iex_al = 0;
                                
                                //dd("aquiii", $this->destino_cantidad, $this->existencias_por_almacen);
                                //while($this->destino_cantidad > 0){
                                    
                                foreach($this->existencias_por_almacen as $existencia_por_almacen){
//                                        if($iex_al == 1){
//                                            dd($this->cantidad_procesar, $this->destino_cantidad, $existencia_por_almacen["existencias"]);
//                                        }
                                    if($this->destino_cantidad<= $existencia_por_almacen["existencias"] && $this->destino_cantidad > 0){
                                        $this->transacciones["transferencia"][$no_transferencia]["datos"] = [
                                            "tipo_transaccion"=>34,
                                            "fecha"=>$this->data["fecha_recepcion"],
                                            "id_obra"=>$this->obra->id_obra,
                                            "id_almacen"=>$existencia_por_almacen["id_almacen"],
                                            "referencia"=>"transferencia automática",
                                            "observaciones"=>"transferencia automática",
                                            "opciones"=>65537,
                                        ];
                                        $this->transacciones["transferencia"][$no_transferencia]["item"] = [
                                            "id_material"=>$item_a_procesar["id"],
                                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                            "cantidad"=>$this->destino_cantidad,
                                            "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                        ];
                                        $this->preparacion_transferencias["items"][$existencia_por_almacen["id_almacen"]][] = [
                                            "id_almacen_origen"=>$existencia_por_almacen["id_almacen"],
                                            "id_material"=>$item_a_procesar["id"],
                                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                            "cantidad"=>$this->destino_cantidad,
                                            "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                            "id_destino"=>$destinos["id"],
                                            "generada_en"=>1
                                        ];
                                        $no_transferencia++;
                                        $this->cantidad_procesar = $this->cantidad_procesar - $this->destino_cantidad;

                                        $this->existencias_por_almacen[$iex_al]["existencias"] = $this->existencias_por_almacen[$iex_al]["existencias"] - $this->destino_cantidad;
                                        $this->destino_cantidad = 0;
//dd("si llega");
                                    }elseif($this->destino_cantidad > $existencia_por_almacen["existencias"] && $this->destino_cantidad > 0){
                                        $this->transacciones["transferencia"][$no_transferencia]["datos"] = [
                                            "tipo_transaccion"=>34,
                                            "fecha"=>$this->data["fecha_recepcion"],
                                            "id_obra"=>$this->obra->id_obra,
                                            "id_almacen"=>$existencia_por_almacen["id_almacen"],
                                            "referencia"=>"transferencia automática",
                                            "observaciones"=>"transferencia automática",
                                            "opciones"=>65537,
                                        ];
                                        $this->transacciones["transferencia"][$no_transferencia]["item"] = [
                                            "id_material"=>$item_a_procesar["id"],
                                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                            "cantidad"=>$existencia_por_almacen["existencias"],
                                            "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                        ];
                                        $this->preparacion_transferencias["items"][$existencia_por_almacen["id_almacen"]][] = [
                                            "id_almacen_origen"=>$existencia_por_almacen["id_almacen"],
                                            "id_material"=>$item_a_procesar["id"],
                                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                            "cantidad"=>$existencia_por_almacen["existencias"],
                                            "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                            "id_destino"=>$destinos["id"],
                                            "generada_en"=>2
                                        ];
                                        $no_transferencia++;
                                        $this->destino_cantidad = $this->destino_cantidad - $existencia_por_almacen["existencias"];
                                        $this->cantidad_procesar = $this->cantidad_procesar - $existencia_por_almacen["existencias"];
                                        $this->existencias_por_almacen[$iex_al]["existencias"] = 0;
                                    }
                                    $iex_al++;
                                }
                                //}
                                
                            }else{
                                $iex_al = 0;
                                    
                                foreach($this->existencias_por_almacen as $existencia_por_almacen){

                                    if($this->destino_cantidad<= $existencia_por_almacen["existencias"] && $this->destino_cantidad > 0 && $existencia_por_almacen["existencias"]>0){
                                        $this->transacciones["transferencia"][$no_transferencia]["datos"] = [
                                            "tipo_transaccion"=>34,
                                            "fecha"=>$this->data["fecha_recepcion"],
                                            "id_obra"=>$this->obra->id_obra,
                                            "id_almacen"=>$existencia_por_almacen["id_almacen"],
                                            "referencia"=>"transferencia automática",
                                            "observaciones"=>"transferencia automática",
                                            "opciones"=>65537,
                                        ];
                                        $this->transacciones["transferencia"][$no_transferencia]["item"] = [
                                            "id_material"=>$item_a_procesar["id"],
                                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                            "cantidad"=>$this->destino_cantidad,
                                            "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                        ];
                                        $this->preparacion_transferencias["items"][$existencia_por_almacen["id_almacen"]][] = [
                                            "id_almacen_origen"=>$existencia_por_almacen["id_almacen"],
                                            "id_material"=>$item_a_procesar["id"],
                                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                            "cantidad"=>$this->destino_cantidad,
                                            "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                            "id_destino"=>$destinos["id"],
                                            "generada_en"=>3
                                        ];
                                        $no_transferencia++;
                                        $this->cantidad_procesar = $this->cantidad_procesar - $this->destino_cantidad;

                                        $this->existencias_por_almacen[$iex_al]["existencias"] = $this->existencias_por_almacen[$iex_al]["existencias"] - $this->destino_cantidad;
                                        $this->destino_cantidad = 0;
                                    }elseif($this->destino_cantidad > $existencia_por_almacen["existencias"] && $this->destino_cantidad > 0 && $existencia_por_almacen["existencias"]){
                                        $this->transacciones["transferencia"][$no_transferencia]["datos"] = [
                                            "tipo_transaccion"=>34,
                                            "fecha"=>$this->data["fecha_recepcion"],
                                            "id_obra"=>$this->obra->id_obra,
                                            "id_almacen"=>$existencia_por_almacen["id_almacen"],
                                            "referencia"=>"transferencia automática",
                                            "observaciones"=>"transferencia automática",
                                            "opciones"=>65537,
                                        ];
                                        $this->transacciones["transferencia"][$no_transferencia]["item"] = [
                                            "id_material"=>$item_a_procesar["id"],
                                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                            "cantidad"=>$existencia_por_almacen["existencias"],
                                            "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                        ];
                                        $this->preparacion_transferencias["items"][$existencia_por_almacen["id_almacen"]][] = [
                                            "id_almacen_origen"=>$existencia_por_almacen["id_almacen"],
                                            "id_material"=>$item_a_procesar["id"],
                                            "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                            "cantidad"=>$existencia_por_almacen["existencias"],
                                            "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                            "id_destino"=>$destinos["id"],
                                            "generada_en"=>4
                                        ];
                                        $no_transferencia++;
                                        $this->destino_cantidad = $this->destino_cantidad - $existencia_por_almacen["existencias"];
                                        $this->cantidad_procesar = $this->cantidad_procesar - $existencia_por_almacen["existencias"];
                                        $this->existencias_por_almacen[$iex_al]["existencias"] = 0;
                                    }
                                    $iex_al++;
                                }
                            }
                        }
                        $iides++;
                    }
                }else if($this->cantidad_procesar > $this->cantidad_disponible_entrada && !($this->cantidad_disponible_entrada > 0)){
                    
                    foreach($item_a_procesar["destinos"] as $destinos){
                        $this->destino_cantidad = $destinos["cantidad"];
                        $iex_al = 0;
                        foreach($this->existencias_por_almacen as $existencia_por_almacen){
                            if($this->destino_cantidad<= $existencia_por_almacen["existencias"] && $this->destino_cantidad > 0 && $existencia_por_almacen["existencias"]>0){
                                $this->transacciones["transferencia"][$no_transferencia]["datos"] = [
                                    "tipo_transaccion"=>34,
                                    "fecha"=>$this->data["fecha_recepcion"],
                                    "id_obra"=>$this->obra->id_obra,
                                    "id_almacen"=>$existencia_por_almacen["id_almacen"],
                                    "referencia"=>"transferencia automática",
                                    "observaciones"=>"transferencia automática",
                                    "opciones"=>65537,
                                ];
                                $this->transacciones["transferencia"][$no_transferencia]["item"] = [
                                    "id_material"=>$item_a_procesar["id"],
                                    "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                    "cantidad"=>$this->destino_cantidad,
                                    "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                ];
                                $this->preparacion_transferencias["items"][$existencia_por_almacen["id_almacen"]][] = [
                                    "id_almacen_origen"=>$existencia_por_almacen["id_almacen"],
                                    "id_material"=>$item_a_procesar["id"],
                                    "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                    "cantidad"=>$this->destino_cantidad,
                                    "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                    "id_destino"=>$destinos["id"],
                                    "generada_en"=>3
                                ];
                                $no_transferencia++;
                                $this->cantidad_procesar = $this->cantidad_procesar - $this->destino_cantidad;

                                $this->existencias_por_almacen[$iex_al]["existencias"] = $this->existencias_por_almacen[$iex_al]["existencias"] - $this->destino_cantidad;
                                $this->destino_cantidad = 0;
                            }elseif($this->destino_cantidad > $existencia_por_almacen["existencias"] && $this->destino_cantidad > 0 && $existencia_por_almacen["existencias"]){
                                $this->transacciones["transferencia"][$no_transferencia]["datos"] = [
                                    "tipo_transaccion"=>34,
                                    "fecha"=>$this->data["fecha_recepcion"],
                                    "id_obra"=>$this->obra->id_obra,
                                    "id_almacen"=>$existencia_por_almacen["id_almacen"],
                                    "referencia"=>"transferencia automática",
                                    "observaciones"=>"transferencia automática",
                                    "opciones"=>65537,
                                ];
                                $this->transacciones["transferencia"][$no_transferencia]["item"] = [
                                    "id_material"=>$item_a_procesar["id"],
                                    "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                    "cantidad"=>$existencia_por_almacen["existencias"],
                                    "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                ];
                                $this->preparacion_transferencias["items"][$existencia_por_almacen["id_almacen"]][] = [
                                    "id_almacen_origen"=>$existencia_por_almacen["id_almacen"],
                                    "id_material"=>$item_a_procesar["id"],
                                    "unidad"=>$this->getUnidadMaterial($item_a_procesar["id"]),
                                    "cantidad"=>$existencia_por_almacen["existencias"],
                                    "id_almacen"=>$this->getIdAlmacenSAODeArea($destinos["id"]),
                                    "id_destino"=>$destinos["id"],
                                    "generada_en"=>4
                                ];
                                $no_transferencia++;
                                $this->destino_cantidad = $this->destino_cantidad - $existencia_por_almacen["existencias"];
                                $this->cantidad_procesar = $this->cantidad_procesar - $existencia_por_almacen["existencias"];
                                $this->existencias_por_almacen[$iex_al]["existencias"] = 0;
                            }
                            $iex_al++;
                        }
                    }
                }
            }
        }
        if(array_key_exists("entrada", $this->transacciones)){
            $this->transacciones["entrada"]["datos"] = [
                "tipo_transaccion"=>33,
                "opciones"=>1,
                "id_antecedente"=>$this->data["orden_compra"],
                "id_empresa"=>$this->getEmpresaOC($this->data["orden_compra"]),
                "id_sucursal"=>$this->getSucursalOC($this->data["orden_compra"]),
                "id_moneda"=>$this->getMonedaOC($this->data["orden_compra"]),
                "fecha"=>$this->data["fecha_recepcion"],
                "id_obra"=>$this->obra->id_obra,
                "observaciones"=>$this->data["observaciones"],
                "referencia"=>$this->data["numero_remision_factura"],
            ]; 
        }
        
        //dd($this->preparacion_transferencias["items"], $this->transacciones);
        $i = 0;
        foreach($this->preparacion_transferencias["items"] as $k=>$v){
            $this->transacciones["transferencias"][$i] = [
                "tipo_transaccion"=>34,
                "fecha"=>$this->data["fecha_recepcion"],
                "id_obra"=>$this->obra->id_obra,
                "id_almacen"=>$k,
                "referencia"=>"transferencia automática",
                "observaciones"=>"transferencia automática",
                "opciones"=>65537,
                "items"=>$v
            ];
            
            $i++;
        }
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
            $id_almacen = $almacen->id_almacen;
        }
        return $id_almacen;
    }
    protected function getPartidasTransferencia($id_item_oc){
        
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
            ItemsComprados as c join items as i on(i.id_item = c.id_item)
        WHERE
            i.id_item = '.$item_procesar["id_item"].' 
        ');
        return $resultado[0]->cantidad_pendiente;
    }
    protected function getExistenciasAlmacen($item_procesar){
        $resultado = DB::connection("cadeco")->select('
            SELECT
                  sum(inventarios.saldo) as existencias
            FROM
                inventarios join items  on(items.id_material = inventarios.id_material)
                join almacenes on(almacenes.id_almacen = inventarios.id_almacen)
            WHERE
                almacenes.id_obra = '.$this->obra->id_obra.' AND
                items.id_item = '.$item_procesar["id_item"].' 
        ');
        return $resultado[0]->existencias;
    }
    
    protected function getExistenciasXAlmacen($item_procesar){
        $resultado = DB::connection("cadeco")->select('
            SELECT
                inventarios.id_almacen as id_almacen,
                sum(inventarios.saldo) as existencias
            FROM
                inventarios join items  on(items.id_material = inventarios.id_material)
                join almacenes on(almacenes.id_almacen = inventarios.id_almacen)
            WHERE
                almacenes.id_obra = '.$this->obra->id_obra.' AND
                items.id_item = '.$item_procesar["id_item"].' AND
                inventarios.saldo > 0
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
        $entrada_almacen = new EntradaAlmacen($datos_entrada);
        $entrada_almacen->obra()->associate($this->obra);
        $entrada_almacen->save();
        $partidas_entrada = $this->creaPartidasEntradaAlmacen($datos_entrada["partidas"]);
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
                    '.$item->id.' 
                    SELECT @RC as res');
            if($resultado["res"] != 0){
                throw new \Exception("Hubo un error al aplicar el procedimiento de entrada de almacén para el material:" . $item->material->descripcion);
            }
            
        }
    }
    
    protected function actualiza_entregas($objEntrada){
        foreach($objEntrada->items as $item){
            DB::table("entregas")
                ->where("id", $item->item_antecedente)
                ->increment("surtida", $item->cantidad);
        }
    }
    
    protected function creaTransferenciaAlmacen($datos_transferencia)
    {
        $transferencia_almacen = new TransferenciaAlmacen($datos_transferencia);
        $transferencia_almacen->save();
        $partidas_transferencia = $this->creaPartidasEntradaAlmacen($datos_transferencia["partidas"]);
        $transferencia_almacen->items()->saveMany($partidas_transferencia);
        $this->ejecuta_procedimiento_transferencia($transferencia_almacen);
        return $transferencia_almacen;
    }
    
    protected function creaPartidasTransferenciaAlmacen($datos_partidas_transferencia){
        $partidas = null;
        foreach($datos_partidas_transferencia as $datos_partida_transferencia){
            $partidas[] = new TransferenciaAlmacenItem($datos_partida_transferencia);
        }
        return $partidas;
    }
    
    protected function ejecuta_procedimiento_transferencia($objTransferencia){
        foreach($objTransferencia->items as $item){
            $resultado = DB::connection("cadeco")->select('
                DECLARE @RC int
                DECLARE @id_item int
                EXECUTE @RC = [dbo].[sp_salida_material] 
                    '.$item->id.' 
                SELECT @RC as res
            ');
            if($resultado["res"] != 0){
                throw new \Exception("Hubo un error al aplicar el procedimiento de transferencia de almacén para el material:" . $item->material->descripcion);
            }
        }
    }
    
    
}
