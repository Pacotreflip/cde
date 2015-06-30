<?php

namespace Ghi\Domain\Conciliacion;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'empresas';

    /**
     * @var string
     */
    protected $primaryKey = 'id_empresa';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Entradas del equipo que el proveedor arrenda a la empresa
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entradas()
    {
        return $this->hasMany(EntradaEquipo::class, 'id_empresa', 'id_empresa');
    }
}
