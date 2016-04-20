<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Transacciones\Item;
use Ghi\Equipamiento\Asignaciones\Asignacion;
use Ghi\Equipamiento\Areas\MaterialRequeridoArea;
use Ghi\Equipamiento\Recepciones\Exceptions\RecepcionSinArticulosException;

class RecibeArticulosAsignacion
{
    use AuxiliaresTransaccionesSAO;
    protected $data;

    protected $obra;
    

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
            //dd($recepcion->id);
            // crear asignación
            $asignacion = $this->creaAsignacion($recepcion->id);
            
            foreach ($this->data['materiales'] as $item) {
                $material = Material::where('id_material', $item['id'])->first();

                foreach ($item['destinos'] as $destino) {
                    $area_origen = Area::findOrFail($destino['id']);
                    $area_destino = Area::findOrFail($destino['id']);
                    $material_requerido = MaterialRequeridoArea::whereRaw('id_material = '. $item['id'].' and id_area = '. $area_destino->id)->first();
                    if(!$material_requerido){
                        //trigger_error("No es posible asignar el artículo al área por que no esta requerido.");
                        throw new \Exception("No es posible asignar el artículo: {$item['descripcion']} al área: ".$area_destino->ruta()." por que no esta requerido.");
                    }
                    $cantidad_requerida = $material_requerido->cantidad_requerida;
                    $cantidad_asignada = $area_destino->cantidad_asignada($item['id']);
                    $cantidad_a_asignar = $destino['cantidad'];
                    $cantidad_total_asignada = $cantidad_asignada + $cantidad_a_asignar;
                    $pendiente = $cantidad_requerida-$cantidad_asignada;
                    //dd($item['id'], $cantidad_requerida, $cantidad_asignada, $cantidad_total_asignada, $cantidad_a_asignar);
                    if (!($cantidad_requerida>= $cantidad_total_asignada)) {
                        //trigger_error("No es posible asignar el artículo: {$item['descripcion']} al área: ".$area_destino->ruta().", la cantidad pendiente de recibir es: $pendiente");
                        //" ." , la cantidad pendiente es {$cantidad_requerida-$cantidad_asignada}"
                        //throw new \Exception("No es posible asignar la cantidad indicada para el articulo {$item['descripcion']}");
                         throw new \Exception("No es posible asignar el artículo: {$item['descripcion']} al área: ".$area_destino->ruta().", la cantidad pendiente de recibir es: $pendiente");

                    }

                    $asignacion->agregaMaterial($material, $destino['cantidad'], $area_origen, $area_destino);
                }
            }

            if ($asignacion->items->count() === 0) {
                throw new RecepcionSinArticulosException;
            }

            $asignacion->save();
            
            
            foreach($proceso_sao as $transaccion){
                DB::connection("cadeco")->table('Equipamiento.recepciones_transacciones')->insert(
                    ['id_recepcion' => $recepcion->id, 'id_transaccion' => $transaccion->id_transaccion]
                );
            }
            foreach($recepcion->items as $item){
                foreach($this->items_ids[$item->id_material] as $k=>$v){
                    DB::connection("cadeco")->table('Equipamiento.recepciones_transacciones_items')->insert(
                        ['id_item_recepcion' => $item->id, 'id_item_transaccion' => $v]
                    );
                }
            }
            
            
            DB::connection('cadeco')->commit();
        } catch (\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }

        return $asignacion;
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
    /**
     * Crea una nueva asignacion.
     * 
     * @return Asignacion
     */
    protected function creaAsignacion($id_recepcion)
    {
        $asignacion = new Asignacion($this->data);
        $asignacion->obra()->associate($this->obra);
        $asignacion->id_recepcion = $id_recepcion;
        $asignacion->creado_por = Auth::user()->usuario;
        $asignacion->id_usuario = Auth::user()->idusuario;
        $asignacion->fecha_asignacion = $this->data["fecha_recepcion"];
        $asignacion->save();

        return $asignacion;
    }
    
    protected function procesoSAO(){
        $this->preparaDatosTransacciones();
        if(array_key_exists("entrada", $this->transacciones)){
            foreach($this->transacciones["entrada"] as $datos_entrada){
                $transacciones [] = $this->creaEntradaAlmacen($datos_entrada);
            }
        }
        if(array_key_exists("transferencias", $this->transacciones)){
            foreach($this->transacciones["transferencias"] as $datos_transferencia){
                $transacciones [] = $this->creaTransferenciaAlmacen($datos_transferencia);
            }
        }
        
        if(array_key_exists("salidas", $this->transacciones)){
            foreach($this->transacciones["salidas"] as $datos_transferencia){
                $transacciones [] = $this->creaSalidaAlmacen($datos_transferencia);
            }
        }
        //dd($transacciones);
        foreach ($transacciones as $transaccion){
            foreach($transaccion->items as $item){
                $this->items_ids[$item->id_material][] = $item->id_item;
            }
        }
        $this->orden_compra_cumplida();
        return $transacciones;
    }
}
