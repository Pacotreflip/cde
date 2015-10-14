<?php

namespace Ghi\Equipamiento\Inventarios;

use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Transacciones\ItemTransaccion;
use Ghi\Equipamiento\Inventarios\Exceptions\InventarioNoEncontradoException;
use Ghi\Equipamiento\Inventarios\Exceptions\SinExistenciaSuficienteException;

class Inventario extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.inventarios';

    /**
     * [$casts description].
     * 
     * @var array
     */
    protected $casts = [
        'id_material' => 'int',
        'id_area' => 'int',
        'cantidad' => 'float',
    ];

    /**
     * Cantidad anterior del inventario.
     * 
     * @var float
     */
    private $cantidadAnterior;

    /**
     * Item que soporta el movimiento en este inventario.
     * 
     * @var ItemTransaccion
     */
    private $item;

    /**
     * Override del metodo boot para generar los movimientos
     * de este inventario cuando sea actualizado.
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($inventario) {
            $inventario->cantidadAnterior = $inventario->getOriginal('cantidad');
        });

        static::updated(function ($inventario) {
            $inventario->generaMovimientoInventario($inventario->cantidadAnterior, $inventario->cantidad, $inventario->item);
        });
    }

    /**
     * Area relacionada con este inventario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    /**
     * Material relacionado con este inventario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material');
    }

    /**
     * Movimientos relacionados con este inventario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'id_inventario');
    }

    /**
     * Incrementa la existencia de este inventario.
     * 
     * @param  float $cantidad
     * @return bool|self
     */
    public function incrementaExistencia($cantidad, ItemTransaccion $item)
    {
        $this->item = $item;
        $actual = $this->cantidad;
        $total = $actual + $cantidad;
        $this->cantidad = $total;

        if ((float) $total === (float) $actual) {
            return $this;
        }

        $this->beginTransaction();

        try {
            if ($this->save()) {
                $this->commitTransaction();

                return $this;
            }
        } catch (\Exception $e) {
            $this->rollbackTransaction();
        }

        return false;
    }

    /**
     * Decrementa la existencia de este inventario.
     * 
     * @param  float $decremento
     * @throws SinExistenciaSuficienteException
     * @return bool|self
     */
    public function decrementaExistencia($decremento, ItemTransaccion $item)
    {
        $this->item = $item;

        if ($this->tieneExistenciaSuficiente($decremento)) {
            $disponible = $this->cantidad;
            $restante = $disponible - $decremento;
            $this->cantidadAnterior = $disponible;
            $this->cantidad = $restante;
            
            $this->beginTransaction();

            try {
                $this->save();
                $this->commitTransaction();

                return $this;
            } catch (SinExistenciaDisponibleException $e) {
                $this->rollbackTransaction();
            }
        }

        return false;
    }

    /**
     * Transfiere existencia de un inventario a otro.
     * @param  float           $cantidad           La cantidad a transferir.
     * @param  Inventario      $inventario_destino El inventario destino.
     * @param  ItemTransaccion $item               El item que soporta este movimiento.
     * @return bool
     */
    public function transferirA($cantidad, Inventario $inventario_destino, ItemTransaccion $item)
    {
        $this->beginTransaction();

        try {
            $this->decrementaExistencia($cantidad, $item);

            $inventario_destino->incrementaExistencia($cantidad, $item);

            $this->commitTransaction();

            return true;
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
    }

    /**
     * Indica si este inventario tiene existencia suficiente.
     * 
     * @param  integer $cantidad
     * @throws SinExistenciaSuficienteException
     * @return bool
     */
    protected function tieneExistenciaSuficiente($cantidad = 0)
    {
        $disponible = $this->cantidad;

        if ((float) $disponible >= (float) $cantidad) {
            return true;
        }

        throw new SinExistenciaSuficienteException;
    }

    /**
     * Genera un movimiento de este inventario.
     * 
     * @param  float $cantidad_anterior
     * @param  float $cantidad_actual
     * @param  ItemTransaccion $item
     * @return MovimientoInventario
     */
    protected function generaMovimientoInventario($cantidad_anterior, $cantidad_actual, ItemTransaccion $item)
    {
        $movimiento = $this->movimientos()->getRelated()->newInstance();
        $movimiento->id_inventario = $this->getKey();
        $movimiento->id_item = $item->getKey();
        $movimiento->cantidad_anterior = $cantidad_anterior;
        $movimiento->cantidad_actual = $cantidad_actual;
        $movimiento->save();

        return $movimiento;
    }

    protected function beginTransaction()
    {
        DB::connection($this->connection)->beginTransaction();
    }

    protected function commitTransaction()
    {
        DB::connection($this->connection)->commit();
    }

    protected function rollbackTransaction()
    {
        DB::connection($this->connection)->rollback();
    }
}
