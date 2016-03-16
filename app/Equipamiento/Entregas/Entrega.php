<?php

namespace Ghi\Equipamiento\Entregas;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;
use Ghi\Core\Models\Obra;
use Ghi\Core\Models\User;

class Entrega extends Model
{
    use TransaccionTrait;
    /**
     * @var string
     */
    protected $table = 'Equipamiento.entregas';

    /**
     * @var array
     */
    protected $fillable = ['fecha_entrega', 'id_obra', 'id_usuario', 'observaciones'];
    protected $dates = ['fecha_entrega'];
    
    /**
     *
     * @var string 
     */
    protected $connection = 'cadeco';
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->asignaFolio();
        });
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    
    public function partidas(){
        return $this->hasMany(EntregaPartida::class, "id_entrega");
    }
    
    /**
     * Obra relacionada con esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra(){
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }
}
