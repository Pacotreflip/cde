<?php

namespace Ghi\Domain\Conciliacion;

use Ghi\Domain\Almacenes\AlmacenMaquinaria;
use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
use Ghi\Domain\Core\Empresa;
use Ghi\Domain\Core\Usuarios\UsuarioCadeco;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Conciliacion extends Model
{
    use PresentableTrait;

    /**
     * Dias considerados en un mes como base
     */
    const DIAS_EN_MES = 30;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Maquinaria.conciliaciones';

    /**
     * @var array
     */
    protected $fillable = ['fecha_inicial', 'fecha_final', 'observaciones'];

    /**
     * @var array
     */
    protected $dates = ['fecha_inicial', 'fecha_final'];

    /**
     * @var array
     */
    protected $casts = [
        'dias_con_operacion'           => 'int',
        'horas_contrato'               => 'int',
        'factor_contrato_periodo'      => 'float',
        'horas_a_conciliar'            => 'int',
        'horas_pagables'               => 'int',
        'horas_efectivas'              => 'int',
        'horas_reparacion_mayor'       => 'int',
        'horas_reparacion_menor'       => 'int',
        'horas_mantenimiento'          => 'int',
        'horas_ocio'                   => 'int',
        'horas_traslado'               => 'int',
        'horometro_inicial'            => 'float',
        'horometro_final'              => 'float',
        'horas_horometro'              => 'int',
        'horas_efectivas_conciliadas'  => 'int',
        'horas_reparacion_conciliadas' => 'int',
        'horas_ocio_conciliadas'       => 'int',
    ];

    /**
     * @var string
     */
    protected $presenter = ConciliacionPresenter::class;

    /**
     * Empresa relacionada con esta conciliacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    /**
     * Almacen de maquinaria relacionado a esta conciliacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(AlmacenMaquinaria::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Usuario que genera esta conciliacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creadoPor()
    {
        return $this->belongsTo(UsuarioCadeco::class, 'creado_por', 'usuario');
    }

    /**
     * Cierra el periodo de conciliacion
     *
     * @throws ReglaNegocioException
     */
    public function cerrar()
    {
        if ($this->cerrada) {
            return;
        }

        // validar que:
        // horas reparacion conciliadas no sean mayor al total de horas reparacion mayor

        $this->cerrada = true;

        return $this;
    }

    /**
     * Calcula el numero de dias de la conciliacion
     *
     * @return float
     */
    public function diasConciliacion()
    {
        return $this->fecha_inicial->diffInDays($this->fecha_final);
    }

    /**
     * Calcula el total de horas de operacion del periodo
     *
     * @return float
     */
    public function getTotalHoras()
    {
        return $this->horas_efectivas +
            $this->horas_reparacion_mayor +
            $this->horas_reparacion_menor +
            $this->horas_mantenimiento +
            $this->horas_ocio +
            $this->horas_traslado;
    }
}
