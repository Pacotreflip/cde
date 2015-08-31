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
}
