<?php

namespace Ghi\Equipamiento\ReporteCostos;

use Illuminate\Database\Eloquent\Model;

class AreaReporte extends Model
{
    protected $connection = 'cadeco';
    protected $table = 'Equipamiento.reporte_b_areas';
    
    protected $casts = [
        'id' => 'int',
    ];

    public $timestamps = false;
}
