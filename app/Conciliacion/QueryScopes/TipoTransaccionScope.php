<?php namespace Ghi\Maquinaria\Domain\Conciliacion\QueryScopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class TipoTransaccionScope implements ScopeInterface {

    /**
     * @var
     */
    protected $tipoTransaccion;

    /**
     * @param $tipoTransaccion
     */
    function __construct($tipoTransaccion)
    {
        $this->tipoTransaccion = $tipoTransaccion;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        $builder->whereTipoTransaccion($this->tipoTransaccion);
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function remove(Builder $builder)
    {
        // TODO: Implement remove() method.
    }
}