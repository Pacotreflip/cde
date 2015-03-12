<?php namespace Ghi\Maquinaria\Domain\Conciliacion\QueryScopes;

trait TipoTransaccionTrait {

    /**
     * Agrega el scope de tipo de transaccion a un modelo
     *
     * @return void
     */
    public static function bootTipoTransaccionTrait()
    {
        static::addGlobalScope(new TipoTransaccionScope(static::TIPO_TRANSACCION));
    }

}