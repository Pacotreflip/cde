<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Recepciones\Exceptions\RecepcionSinArticulosException;

class RecibeArticulos
{
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
     */
    public function save()
    {
        try {
            DB::connection('cadeco')->beginTransaction();
            
            $recepcion = $this->creaRecepcion();

            foreach ($this->data['materiales'] as $item) {
                $material = Material::where('id_material', $item['id'])->first();

                $recepcion->recibeMaterial($material, $item['cantidad_recibir']);
            }

            if ($recepcion->items->count() == 0) {
                throw new RecepcionSinArticulosException;
            }

            $recepcion->numero_folio = $this->siguienteFolio();
            $recepcion->save();
            
            DB::connection('cadeco')->commit();
        } catch (Exception $e) {
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
        $recepcion->id_area_almacenamiento = $this->data['area_almacenamiento'];
        $recepcion->numero_folio = 0;
        $recepcion->save();

        return $recepcion;
    }

    /**
     * Obtiene el siguiente folio de recepción.
     * 
     * @return int
     */
    protected function siguienteFolio()
    {
        return Recepcion::where('id_obra', $this->obra->id_obra)
            ->max('numero_folio') + 1;
    }
}
