<?php

namespace Ghi;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'igh.empresa';
    protected $primaryKey = 'idempresa';
    
    public function usuarios(){
        return $this->hasMany(Equipamiento\Autenticacion\User::class,"idempresa","idempresa");
    }
}
