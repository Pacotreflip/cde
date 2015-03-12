<?php namespace Ghi\Core\Domain\Obras;

use Ghi\Core\Domain\Obras\Events\ObraSeRegistro;
use Illuminate\Database\Eloquent\Model;
//use Laracasts\Commander\Events\EventGenerator;
use Laracasts\Presenter\PresentableTrait;

class Obra extends Model {

    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'obras';

    /**
     * @var string
     */
    protected $primaryKey = 'id_obra';

    /**
     * @var array
     */
    protected $fillable = [
        'nombre', 'descripcion', 'tipo_obra', 'constructora', 'cliente',
        'facturar', 'responsable', 'rfc', 'direccion', 'ciudad', 'codigo_postal',
        'estado', 'id_moneda', 'iva', 'fecha_inicial', 'fecha_final'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $dates = ['fecha_inicial', 'fecha_final'];

    /**
     * @var string
     */
    protected $presenter = ObraPresenter::class;

    /**
     * Almacenes asociados con la obra actual
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function almacenes()
    {
        return $this->hasMany(Almacen::class, 'id_obra', 'id_obra');
    }

    /**
     * @param $nombre
     * @param $descripcion
     * @param $estadoObra
     * @param $constructora
     * @param $cliente
     * @param $facturar
     * @param $responsable
     * @param $rfc
     * @param $direccion
     * @param $ciudad
     * @param $codigoPostal
     * @param $estado
     * @param $idMoneda
     * @param $iva
     * @param $fechaInicial
     * @param $fechaFinal
     * @return static
     */
//    public static function registrar(
//        $nombre, $descripcion, $estadoObra, $constructora,
//        $cliente, $facturar, $responsable, $rfc, $direccion,
//        $ciudad, $codigoPostal, $estado, $idMoneda, $iva,
//        $fechaInicial, $fechaFinal
//    )
//    {
//        $obra = new static([
//            'nombre' => $nombre,
//            'descripcion' => $descripcion,
//            'tipo_obra' => $estadoObra,
//            'constructora' => $constructora,
//            'cliente' => $cliente,
//            'facturar' => $facturar,
//            'responsable' => $responsable,
//            'rfc' => $rfc,
//            'direccion' => $direccion,
//            'ciudad' => $ciudad,
//            'codigo_postal' => $codigoPostal,
//            'estado' => $estado,
//            'id_moneda' => $idMoneda,
//            'iva' => $iva,
//            'fecha_inicial' => $fechaInicial,
//            'fecha_final' => $fechaFinal
//        ]);
//
//        $obra->raise(new ObraSeRegistro($obra));
//
//        return $obra;
//    }
}