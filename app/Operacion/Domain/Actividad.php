<?php namespace Ghi\Operacion\Domain;

use Ghi\Core\Domain\Conceptos\Concepto;
use Ghi\Core\Domain\Usuarios\User;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Actividad extends Model {

    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'maquinaria.actividades';

    /**
     * @var array
     */
    protected $fillable = [
        'cantidad',
        'con_cargo',
        'observaciones',
        'hora_inicial',
        'hora_final',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'con_cargo' => 'boolean',
    ];

    /**
     * @var
     */
    protected $presenter = ActividadPresenter::class;

    /**
     * Reporte de actividades al que pertenece la actividad actual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reporte()
    {
        return $this->belongsTo(ReporteActividad::class, 'id_reporte');
    }

    /**
     * Tipo de hora de la actividad actual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoHora()
    {
        return $this->belongsTo(TipoHora::class, 'id_tipo_hora');
    }


    /**
     * Concepto destino de la actividad
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destino()
    {
        return $this->belongsTo(Concepto::class, 'id_concepto', 'id_concepto');
    }


    /**
     * Usuario que reporto la actividad
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por', 'usuario');
    }


    /**
     * @param ReporteActividad $reporte
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function reportarEn(ReporteActividad $reporte)
    {
        $reporte->superaLimiteHorasDiarias($this->cantidad);

        return $reporte->actividades()->save($this);
    }

    /**
     * @return mixed
     */
    public function tieneDestino()
    {
        return (boolean) $this->concepto;
    }
}
