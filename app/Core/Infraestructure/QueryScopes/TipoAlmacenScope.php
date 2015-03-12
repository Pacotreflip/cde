<?php  namespace Ghi\Core\Infraestructure\QueryScopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class TipoAlmacenScope implements ScopeInterface {

    /**
     * @var
     */
    protected $tipoAlmacen;

    /**
     * @param $tipoAlmacen
     */
    function __construct($tipoAlmacen)
    {
        $this->tipoAlmacen = $tipoAlmacen;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        $builder->whereTipoAlmacen($this->tipoAlmacen);
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