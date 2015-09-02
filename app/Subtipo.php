<?php

namespace Ghi;

use Illuminate\Database\Eloquent\Model;

class Subtipo extends Model
{
    /**
     * @var string
     */
    protected $connection = 'equipamiento';

    /**
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Tipo de area al que pertenece este subtipo de area
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id', 'id');
    }
}
