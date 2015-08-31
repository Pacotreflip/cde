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
}
