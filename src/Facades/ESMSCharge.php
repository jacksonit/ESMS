<?php

namespace Jacksonit\ESMS\Facades;

use Illuminate\Support\Facades\Facade;

class ESMSCharge extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ESMSCharge';
    }
}