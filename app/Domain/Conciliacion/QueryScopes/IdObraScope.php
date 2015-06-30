<?php namespace Ghi\Maquinaria\Domain\Conciliacion\QueryScopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class IdObraScope implements ScopeInterface {

    /**
     * @var
     */
    protected $idObra;

    /**
     * @param $idObra
     */
    function __construct($idObra)
    {
        $this->idObra = $idObra;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        $builder->whereIdObra($this->idObra);
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