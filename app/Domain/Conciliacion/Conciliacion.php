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
    protected $fillable = [
        'fecha_inicial',
        'fecha_final',
        'dias_con_operacion',
        'horas_contrato',
        'factor_contrato_periodo',
        'horas_contrato_periodo',
        'horas_efectivas',
        'horas_reparacion_mayor',
        'horas_reparacion_menor',
        'horas_mantenimiento',
        'horas_ocio',
        'horometro_inicial',
        'horometro_final',
        'horas_horometro',
        'horas_conciliadas',
        'observaciones',
    ];

    /**
     * @var array
     */
    protected $dates = ['fecha_inicial', 'fecha_final'];

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
     * Crea una nueva conciliacion
     *
     * @param array $data
     * @return static
     */
    public static function generar(array $data)
    {
        $conciliacion = new static([
            'fecha_inicial' => $data['fecha_inicial'],
            'fecha_final' => $data['fecha_final'],
            'dias_con_operacion' => $data['dias_con_operacion'],
            'horas_contrato' => $data['horas_contrato'],
            'factor_contrato_periodo' => $data['horas_contrato'] / self::DIAS_EN_MES,
            'horas_efectivas' => $data['horas_efectivas'],
            'horas_reparacion_mayor' => $data['horas_reparacion_mayor'],
            'horas_reparacion_menor' => $data['horas_reparacion_menor'],
            'horas_mantenimiento' => $data['horas_mantenimiento'],
            'horas_ocio' => $data['horas_ocio'],
            'horometro_inicial' => $data['horometro_inicial'],
            'horometro_final' => $data['horometro_final'],
            'horas_horometro' => $data['horas_horometro'],
            'observaciones' => $data['observaciones'],
        ]);

        $conciliacion->horas_a_conciliar = (int) ($conciliacion->factor_contrato_periodo * $conciliacion->diasConciliacion());

        return $conciliacion;
    }

    /**
     * Cierra el periodo de conciliacion
     *
     * @param $horasEfectivas
     * @param $horasReparacionMayor
     * @param $horasOcio
     * @throws ReglaNegocioException
     */
    public function cerrar($horasEfectivas, $horasReparacionMayor, $horasOcio)
    {
        if ($this->cerrado) {
            return;
        }

        $this->horas_conciliadas_efectivas = $horasEfectivas;
        $this->horas_conciliadas_reparacion_mayor = $horasReparacionMayor;
        $this->horas_conciliadas_ocio = $horasOcio;

        $this->horas_conciliadas = $horasEfectivas + $horasReparacionMayor + $horasOcio;

        if (! $this->horasEfectivasCubrenPeriodo()) {
            if ($this->seSuperanHorasAConciliar()) {
                throw new ReglaNegocioException("La suma de distribución ({$this->horas_conciliadas})
                    no puede superar el total de horas a conciliar ({$this->horas_a_conciliar})");
            }
        }

        $this->cerrado = true;
    }

    /**
     * Calcula las horas a pagar propuestas
     *
     * @return float
     */
    public function horasPropuestas()
    {
        if ($this->horasEfectivasCubrenPeriodo()) {
            return $this->horas_efectivas;
        }

        $horas = $this->horas_efectivas;

        $diferencia = $this->horas_a_conciliar - $this->horas_efectivas;

        if ($diferencia > $this->horas_reparacion_mayor) {
            $horas += $diferencia - $this->horas_reparacion_mayor;
        }

        return $horas;
    }

    /**
     * Obtiene las horas de reparacion mayor a proponer
     *
     * @return float
     */
    public function horasReparacionMayorPropuesta()
    {
        if ($this->horasEfectivasCubrenPeriodo()) {
            return $this->horas_reparacion_mayor;
        }
        return 0;
    }

    /**
     * Obtiene las horas de ocio a proponer
     *
     * @return float
     */
    public function horasOcioPropuesta()
    {
        return $this->horasPropuestas() - $this->horas_efectivas;
    }

    /**
     * Calcula el numero de dias de la conciliacion
     *
     * @return float
     */
    public function diasConciliacion()
    {
        return $this->fecha_inicial->subDay()->diffInDays($this->fecha_final);
    }

    /**
     * Indica si las horas conciliadas superan a las horas a conciliar
     *
     * @return bool
     */
    protected function seSuperanHorasAConciliar()
    {
        return (
            $this->horas_conciliadas_efectivas +
            $this->horas_conciliadas_reparacion_mayor +
            $this->horas_conciliadas_ocio) > $this->horas_a_conciliar;
    }

    /**
     * Indica si las horas efectivas son mayores o iguales a las horas a conciliar de este periodo
     *
     * @return bool
     */
    protected function horasEfectivasCubrenPeriodo()
    {
        return $this->horas_a_conciliar <= $this->horas_efectivas;
    }

    /**
     * Indica si la operacion del periodo esta completa (todos los dias del periodo tienen operacion)
     *
     * @return bool
     */
    public function operacionEstaCompleta()
    {
        if ($this->horas_a_conciliar > $this->getTotalHoras()) {
            return false;
        }
        return true;
    }

    /**
     * Calcula el total de horas de operacion del periodo
     *
     * @return float
     */
    protected function getTotalHoras()
    {
        return $this->horas_efectivas +
            $this->horas_reparacion_mayor +
            $this->horas_reparacion_menor +
            $this->horas_mantenimiento +
            $this->horas_ocio;
    }
}
