<?php  namespace Ghi\Core\Domain; 

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model {

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'empresas';

    /**
     * @var string
     */
    protected $primaryKey = 'id_empresa';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function entradasEquipo()
    {
        return $this->hasMany(Transaccion::class, 'id_empresa', 'id_empresa')
            ->where('tipo_transaccion', Transaccion::TIPO_ENTRADA_EQUIPO)
            ->where('opciones', Transaccion::OPCIONES_ENTRADA_EQUIPO);
    }
}
