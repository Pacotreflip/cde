<?php namespace Ghi\Operacion\Domain;

use Ghi\Core\Domain\Conceptos\Concepto;
use Ghi\Core\Domain\Usuarios\UsuarioCadeco;
use Ghi\Operacion\Domain\Exceptions\ConceptoNoEsMedibleException;
use Ghi\Operacion\Domain\ReporteActividad;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model {

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
        'id_tipo_hora',
        'cantidad',
        'con_cargo',
        'observaciones',
        'usuario',
    ];

    /**
     * Reporte de horas al que pertenece la hora actual
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reporte()
    {
        return $this->belongsTo(ReporteActividad::class, 'id_reporte');
    }

    /**
     * Tipo de hora de la hora actual
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoHora()
    {
        return $this->belongsTo(TipoHora::class, 'id_tipo_hora');
    }

    /**
     * Concepto asignado a la hora
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'id_concepto', 'id_concepto');
    }

    /**
     * Usuario que capturo la hora en el reporte
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuarioRegistro()
    {
        return $this->belongsTo(UsuarioCadeco::class, 'usuario', 'usuario');
    }

    /**
     * @param $idTipoHora
     * @param $cantidad
     * @param Concepto $concepto
     * @param $conCargo
     * @param $observaciones
     * @param $usuario
     * @throws ConceptoNoEsMedibleException
     * @return Actividad
     */
    public static function creaHora($idTipoHora, $cantidad, Concepto $concepto = null, $conCargo, $observaciones, $usuario)
    {
        $horaTipo = new HoraTipo($idTipoHora);

        $hora = new self([
            'cantidad' => $cantidad,
            'con_cargo' => $conCargo,
            'observaciones' => $observaciones,
            'usuario' => $usuario,
        ]);

        $hora->id_tipo_hora = $horaTipo->getIdTipoHora();

        if ($concepto)
        {
            if ( ! $concepto->esMedible())
            {
                throw new ConceptoNoEsMedibleException;
            }

            $hora->concepto()->associate($concepto);
        }

        return $hora;
    }


    /**
     * @param ReporteOperacion $unReporte
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function reportarEn(ReporteOperacion $unReporte)
    {
        $unReporte->verificaLimiteHorasDiarias($this->cantidad);

        return $unReporte->horas()->save($this);
    }

    /**
     * @return mixed
     */
    public function tieneDestino()
    {
        return $this->concepto;
    }
}
