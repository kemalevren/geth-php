<?php
/*
 * (c) Kemal Evren <hi@kemalevren.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace kemalevren\Geth\Laravel5;

use Illuminate\Support\ServiceProvider;

class GethPhpServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('json-rpc', 'kemalevren\Geth\JsonRpc');
    }
}