<?php

namespace Ghi\Domain\ReportesActividad;

use Ghi\Domain\Core\Conceptos\Concepto;
use Ghi\Domain\Core\Usuarios\User;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Actividad extends Model
{
    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Maquinaria.actividades';

    /**
     * @var array
     */
    protected $fillable = [
        'cantidad',
        'con_cargo_empresa',
        'hora_inicial',
        'hora_final',
        'observaciones',
        'turno',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'con_cargo_empresa' => 'boolean',
    ];

    /**
     * @var
     */
    protected $presenter = ActividadPresenter::class;

    public function getTipoHoraAttribute($value)
    {
        return new TipoHora($value);
    }
    
    public function setTipoHoraAttribute($value)
    {
        $this->attributes['tipo_hora'] = (new TipoHora($value))->getCodigo();
    }

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
}
