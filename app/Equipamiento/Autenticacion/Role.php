<?php

namespace  Ghi\Equipamiento\Autenticacion;

use \Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    
    public function permissions(){
        return $this->belongsToMany(Permission::class,'permission_role','role_id','permission_id')
            ;
    }
}
