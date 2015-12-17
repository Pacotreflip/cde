<?php

namespace Ghi;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'igh.departamento';
    protected $primaryKey = 'iddepartamento';
    
    public function usuarios(){
        return $this->hasMany(Equipamiento\Autenticacion\User::class,"iddepartamento","iddepartamento");
    }
}
