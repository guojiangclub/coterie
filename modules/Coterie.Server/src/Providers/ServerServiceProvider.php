<?php

/*
 * This file is part of ibrand/coterie-server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Server\Providers;

use Illuminate\Support\ServiceProvider;

class ServerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'coterie');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config.php' => config_path('ibrand/coterie.php'),
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config.php', 'ibrand.coterie'
        );

        $this->app->register(RouteServiceProvider::class);
    }
}
