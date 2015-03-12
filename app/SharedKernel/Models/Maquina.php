<?php  namespace Ghi\SharedKernel\Models; 

use Ghi\Core\App\TenantModel;

class Maquina extends TenantModel {

    /**
     * @var string
     */
    protected $table = 'inventarios';

    /**
     * @var string
     */
    protected $primaryKey = 'id_lote';

    /**
     * @var bool
     */
    public  $timestamps = false;
}