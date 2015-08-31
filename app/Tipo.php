<?php

namespace Ghi;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
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
     * Subtipos de area relacionados con este tipo de area
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subtipos()
    {
        return $this->hasMany(Subtipo::class);
    }
}
