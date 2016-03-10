<?php

namespace Ghi\Equipamiento\Cierres;
use Illuminate\Database\Eloquent\Model;

class CierreCancelado extends Model
{
    /**
     * @var string
     */
    protected $table = 'Equipamiento.cierres_cancelados';

    /**
     * @var array
     */
    protected $fillable = ['id_area', 'id_usuario'];
    
    /**
     *
     * @var string 
     */
    protected $connection = 'cadeco';
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area(){
        return $this->belongsTo(Area::class, "id_area");
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario(){
        return $this->belongsTo(User::class, "id_usuario", "idusuario");
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario_cancelo(){
        return $this->belongsTo(User::class, "id_usuario_cancelo", "idusuario");
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    
    public function partidas(){
        return $this->hasMany(CierreCanceladoPartida::class, "id_cierre");
    }
}
