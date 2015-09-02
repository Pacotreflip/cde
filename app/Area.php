<?php

namespace Ghi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\Node;

class Area extends Node
{
    /**
     * @var string
     */
    protected $connection = 'equipamiento';

    /**
     * Campos afectables por asignacion masiva
     *
     * @var array
     */
    protected $fillable = ['nombre', 'clave', 'descripcion'];

    /**
     * Subtipo de area
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subtipo()
    {
        return $this->belongsTo(Subtipo::class, 'subtipo_id');
    }
}
