<?php

namespace Ghi\Equipamiento\ReporteCostos;

use Illuminate\Database\Eloquent\Model;

class AreaSecrets extends Model
{
    protected $connection = 'cadeco';
    protected $table = 'Equipamiento.reporte_b_areas_secrets';
    
    protected $casts = [
        'id' => 'int',
    ];

    public $timestamps = false;
}
