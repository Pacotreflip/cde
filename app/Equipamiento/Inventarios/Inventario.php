<?php

namespace Ghi\Equipamiento\Inventarios;

use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
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
        'cantidad_existencia' => 'float',
    ];

    /**
     * Cantidad anterior del inventario.
     * 
     * @var float
     */
    private $cantidadAnterior = 0;

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
            $inventario->cantidadAnterior = $inventario->getOriginal('cantidad_existencia');
        });

        static::updated(function ($inventario) {
            $inventario->creaMovimientoInventario($inventario->cantidadAnterior, $inventario->cantidad_existencia);
        });

        static::created(function ($inventario) {
            $inventario->creaMovimientoInventario($inventario->cantidadAnterior, $inventario->cantidad_existencia);
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
    public function incrementaExistencia($cantidad)
    {
        $actual = $this->cantidad_existencia;
        $total = $actual + $cantidad;

        if ((float) $total === (float) $actual) {
            return $this;
        }

        $this->beginTransaction();

        try {
            if ($this->increments('cantidad_existencia', $total)) {
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
    public function decrementaExistencia($decremento)
    {
        if ($this->tieneExistenciaSuficiente($decremento)) {
            $disponible = $this->cantidad_existencia;
            $restante = $disponible - $decremento;
            
            $this->beginTransaction();

            try {
                if ($this->decrement('cantidad_existencia', $restante)) {
                    $this->commitTransaction();
                }

                return $this;
            } catch (SinExistenciaDisponibleException $e) {
                $this->rollbackTransaction();
            }
        }

        return false;
    }

    /**
     * Transfiere existencia de un inventario a otro.
     *
     * @param  float $cantidad La cantidad a transferir.
     * @param  Inventario $inventario_destino El inventario destino.
     * @return bool
     *
     * @throws \Exception
     */
    public function transferirA($cantidad, Inventario $inventario_destino)
    {
        $this->beginTransaction();

        try {
            $this->decrementaExistencia($cantidad);

            $inventario_destino->incrementaExistencia($cantidad);

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
        $disponible = $this->cantidad_existencia;

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
     * @return MovimientoInventario
     */
    protected function creaMovimientoInventario($cantidad_anterior, $cantidad_actual)
    {
        $movimiento = $this->movimientos()->getRelated()->newInstance();
        $movimiento->cantidad_anterior = $cantidad_anterior;
        $movimiento->cantidad_actual = $cantidad_actual;
        $this->movimientos()->save($movimiento);

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
