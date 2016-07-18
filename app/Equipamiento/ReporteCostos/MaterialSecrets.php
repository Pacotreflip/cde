<?php

namespace Ghi\Equipamiento\ReporteCostos;
use Illuminate\Database\Eloquent\Model;


class MaterialSecrets extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.reporte_b_materiales_secrets';



    protected $casts = [
        'id' => 'int',
    ];

    public $timestamps = false;

    

}
