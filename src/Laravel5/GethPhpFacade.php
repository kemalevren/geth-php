<?php
/**
 * Created by PhpStorm.
 * User: kemalevren
 * Date: 14/12/2017
 * Time: 18:33
 */

namespace kemalevren\Geth\Laravel5;

use Illuminate\Support\Facades\Facade;

class GethPhpFacade extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'json-rpc';
    }
} 