<?php

namespace Ghi\Equipamiento\ReporteCostos;

use Illuminate\Database\Eloquent\Model;

class DatosSecretsConDreams extends Model
{
    protected $table = "Equipamiento.reporte_b_datos_secrets";
    protected $connection = "cadeco";
    protected $primaryKey = null;
    
    public $incrementing = false;
    public $timestamps = false;
}
