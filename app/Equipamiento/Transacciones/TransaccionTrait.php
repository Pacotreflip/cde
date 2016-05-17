<?php

namespace Ghi\Equipamiento\Transacciones;
use Illuminate\Support\Facades\Auth;
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
    
    protected function asignaFolioTransaccion()
    {
        return $this->numero_folio = static::where('id_obra', $this->id_obra)
                ->where('tipo_transaccion', $this->tipo_transaccion)
                ->where('opciones', $this->opciones)
                ->max('numero_folio') + 1;
    }
    
    protected function asignaFolioAlternativo()
    {
        return $this->NumeroFolioAlt = static::where('id_obra', $this->id_obra)
                ->where('tipo_transaccion', $this->tipo_transaccion)
                ->max('numero_folio') + 1;
    }
    
    protected function asignaComentario(){
        return $this->comentario = "'" . "I;".date("d/m/Y") .' '. date("h:s") . ";EQP|" . Auth::user()->usuario . "|" . "'";
    }
}
