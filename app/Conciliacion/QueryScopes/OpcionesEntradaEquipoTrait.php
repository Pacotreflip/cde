<?php namespace Ghi\Maquinaria\Domain\Conciliacion\QueryScopes;

trait OpcionesEntradaEquipoTrait {

    /**
     * Agrega el scope de opciones a un modelo
     * @return void
     */
    public static function bootOpcionesEntradaEquipoTrait()
    {
        static::addGlobalScope(new OpcionesScope(8));
    }
}