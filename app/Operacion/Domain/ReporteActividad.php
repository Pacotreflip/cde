<?php namespace Ghi\Operacion\Domain;

use Ghi\Core\App\Exceptions\ReglaNegocioException;
use Ghi\Core\Domain\Almacenes\AlmacenMaquinaria;
use Ghi\Core\Domain\Obras\Obra;
use Ghi\Core\Domain\Usuarios\User;
use Ghi\Operacion\Domain\Events\ReporteHorasSeHaRegistrado;
use Ghi\Operacion\Domain\Events\ReporteActividadSeHaRegistrado;
use Ghi\Operacion\Domain\Exceptions\LimiteDeHorasSuperadoException;
use Ghi\Operacion\Domain\Exceptions\ReporteOperacionCerradoException;
use Illuminate\Database\Eloquent\Model;
//use Laracasts\Commander\Events\EventGenerator;
use Laracasts\Presenter\PresentableTrait;

class ReporteActividad extends Model {

    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'maquinaria.reportes_actividad';

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
    protected $dates = ['fecha'];

    /**
     * @var string
     */
    protected $presenter = ReporteActividadPresenter::class;

    /**
     * Obra a la que pertenece el reporte actual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Almacen maquina al que pertenece el reporte actual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(AlmacenMaquinaria::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Horas asociadas con el reporte actual
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'id_reporte');
    }

    /**
     * Usuario que registra el reporte actual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por', 'usuario');
    }

//    /**
//     * @param $value
//     */
//    public function setFechaAttribute($value)
//    {
//        if (is_object($value))
//        {
//            $this->attributes['fecha'] = $value;
//        }
//
//        $this->attributes['fecha'] = date('Y-m-d',(strtotime($value)));
//    }

    /**
     * Crea un reporte de actividades
     *
     * @return static
     */
    public static function registrar(array $data)
    {
        return new static($data);
    }

    /**
     * @param $cantidad
     * @throws LimiteDeHorasSuperadoException
     */
    public function verificaLimiteHorasDiarias($cantidad)
    {
        if ($this->horas->sum('cantidad') + $cantidad > 24)
        {
            throw (new LimiteDeHorasSuperadoException)->setHorasActuales($this->horas->sum('cantidad'));
        }
    }

    /**
     * @param $horometroFinal
     * @param $kilometrajeFinal
     * @return $this
     * @throws ReglaNegocioException
     * @throws ReporteOperacionCerradoException
     */
    public function cierraOperacion($horometroFinal, $kilometrajeFinal)
    {
        if ($this->cerrado)
        {
            throw new ReporteOperacionCerradoException;
        }

        if ($this->horometro_inicial && $this->horometro_inicial > $horometroFinal)
        {
            throw new ReglaNegocioException('El horometro final no puede ser menor al inicial.');
        }

        if ($this->kilometraje_inicial && $this->kilometraje_inicial > $kilometrajeFinal)
        {
            throw new ReglaNegocioException('El kilometraje final no puede ser menor al inicial.');
        }

        if ($this->horas()->count() == 0)
        {
            throw new ReglaNegocioException('El reporte no tiene horas reportadas.');
        }

        $this->cerrado = true;
        $this->horometro_final = $horometroFinal;
        $this->kilometraje_final = $kilometrajeFinal;

        return $this;
    }
}