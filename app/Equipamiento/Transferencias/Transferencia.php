<?php

namespace Ghi\Equipamiento\Transferencias;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Transacciones\ItemTransaccion;

class Transferencia extends Model
{
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
    protected $fillable = ['fecha', 'observaciones'];

    /**
     * Campos transformados a instancias de Carbon.
     * 
     * @var array
     */
    protected $dates = ['fecha'];

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
        return $this->morphMany(ItemTransaccion::class, 'transaccion', 'tipo_transaccion', 'id_transaccion');
    }

    /**
     * Area origen de de esta transferencia.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area_origen');
    }

    /**
     * Obtiene el siguiente folio disponible para asignar a esta transferencia.
     * 
     * @return integer
     */
    protected function asignaFolio()
    {
        $this->numero_folio = static::where('id_obra', $this->id_obra)
            ->max('numero_folio') + 1;
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
    public static function crear(Obra $obra, $fecha, Area $origen, $observaciones, $usuario)
    {
        $transferencia = new static();
        $transferencia->area()->associate($origen);
        $transferencia->obra()->associate($obra);
        $transferencia->fecha = $fecha;
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
     * @return ItemTransaccion
     */
    public function transfiereMaterial(Material $material, Area $origen, Area $destino, $cantidad)
    {
        $item = ItemTransaccion::nuevoConMaterial($material, $cantidad);
        $item->id_area_origen = $origen->id;
        $item->id_area_destino = $destino->id;
        $item->cantidad = $cantidad;
        $this->items()->save($item);

        $material->transferir($cantidad, $origen, $destino, $item);

        return $item;
    }
}