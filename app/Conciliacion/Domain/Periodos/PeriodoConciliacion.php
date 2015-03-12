<?php namespace Ghi\Conciliacion\Domain\Periodos;

use Carbon\Carbon;
use Ghi\Core\App\Exceptions\ReglaNegocioException;
use Ghi\Core\Domain\Obras\Obra;
use Ghi\Conciliacion\Domain\Events\PeriodoFueCerrado;
use Ghi\Conciliacion\Domain\Proveedor;
use Ghi\Core\Domain\Usuarios\UsuarioCadeco;
use Ghi\SharedKernel\Models\Equipo;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Commander\Events\EventGenerator;
use Laracasts\Presenter\PresentableTrait;

class PeriodoConciliacion extends Model {

    use EventGenerator, PresentableTrait;

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
    protected $table = 'maquinaria.periodos_conciliacion';

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
    ];

    /**
     * @var array
     */
    protected $dates = ['fecha_inicial', 'fecha_final'];

    /**
     * @var string
     */
    protected $presenter = PeriodoConciliacionPresenter::class;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_empresa', 'id_empresa');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_almacen', 'id_almacen');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuarioRegistro()
    {
        return $this->belongsTo(UsuarioCadeco::class, 'usuario', 'usuario');
    }

    /**
     * @param $value
     */
    public function setFechaInicialAttribute($value)
    {
        if (is_string($value))
        {
            $this->attributes['fecha_inicial'] = new Carbon($value);
        }

        $this->attributes['fecha_inicial'] = $value;
    }

    /**
     * @param $value
     */
    public function setFechaFinalAttribute($value)
    {
        if (is_string($value))
        {
            $this->attributes['fecha_final'] = new Carbon($value);
        }

        $this->attributes['fecha_final'] = $value;
    }

    /**
     * @param Obra $obra
     * @param Proveedor $proveedor
     * @param Equipo $equipo
     * @param $fechaInicial
     * @param $fechaFinal
     * @param $diasConOperacion
     * @param $horasContrato
     * @param $horasEfectivas
     * @param $horasReparacionMayor
     * @param $horasReparacionMenor
     * @param $horasMantenimiento
     * @param $horasOcio
     * @param $horometroInicial
     * @param $horometroFinal
     * @param $horasHorometro
     * @param $observaciones
     * @param UserSAO $usuario
     * @return static
     */
    public static function generar(
        Obra $obra,
        Proveedor $proveedor,
        Equipo $equipo,
        $fechaInicial,
        $fechaFinal,
        $diasConOperacion,
        $horasContrato,
        $horasEfectivas,
        $horasReparacionMayor,
        $horasReparacionMenor,
        $horasMantenimiento,
        $horasOcio,
        $horometroInicial,
        $horometroFinal,
        $horasHorometro,
        $observaciones,
        UserSAO $usuario
    )
    {
        $periodo = new static([
            'fecha_inicial' => $fechaInicial,
            'fecha_final' => $fechaFinal,
            'dias_con_operacion' => $diasConOperacion,
            'horas_contrato' => (int) $horasContrato,
            'factor_contrato_periodo' => $horasContrato / self::DIAS_EN_MES,
            'horas_efectivas' => (int) $horasEfectivas,
            'horas_reparacion_mayor' => (int) $horasReparacionMayor,
            'horas_reparacion_menor' => (int) $horasReparacionMenor,
            'horas_mantenimiento' => (int) $horasMantenimiento,
            'horas_ocio' => (int) $horasOcio,
            'horometro_inicial' => (int) $horometroInicial,
            'horometro_final' => (int) $horometroFinal,
            'horas_horometro' => (int) $horasHorometro,
            'observaciones' => $observaciones,
        ]);

        $periodo->horas_a_conciliar = (int) ($periodo->factor_contrato_periodo * $periodo->diasConciliacion());

        $periodo->obra()->associate($obra);
        $periodo->proveedor()->associate($proveedor);
        $periodo->equipo()->associate($equipo);
        $periodo->usuarioRegistro()->associate($usuario);

        return $periodo;
    }

    /**
     * Cierra el periodo de conciliacion
     * @param $horasEfectivas
     * @param $horasReparacionMayor
     * @param $horasOcio
     * @throws ReglaNegocioException
     */
    public function cerrar($horasEfectivas, $horasReparacionMayor, $horasOcio)
    {
        if ($this->cerrado)
        {
            return;
        }

        $this->horas_conciliadas_efectivas = $horasEfectivas;
        $this->horas_conciliadas_reparacion_mayor = $horasReparacionMayor;
        $this->horas_conciliadas_ocio = $horasOcio;

        $this->horas_conciliadas = $horasEfectivas + $horasReparacionMayor + $horasOcio;

        if ( ! $this->horasEfectivasCubrenPeriodo())
        {
            if ($this->seSuperanHorasAConciliar())
            {
                throw new ReglaNegocioException("La suma de distribución ({$this->horas_conciliadas})
                    no puede superar el total de horas a conciliar ({$this->horas_a_conciliar})");
            }
        }

        $this->cerrado = true;

        $this->raise(new PeriodoFueCerrado($this->id));
    }

    /**
     * Calcula las horas por pagar propuestas
     * @return mixed
     */
    public function horasPropuestas()
    {
        if ($this->horasEfectivasCubrenPeriodo())
        {
            return $this->horas_efectivas;
        }

        $horas = $this->horas_efectivas;

        $diferencia = $this->horas_a_conciliar - $this->horas_efectivas;

        if ($diferencia > $this->horas_reparacion_mayor)
        {
            $horas += $diferencia - $this->horas_reparacion_mayor;
        }

        return $horas;
    }

    /**
     * Obtiene las horas de reparacion mayor a proponer
     * @return int|mixed
     */
    public function horasReparacionMayorPropuesta()
    {
        if ($this->horasEfectivasCubrenPeriodo())
        {
            return $this->horas_reparacion_mayor;
        }

        return 0;
    }

    /**
     * Obtiene las horas de ocio a proponer
     * @return mixed
     */
    public function horasOcioPropuesta()
    {
        return $this->horasPropuestas() - $this->horas_efectivas;
    }

    /**
     * Calcula el numero de dias de la conciliacion
     * @return mixed
     */
    public function diasConciliacion()
    {
        return $this->fecha_inicial->subDay()->diffInDays($this->fecha_final);
    }

    /**
     * Indica si las horas conciliadas superan a las horas a conciliar
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
     * Indica si las horas efectivas son mayores o iguales
     * a las horas a conciliar de este periodo
     * @return bool
     */
    protected function horasEfectivasCubrenPeriodo()
    {
        return $this->horas_a_conciliar <= $this->horas_efectivas;
    }

    /**
     * Indica si la operacion del periodo esta completa (todos los dias
     * del periodo tienen operacion)
     * @return bool
     */
    public function operacionEstaCompleta()
    {
        if (
//            $this->diasConciliacion() != $this->dias_con_operacion ||
            $this->horas_a_conciliar > $this->getTotalHoras()
        )
        {
            return false;
        }

        return true;
    }

    /**
     * Calcula el total de horas de operacion del periodo
     * @return mixed
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