<?php namespace Ghi\Core\App\Facades;

use Illuminate\Support\Facades\Facade;

class Context extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'ghi.context'; }
} 