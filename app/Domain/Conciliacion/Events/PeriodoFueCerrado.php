<?php  namespace Ghi\Conciliacion\Domain\Events;

class PeriodoFueCerrado {

    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }

}