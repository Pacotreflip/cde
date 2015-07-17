<?php

namespace Ghi\Domain\ReportesActividad;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
use Ghi\Domain\Almacenes\AlmacenMaquinaria;
use Ghi\Domain\Core\Usuarios\User;
use Ghi\Domain\ReportesActividad\Events\ReporteHorasSeHaRegistrado;
use Ghi\Domain\ReportesActividad\Exceptions\LimiteDeHorasSuperadoException;
use Ghi\Domain\ReportesActividad\Exceptions\ReporteAprobadoException;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class ReporteActividad extends Model
{
    use PresentableTrait;

    /**
     * Numero limite de horas que pueden ser reportadas en un dia
     */
    const LIMITE_HORAS_POR_DIA  = 24;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Maquinaria.reportes_actividad';

    /**
     * @var array
     */
    protected $fillable = [
        'fecha',
        'horometro_inicial',
        'kilometraje_inicial',
        'operador',
        'observaciones',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'aprobado' => 'bool',
        'conciliado' => 'bool',
        'horometro_inicial' => 'float',
        'kilometraje_inicial' => 'int',
    ];

    /**
     * @var array
     */
    protected $dates = ['fecha'];

    /**
     * @var string
     */
    protected $presenter = ReporteActividadPresenter::class;

    /**
     * Almacen de maquinaria relacionado con este reporte
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(AlmacenMaquinaria::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Horas asociadas con este reporte
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'id_reporte');
    }

    /**
     * Usuario que registra este reporte
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por', 'usuario');
    }

    /**
     * Identifica si las horas diarias limite del reporte son superadas al reportar mas actividades
     *
     * @param $cantidad
     * @throws LimiteDeHorasSuperadoException
     */
    public function superaLimiteHorasDiarias($cantidad)
    {
        if ($this->actividades->sum('cantidad') + $cantidad > static::LIMITE_HORAS_POR_DIA) {
            throw (new LimiteDeHorasSuperadoException)->setHorasActuales($this->actividades->sum('cantidad'));
        }
    }

    /**
     * @return $this
     * @throws ReglaNegocioException
     * @throws ReporteAprobadoException
     */
    public function aprobar()
    {
        if ($this->aprobado) {
            throw new ReporteAprobadoException;
        }

        if ($this->horometro_inicial && $this->horometro_inicial > $this->horometro_final) {
            throw new ReglaNegocioException('El horometro final no puede ser menor al inicial.');
        }

        if ($this->kilometraje_inicial && $this->kilometraje_inicial > $this->kilometraje_final) {
            throw new ReglaNegocioException('El kilometraje final no puede ser menor al inicial.');
        }

        if ($this->actividades()->count('cantidad') == 0) {
            throw new ReglaNegocioException('El reporte no contiene actividades reportadas.');
        }

        $this->aprobado = true;

        return $this;
    }
}
