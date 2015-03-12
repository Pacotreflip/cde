<?php namespace Ghi\Maquinaria\Domain\Conciliacion\QueryScopes;

trait IdObraTrait {

    /**
     * Agrega el scope de id_obra a un modelo
     */
    public static function bootIdObraTrait()
    {
        $context = \App::make('Ghi\Core\App\Contexts\TenantContext');

        static::addGlobalScope(new IdObraScope($context->getTenantId()));
    }
}