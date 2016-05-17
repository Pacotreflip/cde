<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ghi\Equipamiento\Transferencias;
use Ghi\Core\Models\Obra;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Articulos\Material;
/**
 * Description of Transferencias
 *
 * @author EMartinez
 */
class Transferencias {
    use AuxiliaresTransaccionesSAO;
    
    /**
     * @param array $data
     * @param Obra  $obra
     */
    protected $data;

    protected $obra;
    
    public function __construct(array $data, Obra $obra)
    {
        $this->data = $data;
        $this->obra = $obra;
    }
    
    public function save(){
        DB::connection('cadeco')->beginTransaction();
        $proceso_sao = $this->procesoSAO();
        try {
            $origen = Area::findOrFail($this->data['area_origen']);

            $transferencia = Transferencia::crear(
                $this->obra,
                $this->data['fecha_transferencia'],
                $this->data['observaciones'],
                auth()->user()->usuario
            );
            
            foreach ($this->data['materiales'] as $item) {
                $material = Material::where('id_material', $item['id'])->firstOrFail();
                $destino = Area::findOrFail($item['area_destino']);
                $transferencia->transfiereMaterial($material, $origen, $destino, $item['cantidad']);
            }
            
            foreach($proceso_sao as $transaccion){
                DB::connection("cadeco")->table('Equipamiento.transferencias_transacciones')->insert(
                    ['id_transferencia' => $transferencia->id, 'id_transaccion' => $transaccion->id_transaccion]
                );
            }
            foreach($transferencia->items as $item){
                foreach($this->items_ids[$item->id_material] as $k=>$v){
                    DB::connection("cadeco")->table('Equipamiento.transferencias_transacciones_items')->insert(
                        ['id_item_transferencia' => $item->id, 'id_item_transaccion' => $v]
                    );
                }
            }

            DB::connection('cadeco')->commit();
        } catch(\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }
    }
    
    protected function procesoSAO(){
        $this->preparaDatosTransacciones();
        
        if(array_key_exists("transferencias", $this->transacciones)){
            foreach($this->transacciones["transferencias"] as $datos_transferencia){
                $transacciones [] = $this->creaTransferenciaAlmacen($datos_transferencia);
            }
        }
        foreach ($transacciones as $transaccion){
            foreach($transaccion->items as $item){
                $this->items_ids[$item->id_material][] = $item->id_item;
            }
        }
        //$this->orden_compra_cumplida();
        return $transacciones;
    }
}
