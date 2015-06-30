<?php namespace Ghi\Maquinaria\Domain\Conciliacion\QueryScopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class OpcionesScope implements ScopeInterface {

    /**
     * @var
     */
    protected $opciones;

    /**
     * @param $opciones
     */
    function __construct($opciones)
    {
        $this->opciones = $opciones;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        $builder->whereOpciones($this->opciones);
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