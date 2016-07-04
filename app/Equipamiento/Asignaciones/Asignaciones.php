<?php

namespace Ghi\Equipamiento\Asignaciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Transacciones\Item;
use Ghi\Equipamiento\Recepciones\Exceptions\RecepcionSinArticulosException;
use Ghi\Equipamiento\Inventarios\Inventario;
use Ghi\Equipamiento\Asignaciones\Asignacion;

class Asignaciones
{
    
    use AuxiliaresTransaccionesSAO;
    
    protected $id;

    protected $obra;
    protected $transacciones_relacionadas_recepcion = [];
    protected $transacciones_relacionadas_asignacion = [];
    /**
     * @param array $data
     * @param Obra  $obra
     */
    public function __construct($id, Obra $obra)
    {
        $this->id = $id;
        $this->obra = $obra;
    }

    /**
     * Genera una recepcion de articulos.
     *
     * @return Recepcion
     * @throws \Exception
     */
    public function cancelar()
    {
        try {
            DB::connection('cadeco')->beginTransaction();
            $asignacion = Asignacion::findOrFail($this->id);
            if($asignacion->recepcion)
                throw new \Exception("La asignación se generó desde el módulo de recepciones, debe eliminarse desde dicho módulo buscando la recepción: #" . $asignacion->recepcion->numero_folio );
            $this->eliminaRelacionTransaccionesAsignacion($asignacion);
            $this->actualizaInventarios($asignacion);
            $this->procesoSAO($asignacion);
            $asignacion->delete();
            DB::connection('cadeco')->commit();
        } catch (\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }
    }
        
    protected function eliminaRelacionTransaccionesAsignacion($asignacion){
        //dd($asignacion, $asignacion->transacciones);
        if($asignacion){
            $this->transacciones_relacionadas_asignacion = $asignacion->transacciones()->orderBy("id_transaccion","desc")->get();
            if(!(count($this->transacciones_relacionadas_asignacion)>0))
                throw new \Exception("La asignación debe ser eliminada desde el módulo de recepciones." . $objTransaccion->id_transaccion);
                
            DB::connection("cadeco")->table('Equipamiento.asignaciones_transacciones')
                ->where("id_asignacion","=", $asignacion->id)
                ->delete();
            foreach($asignacion->items as $item){
                DB::connection("cadeco")->table('Equipamiento.asignaciones_transacciones_items')
                ->where("id_item_asignacion","=", $item->id)
                ->delete();
            }
        }
    }
    
    protected function actualizaInventarios($recepcion){
        $asignacion = $recepcion->asignacion;
        //dd($asignacion);
        if(!$asignacion){
            foreach($recepcion->items as $item){
                $inventario = Inventario::where("id_material","=", $item->id_material)
                    ->where("id_area","=", $item->id_area_origen)->first()
                        ;
                $inventario->incrementaExistencia($item->cantidad_asignada);
            }
        }
    }

    protected function procesoSAO(){
        //dd($this->transacciones_relacionadas_asignacion);
        foreach ($this->transacciones_relacionadas_asignacion as $transaccion_asignacion){
            $this->elimina_transaccion($transaccion_asignacion);
        }
        
    }
}
