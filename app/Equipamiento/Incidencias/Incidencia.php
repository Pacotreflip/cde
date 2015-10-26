<?php

namespace Ghi\Equipamiento\Incidencias;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;

class Incidencia extends Model
{
    use TransaccionTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.incidencias';

    /**
     * @var array
     */
    protected $fillable = ['fecha_incidencia', 'motivo', 'descripcion', 'anotaciones'];

    /**
     * Obra relacionada con esta incidencia.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }
}
