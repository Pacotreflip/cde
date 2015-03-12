<?php  namespace Ghi\Core\Infraestructure\QueryScopes;

trait AlmacenTipoMaquinariaTrait {

    /**
     * Agrega el scope de tipo de almacen a un modelo
     *
     * @return void
     */
    public static function bootAlmacenTipoMaquinariaTrait()
    {
        static::addGlobalScope(new TipoAlmacenScope(2));
    }
}