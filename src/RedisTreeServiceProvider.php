<?php

namespace Mmeyer2k\RedisTree;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class RedisTreeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'redistree');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('redistree.php'),
        ]);

        view()->composer('redistree::*', function (View $view) {
            view()->share('option', function (string $option) {
                return RedisTreeModel::option($option);
            });
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config.php', 'redistree'
        );
    }
}
