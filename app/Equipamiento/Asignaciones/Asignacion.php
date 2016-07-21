<?php

namespace Ghi\Equipamiento\Asignaciones;

use Ghi\Core\Models\Obra;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Autenticacion\User;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Areas\MaterialRequeridoArea;
use Ghi\Equipamiento\Comprobantes\Comprobante;

class Asignacion extends Model
{
    use TransaccionTrait;
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.asignaciones';

    protected $dates = ['fecha_asignacion'];

    /**
     * @var array
     */
    protected $fillable = [
        'fecha_asignacion',
        'observaciones',
    ];
    
    /**
     * [boot description].
     * 
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->asignaFolio();
        });
    }
    
    /**
     * Obra relacionada con esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    
    /**
     * Recepción asociada con esta asignación.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class,'id_recepcion', 'id');
    }

    public function items()
    {
        return $this->hasMany(ItemAsignacion::class, 'id_asignacion');
    }
    
     /**
     * Asigna un material en este folio de asignación.
     *
     * @param Material $material
     * @param float $cantidad
     * @param $id_item
     * @param Area $area_origen
     * @param Area $area_destino
     */
    public function agregaMaterial(Material $material, $cantidad, $area_origen = null, $area_destino = null)
    {
        if ($cantidad <= 0) {
            return;
        }

        $item = new ItemAsignacion();
        $item->id_material = $material->id_material;
        $item->cantidad_asignada= $cantidad;
        $item->unidad = $material->unidad_compra;

        if ($area_origen) {
            $item->id_area_origen = $area_origen->id;
            $inventario_origen = $material->getInventarioDeArea($area_origen);
            $inventario_origen->decrementaExistencia($cantidad);
            
        }
        if ($area_destino) {
            $item->id_area_destino = $area_destino->id;
            
        }
        $material_requerido = $area_destino->materialesRequeridos()->where("id_material", $material->id_material)->first();
        $item->id_material_requerido = $material_requerido->id;
        
        $this->items()->save($item);
    }
    public function usuario_registro(){
        return $this->hasOne(User::class,"idusuario", "id_usuario");
    }
    public function transacciones(){
        return $this->belongsToMany(Transaccion::class, "Equipamiento.asignaciones_transacciones", "id_asignacion", "id_transaccion" );
    }
    public function comprobantes() {
        return $this->hasMany(Comprobante::class, 'id_asignacion', 'id');
    }  
    public function agregaComprobante(Comprobante $comprobante) {
        return $this->comprobantes()->save($comprobante);
    }
}
