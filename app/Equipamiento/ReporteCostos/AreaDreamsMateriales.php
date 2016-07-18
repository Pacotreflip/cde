<?php

namespace Ghi\Equipamiento\ReporteCostos;
use Illuminate\Database\Eloquent\Model;


class AreaDreamsMateriales extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.reporte_b_areas_dreams_materiales';

    protected $primaryKey = "id_material";
    public $timestamps = false;

    
    
}
