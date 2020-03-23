<?php

namespace EdsonAlvesan\DigiSac\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DigiSac.
 */
class DigiSac extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'digisac';
    }
}
