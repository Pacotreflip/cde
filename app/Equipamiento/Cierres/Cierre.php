<?php

namespace Ghi\Equipamiento\Cierres;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;
use Ghi\Core\Models\Obra;
use Ghi\Core\Models\User;
use Ghi\Equipamiento\Comprobantes\Comprobante;

class Cierre extends Model
{
    use TransaccionTrait;
     /**
     * @var string
     */
    protected $table = 'Equipamiento.cierres';

    /**
     * @var array
     */
    protected $fillable = ['id_area', 'id_usuario'];
    protected $dates = ['fecha_cierre'];
    
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    
    public function partidas(){
        return $this->hasMany(CierrePartida::class, "id_cierre");
    }
    
    /**
     * Obra relacionada con esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra(){
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }
    public function comprobantes() {
        return $this->hasMany(Comprobante::class, 'id_cierre', 'id');
    } 
    public function agregaComprobante(Comprobante $comprobante) {
        return $this->comprobantes()->save($comprobante);
    }
}
