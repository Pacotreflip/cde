<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Database\Eloquent\Model;

class AreaAcumuladores extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.areas_acumuladores';
    
    public $timestamps = false;
    
    public function area(){
        return $this->belongsTo(Area::class, "id_area");
    }
    
    public function progressbar_estado_asignacion_class()
    {
        $clase = "";
        if(round($this->porcentaje_asignacion*100) == 100){
            $clase = "progress-bar-success";
        }else if(round($this->porcentaje_asignacion*100) < 100 && round($this->porcentaje_asignacion*100)>=70){
            $clase = "";
        }else if(round($this->porcentaje_asignacion*100) < 70 && round($this->porcentaje_asignacion*100)>=50){
            $clase = "progress-bar-warning";
        }else{
            $clase = "progress-bar-danger";
        }
        return $clase;
    }
    public function getProgressBarEstadoAsignacionClassAttribute()
    {
        return $this->progressbar_estado_asignacion_class();
    }
    public function progressbar_estado_validacion_class()
    {
        $clase = "";
        if(round($this->porcentaje_validacion*100) == 100){
            $clase = "progress-bar-success";
        }else if(round($this->porcentaje_validacion*100) < 100 && round($this->porcentaje_validacion*100)>=70){
            $clase = "";
        }else if(round($this->porcentaje_validacion*100) < 70 && round($this->porcentaje_validacion*100)>=50){
            $clase = "progress-bar-warning";
        }else{
            $clase = "progress-bar-danger";
        }
        return $clase;
    }
    public function getProgressBarEstadoValidacionClassAttribute()
    {
        return $this->progressbar_estado_validacion_class();
    }
    
    public function progressbar_estado_cierre_class()
    {
        $clase = "";
        if(round($this->porcentaje_cierre*100) == 100){
            $clase = "progress-bar-success";
        }else if(round($this->porcentaje_cierre*100) < 100 && round($this->porcentaje_cierre*100)>=70){
            $clase = "";
        }else if(round($this->porcentaje_cierre*100) < 70 && round($this->porcentaje_cierre*100)>=50){
            $clase = "progress-bar-warning";
        }else{
            $clase = "progress-bar-danger";
        }
        return $clase;
    }
    public function getProgressBarEstadoCierreClassAttribute()
    {
        return $this->progressbar_estado_cierre_class();
    }
    
    public function progressbar_estado_entrega_class()
    {
        $clase = "";
        if(round($this->porcentaje_entrega*100) == 100){
            $clase = "progress-bar-success";
        }else if(round($this->porcentaje_entrega*100) < 100 && round($this->porcentaje_entrega*100)>=70){
            $clase = "";
        }else if(round($this->porcentaje_entrega*100) < 70 && round($this->porcentaje_entrega*100)>=50){
            $clase = "progress-bar-warning";
        }else{
            $clase = "progress-bar-danger";
        }
        return $clase;
    }
    public function getProgressBarEstadoEntregaClassAttribute()
    {
        return $this->progressbar_estado_entrega_class();
    }
}
