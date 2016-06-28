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
    protected $fillable = ['fecha_entrega', 'id_obra', 'id_usuario', 'observaciones', 'concepto'];
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
    public function ids_areas(){
        $partidas = $this->partidas;
        $ids_area = [];
        foreach ($partidas as $partida){
            $ids_area[] = $partida->cierre_partida->area->id;
            
        }
        $ids_area_un = array_unique($ids_area);
        return $ids_area_un;
    }
    public function partida_articulos(){
        $salida = [];
        $partidas = $this->partidas;
        $id_areas = $this->ids_areas();
        $i = 0;
        foreach ($partidas as $partida){
            foreach($partida->cierre_partida->area->materialesAsignados as $articulo_asignado){
                $articulos[$i] = $articulo_asignado->material;
                $i++;
            }
            foreach($partida->cierre_partida->area->materiales_almacenados as $articulo_almacenado){
                $articulos[$i] = $articulo_almacenado->material;
                $i++;
            }
        }
        $articulos_unique = array_unique($articulos);
        $ia = 0;
        foreach($articulos_unique as $articulo){
            $salida[$ia]["i"] = $ia+1;
            $salida[$ia]["familia"] = $articulo->familia()["descripcion"];
            $salida[$ia]["descripcion"] = $articulo->descripcion;
            $salida[$ia]["unidad"] = $articulo->unidad;
            $salida[$ia]["cantidad_cierre"] = $articulo->cantidad_cierre($id_areas);
            $salida[$ia]["ubicacion"] = $articulo->ubicacion_para_entrega($id_areas);
            $ia++;
        }
        $salida_col = new \Illuminate\Support\Collection($salida);
        return $salida;
    }
}
