<?php

namespace Mmeyer2k\RedisTree;

class RedisTreeServiceProvider extends \Illuminate\Support\ServiceProvider
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
