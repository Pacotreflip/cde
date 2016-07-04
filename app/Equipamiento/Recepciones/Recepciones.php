<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Transacciones\Item;
use Ghi\Equipamiento\Recepciones\Exceptions\RecepcionSinArticulosException;

class Recepciones
{
    
    use AuxiliaresTransaccionesSAO;
    
    protected $id;

    protected $obra;
    protected $transacciones_relacionadas;
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
            $recepcion = Recepcion::findOrFail($this->id);
            $this->eliminaRelacionTransacciones($recepcion);
            $this->procesoSAO($recepcion);
            $recepcion->delete();
            DB::connection('cadeco')->commit();
        } catch (\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }
    }

    protected function eliminaRelacionTransacciones($recepcion){
        $this->transacciones_relacionadas = $recepcion->transacciones;
        DB::connection("cadeco")->table('Equipamiento.recepciones_transacciones')
            ->where("id_recepcion","=", $recepcion->id)
            ->delete();
        foreach($recepcion->items as $item){
            DB::connection("cadeco")->table('Equipamiento.recepciones_transacciones_items')
            ->where("id_item_recepcion","=", $item->id)
            ->delete();
        }
    }


    protected function procesoSAO($recepcion){
        $transacciones = $this->transacciones_relacionadas;
        foreach ($transacciones as $transaccion){
            $this->elimina_transaccion($transaccion);
        }
        $this->orden_compra_no_cumplida($recepcion);
    }
}
