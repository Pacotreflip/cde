<?php namespace Ghi\Operacion\Domain;

use Ghi\Core\App\Exceptions\ReglaNegocioException;
use Ghi\Almacenes\Domain\AlmacenMaquinaria;
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
     * Numero limite de horas que pueden ser reportadas en un dia
     */
    const LIMITE_HORAS_DIA = 24;

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
    protected $casts = [
        'cerrado' => 'boolean',
        'conciliado' => 'boolean',
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
     * Identifica si las horas diarias limite del reporte son superadas al reportar mas actividades
     *
     * @param $cantidad
     * @throws LimiteDeHorasSuperadoException
     */
    public function superaLimiteHorasDiarias($cantidad)
    {
        if ($this->actividades->sum('cantidad') + $cantidad > static::LIMITE_HORAS_DIA)
        {
            throw (new LimiteDeHorasSuperadoException)->setHorasActuales($this->actividades->sum('cantidad'));
        }
    }

    /**
     * @return $this
     * @throws ReglaNegocioException
     * @throws ReporteOperacionCerradoException
     */
    public function cerrar()
    {
        if ($this->cerrado)
        {
            throw new ReporteOperacionCerradoException;
        }

        if ($this->horometro_inicial && $this->horometro_inicial > $this->horometro_final)
        {
            throw new ReglaNegocioException('El horometro final no puede ser menor al inicial.');
        }

        if ($this->kilometraje_inicial && $this->kilometraje_inicial > $this->kilometraje_final)
        {
            throw new ReglaNegocioException('El kilometraje final no puede ser menor al inicial.');
        }

        if ($this->actividades()->count('cantidad') == 0)
        {
            throw new ReglaNegocioException('El reporte no contiene actividades reportadas.');
        }

        $this->cerrado = true;

        $this->save();

        return $this;
    }

}
