<?php

namespace Ghi\Events;

use Ghi\Domain\Conciliacion\Conciliacion;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ConciliacionFueAprobada extends Event
{
    use SerializesModels;

    /**
     * @var Conciliacion
     */
    public $conciliacion;

    /**
     * Create a new event instance.
     *
     * @param Conciliacion $conciliacion
     */
    public function __construct(Conciliacion $conciliacion)
    {
        $this->conciliacion = $conciliacion;
    }
}
