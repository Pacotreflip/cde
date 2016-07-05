<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Transacciones\Item;
use Ghi\Equipamiento\Recepciones\Exceptions\RecepcionSinArticulosException;
use Ghi\Equipamiento\Inventarios\Inventario;

class Recepciones
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
    public function __construct($datos, Obra $obra)
    {
        $this->id = $datos["id"];
        $this->datos = $datos;
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
            $recepcion = Recepcion::findOrFail($this->id);
            $asignacion = $recepcion->asignacion;
            $this->eliminaRelacionTransaccionesAsignacion($asignacion);
            $this->eliminaRelacionTransacciones($recepcion);
            $this->actualizaInventarios($recepcion);
            $this->eliminaAsignacion($recepcion);
            $this->procesoSAO($recepcion);
            $this->registraCancelacion($recepcion);
            $recepcion->delete();
            DB::connection('cadeco')->commit();
        } catch (\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }
    }

    protected function eliminaRelacionTransacciones($recepcion){
        $this->transacciones_relacionadas_recepcion = $recepcion->transacciones()->orderBy("id_transaccion","desc")->get();
        //dd($this->transacciones_relacionadas_recepcion);
        DB::connection("cadeco")->table('Equipamiento.recepciones_transacciones')
            ->where("id_recepcion","=", $recepcion->id)
            ->delete();
        foreach($recepcion->items as $item){
            DB::connection("cadeco")->table('Equipamiento.recepciones_transacciones_items')
            ->where("id_item_recepcion","=", $item->id)
            ->delete();
        }
    }
    protected function registraCancelacion($recepcion){
        $carbon = new \Carbon\Carbon();
        DB::connection("cadeco")->table('Equipamiento.cancelaciones')->insert(
            [
                'id_obra'=>$this->obra->id_obra,
                'motivo'=>$this->datos["motivo"],
                'created_at'=>$carbon->now(),
                'updated_at'=>$carbon->now(),
                'numero_folio_recepcion' => $recepcion->numero_folio, 
                'id_usuario' => Auth::id()]
        );
    }


    protected function eliminaRelacionTransaccionesAsignacion($asignacion){
        //dd($asignacion, $asignacion->transacciones);
        if($asignacion){
            $this->transacciones_relacionadas_asignacion = $asignacion->transacciones()->orderBy("id_transaccion","desc")->get();

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

    protected function eliminaAsignacion($recepcion){
        $asignacion = $recepcion->asignacion;
        if($asignacion){
            $asignacion->delete();
        }
    }
    protected function actualizaInventarios($recepcion){
        $asignacion = $recepcion->asignacion;
        //dd($asignacion);
        if(!$asignacion){
            foreach($recepcion->items as $item){
                $inventario = Inventario::where("id_material","=", $item->id_material)
                    ->where("id_area","=", $item->id_area_almacenamiento)->first()
                        ;
                $inventario->decrementaExistencia($item->cantidad_recibida);
            }
        }
    }

    protected function procesoSAO($recepcion){
        //dd($this->transacciones_relacionadas_asignacion);
        foreach ($this->transacciones_relacionadas_asignacion as $transaccion_asignacion){
            $this->elimina_transaccion($transaccion_asignacion);
        }
        $transacciones = $this->transacciones_relacionadas_recepcion;
        foreach ($transacciones as $transaccion){
            $this->elimina_transaccion($transaccion);
        }
        $this->orden_compra_no_cumplida($recepcion);
        
    }
}
