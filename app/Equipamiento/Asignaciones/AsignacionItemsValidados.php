<?php

namespace Ghi\Equipamiento\Asignaciones;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Asignaciones\ItemAsignacion;
use Ghi\Equipamiento\Autenticacion\User;

class AsignacionItemsValidados extends Model
{
    /**
     * @var string
     */
    protected $table = 'Equipamiento.asignacion_item_validacion';

    /**
     * @var array
     */
    protected $fillable = ['id_item_validacion', 'id_usuario'];
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function item_asignacion(){
        return $this->belongsTo(ItemAsignacion::class, "id_item_validacion");
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario_valido(){
        return $this->belongsTo(User::class, "id_usuario", "idusuario");
    }
}
