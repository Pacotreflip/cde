<?php

namespace Ghi;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'igh.ubicacion';
    protected $primaryKey = 'idubicacion';
    
    public function usuarios(){
        return $this->hasMany(Equipamiento\Autenticacion\User::class,"idubicacion","idubicacion");
    }
}
