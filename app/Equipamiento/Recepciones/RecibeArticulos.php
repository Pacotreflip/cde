<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Recepciones\Exceptions\RecepcionSinArticulosException;

class RecibeArticulos
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var Obra
     */
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
                $area = Area::findOrFail($this->data['area_almacenamiento']);

                $recepcion->recibeMaterial($material, $area, $item['cantidad_recibir']);
            }

            if ($recepcion->items->count() === 0) {
                throw new RecepcionSinArticulosException;
            }

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
        $recepcion->creado_por = \Auth::user()->usuario;
        $recepcion->save();

        return $recepcion;
    }
}
