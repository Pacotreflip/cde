<?php

namespace Ghi\Domain\Conciliacion;

use Ghi\Domain\Core\ComentarioTransaccionTrait;
use Ghi\Domain\Core\Transaccion;

class ParteUso extends Transaccion
{
    use ComentarioTransaccionTrait;

    const TIPO_TRANSACCION = 36;

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
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(ItemParteUso::class, 'id_transaccion', 'id_transaccion');
    }

    /**
     * Crea una transaccion de partes de uso
     *
     * @param $id_obra
     * @param $fecha
     * @param $id_almacen
     * @param $usuario
     * @return static
     */
    public static function crear($id_obra, $fecha, $id_almacen, $usuario)
    {
        $parte_uso = new static([
            'id_obra'      => $id_obra,
            'fecha'        => $fecha,
            'id_almacen'   => $id_almacen,
            'cumplimiento' => $fecha,
        ]);

        $parte_uso->tipo_transaccion = static::TIPO_TRANSACCION;
        $parte_uso->comentario = $parte_uso->getComentarioRegistro($usuario);

        return $parte_uso;
    }
}
