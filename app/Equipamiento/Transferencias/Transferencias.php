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
use Illuminate\Support\Facades\Auth;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Inventarios\Inventario;
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
    protected $transacciones_relacionadas_transferencia = [];
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
            if($origen->cerrada){
                throw new \Exception("El área origen se encuentra cerrada, no puede hacer movimientos de sus inventarios.");
            }

            $entrega = auth()->user()->present()->nombreCompleto;
            $recibe = $this->data['recibe'] == "" ? $entrega : $this->data['recibe'];
            
            $transferencia = Transferencia::crear(
                $this->obra,
                $this->data['fecha_transferencia'],
                $this->data['observaciones'],
                auth()->user()->usuario,
                $entrega,
                $recibe
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
    
    
    public function cancelar()
    {
        try {
            DB::connection('cadeco')->beginTransaction();
            $transferencia = Transferencia::findOrFail($this->data["id"]);
            
            $this->eliminaRelacionTransaccionesTransferencia($transferencia);
            $this->actualizaInventarios($transferencia);
            $this->procesoCancelacionSAO($transferencia);
            $this->registraCancelacion($transferencia);
            $transferencia->delete();
            DB::connection('cadeco')->commit();
        } catch (\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }
    }
    protected function eliminaRelacionTransaccionesTransferencia($transferencia){
        //dd($asignacion, $asignacion->transacciones);
        if($transferencia){
            $this->transacciones_relacionadas_transferencia = $transferencia->transacciones()->orderBy("id_transaccion","desc")->get();
                
            DB::connection("cadeco")->table('Equipamiento.transferencias_transacciones')
                ->where("id_transferencia","=", $transferencia->id)
                ->delete();
            foreach($transferencia->items as $item){
                DB::connection("cadeco")->table('Equipamiento.transferencias_transacciones_items')
                ->where("id_item_transferencia","=", $item->id)
                ->delete();
            }
        }
    }
    
    protected function actualizaInventarios($transferencia){
       
        if($transferencia){
            foreach($transferencia->items as $item){
                $inventario_origen = Inventario::where("id_material","=", $item->id_material)
                    ->where("id_area","=", $item->id_area_destino)->first();
                //dd($inventario_origen);
                $inventario_destino = Inventario::where("id_material","=", $item->id_material)
                    ->where("id_area","=", $item->id_area_origen)->first();
                $inventario_origen->transferirA($inventario_destino, $item->cantidad_transferida);
            }
        }
    }
    protected function procesoCancelacionSAO(){
        //dd($this->transacciones_relacionadas_asignacion);
        foreach ($this->transacciones_relacionadas_transferencia as $transaccion_transferencia){
            $this->elimina_transaccion($transaccion_transferencia);
        }
        
    }
    protected function registraCancelacion($transferencia){
        $carbon = new \Carbon\Carbon();
        DB::connection("cadeco")->table('Equipamiento.cancelaciones')->insert(
            [
                'id_obra'=>$this->obra->id_obra,
                'motivo'=>$this->data["motivo"],
                'created_at'=>$carbon->now(),
                'updated_at'=>$carbon->now(),
                'numero_folio_transferencia' => $transferencia->numero_folio, 
                'id_usuario' => Auth::id()]
        );
    }
}
