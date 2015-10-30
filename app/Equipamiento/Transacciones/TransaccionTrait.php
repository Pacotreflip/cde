<?php

namespace Ghi\Equipamiento\Transacciones;

trait TransaccionTrait
{
    /**
     * Obtiene el siguiente folio disponible para esta transaccion.
     *
     * @return integer
     */
    protected function asignaFolio()
    {
        return $this->numero_folio = static::where('id_obra', $this->id_obra)
                ->max('numero_folio') + 1;
    }
}
