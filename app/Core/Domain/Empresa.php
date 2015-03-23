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

}
