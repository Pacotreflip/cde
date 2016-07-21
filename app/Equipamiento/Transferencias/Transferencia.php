<?php

namespace Ghi\Equipamiento\Transferencias;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Comprobantes\Comprobante;

class Transferencia extends Model
{
    use TransaccionTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.transferencias';

    /**
     * Campos asignables de forma masiva.
     * 
     * @var array
     */
    protected $fillable = ['fecha_transferencia', 'observaciones'];

    /**
     * Campos transformados a instancias de Carbon.
     * 
     * @var array
     */
    protected $dates = ['fecha_transferencia'];

    /**
     * [boot description].
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transferencia) {
            $transferencia->asignaFolio();
        });
    }

    /**
     * Obra relacionada con esta transferencia.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Items relacionados con esta transferencia.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function items()
    {
        return $this->hasMany(ItemTransferencia::class, 'id_transferencia');
    }

    /**
     * Constructor de una nueva transferencia.
     * 
     * @param  string $fecha
     * @param  Area   $origen
     * @param  string $observaciones
     * @param  string $usuario
     * @return self
     */
    public static function crear(Obra $obra, $fecha, $observaciones, $usuario)
    {
        $transferencia = new static();
        $transferencia->obra()->associate($obra);
        $transferencia->fecha_transferencia = $fecha;
        $transferencia->observaciones = $observaciones;
        $transferencia->creado_por = $usuario;
        $transferencia->save();

        return $transferencia;
    }

    /**
     * Transfiere un material de un area a otra.
     * 
     * @param  Material $material
     * @param  Area $origen
     * @param  Area $destino
     * @param  float $cantidad
     * @return ItemTransferencia
     */
    public function transfiereMaterial(Material $material, Area $origen, Area $destino, $cantidad)
    {
        $item = new ItemTransferencia;
        $item->material()->associate($material);
        $item->unidad = $material->unidad;
        $item->id_area_origen = $origen->id;
        $item->id_area_destino = $destino->id;
        $item->cantidad_transferida = $cantidad;
        $this->items()->save($item);

        $material->transfiereExistencia($cantidad, $origen, $destino);

        return $item;
    }
    public function transacciones(){
        return $this->belongsToMany(Transaccion::class, "Equipamiento.transferencias_transacciones", "id_transferencia", "id_transaccion" );
    }
    public function comprobantes() {
        return $this->hasMany(Comprobante::class, 'id_transferencia', 'id');
    } 
    public function agregaComprobante(Comprobante $comprobante) {
        return $this->comprobantes()->save($comprobante);
    }
}
