<?php

declare(strict_types=1);

namespace Mmeyer2k\RedisTree;

use Illuminate\View\View;

class RedisTreeViewComposer
{
    public function compose(View $view) {
        $view->with('option', function (string $option) {
            return RedisTreeModel::option($option);
        });
    }
}