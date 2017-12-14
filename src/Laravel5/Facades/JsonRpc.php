<?php
/**
 * Created by PhpStorm.
 * User: kemalevren
 * Date: 14/12/2017
 * Time: 18:33
 */

namespace kemalevren\Geth\Laravel5\Facades;

use Illuminate\Support\Facades\Facade;

class JsonRpc extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'JsonRpc';
    }
}