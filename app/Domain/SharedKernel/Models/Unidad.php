<?php namespace Ghi\SharedKernel\Models;

use Ghi\Core\App\TenantModel;

class Unidad extends TenantModel {

    /**
     * @var string
     */
    protected $table = 'unidades';

    /**
     * @var string
     */
//    protected $primaryKey = 'unidad';

    /**
     * @var bool
     */
    public $timestamps = false;

}