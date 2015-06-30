<?php namespace Ghi\Maquinaria\Domain\Conciliacion\QueryScopes;

trait OpcionesOrdenRentaTrait {

    /**
     * Agrega el scope de opciones a un modelo
     * @return void
     */
    public static function bootOpcionesOrdenRentaTrait()
    {
        static::addGlobalScope(new OpcionesScope(8));
    }
}