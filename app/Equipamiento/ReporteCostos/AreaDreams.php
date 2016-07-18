<?php

namespace Ghi\Equipamiento\ReporteCostos;
use Illuminate\Database\Eloquent\Model;


class AreaDreams extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.reporte_b_areas_dreams';



    protected $casts = [
        'id' => 'int',
    ];

    public $timestamps = false;

    
    
}
