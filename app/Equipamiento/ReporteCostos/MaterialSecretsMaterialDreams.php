<?php

namespace Ghi\Equipamiento\ReporteCostos;
use Illuminate\Database\Eloquent\Model;


class MaterialSecretsMaterialDreams extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.reporte_b_ms_md';

    protected $primaryKey = "id_material_dreams";

    public $timestamps = false;

    

}
