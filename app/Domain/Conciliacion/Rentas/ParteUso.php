<?php namespace Ghi\Maquinaria\Domain\Conciliacion\Models;

use Ghi\Core\App\TenantModel;
use Ghi\Maquinaria\Domain\Conciliacion\QueryScopes\TipoTransaccionTrait;
use Ghi\SharedKernel\Models\ComentarioTransaccionTrait;

class ParteUso extends TenantModel {

    const TIPO_TRANSACCION = 36;

    use TipoTransaccionTrait, ComentarioTransaccionTrait;

    /**
     * @var string
     */
    protected $table = 'transacciones';

    /**
     * @var string
     */
    protected $primaryKey = 'id_transaccion';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $dates = ['fecha', 'cumplimiento', 'vencimiento'];

    /**
     * @var array
     */
    protected $fillable = [
        'id_obra',
        'fecha',
        'id_almacen',
        'cumplimiento',
    ];

    /**
     * Items de esta parte de uso (horas reportadas)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('Ghi\Maquinaria\Domain\Conciliacion\Models\ItemParteUso', 'id_transaccion', 'id_transaccion');
    }

    /**
     * Crea una transaccion de partes de uso
     * @param $idObra
     * @param $fecha
     * @param $idAlmacen
     * @param $usuario
     * @return static
     */
    public static function crear($idObra, $fecha, $idAlmacen, $usuario)
    {
        $parteUso = new static([
            'id_obra' => $idObra,
            'fecha' => $fecha,
            'id_almacen' => $idAlmacen,
            'cumplimiento' => $fecha,
        ]);

        $parteUso->tipo_transaccion = static::TIPO_TRANSACCION;
        $parteUso->comentario = $parteUso->getComentarioRegistro($usuario);

        return $parteUso;
    }
}