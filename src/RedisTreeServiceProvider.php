<?php

namespace Mmeyer2k\RedisTree;

use Illuminate\Support\ServiceProvider;

class RedisTreeServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'redistree');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('redistree.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
                __DIR__ . '/../config.php', 'redistree'
        );
    }

}
