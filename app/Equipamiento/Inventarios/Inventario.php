<?php

namespace Ghi\Equipamiento\Inventarios;

use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\ManageDatabaseTransactions;
use Ghi\Equipamiento\Inventarios\Exceptions\InventarioNoEncontradoException;
use Ghi\Equipamiento\Inventarios\Exceptions\SinExistenciaSuficienteException;

class Inventario extends Model
{
    use ManageDatabaseTransactions;

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

        static::created(function ($inventario) {
            $inventario->creaMovimientoInventario($inventario->cantidadAnterior, $inventario->cantidad_existencia);
        });

        static::deleting(function ($inventario) {
            if (! $inventario->puedeSerBorrado()) {
                return false;
            }
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
     * Crea un nuevo inventario.
     * 
     * @param  Area     $area
     * @param  Material $material
     * @param  float    $cantidad
     * @return self
     */
    public static function creaInventario(Area $area, Material $material, $cantidad = 0)
    {
        if ($cantidad < 0) {
            throw new \Exception('La cantidad inicial del inventario no puede ser negativa');
        }
        
        if ($this->area->cerrada) {
            throw new \Exception('No se puede crear inventario en un área cerrada.');
        }

        $existe = static::existeInventario($area, $material);

        if ($existe) {
            return $existe;
        }

        $inventario = new static;
        $inventario->id_obra = $area->id_obra;
        $inventario->id_area = $area->getKey();
        $inventario->id_material = $material->getKey();
        $inventario->cantidad_existencia = $cantidad;
        $inventario->save();

        return $inventario;
    }

    /**
     * Verifica si un inventario existe en un area.
     * 
     * @param  Area     $area
     * @param  Material $material
     * @return null|Inventario
     */
    protected static function existeInventario(Area $area, Material $material)
    {
        return static::where('id_area', $area->id)->where('id_material', $material->id_material)->first();
    }

    /**
     * Identifica si este inventario puede ser borrado.
     * 
     * @return bool
     */
    public function puedeSerBorrado()
    {
        return $this->cantidad_existencia <= 0 and $this->movimientos->count() === 1 and $this->area->cerrada === false;
    }

    /**
     * Incrementa la existencia de este inventario.
     * 
     * @param  float $cantidad
     * @return bool|self
     */
    public function incrementaExistencia($cantidad)
    {
        if ($this->area->cerrada) {
            throw new \Exception('No se puede incrementar la existencia de un inventario en una área cerrada.');
        }
        $cantidad_actual = $this->cantidad_existencia;
        $cantidad_total = $cantidad_actual + $cantidad;

        if ((float) $cantidad_total === (float) $cantidad_actual) {
            return $this;
        }

        try {
            $this->beginTransaction();

            if ($this->increment('cantidad_existencia', $cantidad)) {
                $this->creaMovimientoInventario($cantidad_actual, $cantidad_total);
                $this->commitTransaction();
            }

            return $this;
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
        if ($this->area->cerrada) {
            throw new \Exception('No se puede disminuir la existencia de un inventario en una área cerrada.');
        }
        if ($this->tieneExistenciaSuficiente($decremento)) {
            $cantidad_disponible = $this->cantidad_existencia;
            $cantidad_restante = $cantidad_disponible - $decremento;
            
            try {
                $this->beginTransaction();

                if ($this->decrement('cantidad_existencia', $decremento)) {
                    $this->creaMovimientoInventario($cantidad_disponible, $cantidad_restante);
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
     * @param  Inventario $inventario_destino El inventario destino.
     * @param  float      $cantidad           La cantidad a transferir.
     * @return bool
     *
     * @throws \Exception
     */
    public function transferirA(Inventario $inventario_destino, $cantidad)
    {
        if ($this->area->cerrada) {
            throw new \Exception('No se puede transferir la existencia de un inventario en una área cerrada.');
        }
        if ($this->id == $inventario_destino->id) {
            return false;
        }
//
//        try {
            //$this->beginTransaction();

            $this->decrementaExistencia($cantidad);

            $inventario_destino->incrementaExistencia($cantidad);

            //$this->commitTransaction();

            return true;
//        } catch (\Exception $e) {
//            $this->rollbackTransaction();
//            throw $e;
//        }
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
        if ($this->area->cerrada) {
            throw new \Exception('No se puede crear un movimiento de inventario en una área cerrada.');
        }
        $movimiento = $this->movimientos()->getRelated()->newInstance();
        $movimiento->cantidad_anterior = $cantidad_anterior;
        $movimiento->cantidad_actual = $cantidad_actual;
        $this->movimientos()->save($movimiento);
        $this->load('movimientos');

        return $movimiento;
    }
}
