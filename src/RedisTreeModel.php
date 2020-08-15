<?php

namespace Mmeyer2k\RedisTree;

class RedisTreeModel
{
    public static function registerRoutes($prefix = 'redistree')
    {
        \Route::group(['prefix' => $prefix], function () {
            $controller = '\Mmeyer2k\RedisTree\RedisTreeController';
            \Route::get('/', "$controller@getIndex");
            \Route::get('about', "$controller@getAbout");
            \Route::get('stats', "$controller@getStatistics");
            \Route::get('options', "$controller@getOptions");
            \Route::post('delete-node', "$controller@postDeleteNode");
            \Route::post('delete-key', "$controller@postDeleteKey");
            \Route::post('write-key', "$controller@postWriteKey");
            \Route::post('set-option', "$controller@postOptionSet");
        });
    }

    public static function array2table($data)
    {
        $out = '<table class="table table-hover">';
        foreach ($data as $name => $value) {
            $out .= '<tr>';
            $out .= "<th>$name</th>";
            if (is_array($value)) {
                $out .= "<td>" . self::array2table($value) . "</td>";
            } else {
                $out .= "<td>$value</td>";
            }
            $out .= '</tr>';
        }

        return $out . '</table>';
    }

    public static function digestKeyspace($keys, $path)
    {
        $seps = RedisTreeModel::option('separators');
        $out = [];
        foreach ($keys as $key) {
            $key = substr($key, strlen($path));
            if (!\Str::contains($key, $seps)) {
                $out[] = $key;
                continue;
            }
            foreach ($seps as $separator) {
                $nodes = explode($separator, $key);

                if (!$nodes) {
                    continue;
                }

                // To make sure this isnt a sub-key with a differnt kind
                // of separator, we will test if the resulting relKey
                // has a separator value still, if so skip
                if (\Str::contains($nodes[0], $seps)) {
                    continue;
                }

                $out[] = $nodes[0] . $separator;
            }
        }

        sort($out);

        return array_unique($out);
    }

    public static function option($opt)
    {
        if (\Session::has('options')) {
            $opts = \Session::get('options');

            return $opts[$opt];
        }

        return \config("redistree.$opt");
    }

    public static function redisEscape($str)
    {
        foreach (['*', '\\'] as $char) {
            $str = str_replace($char, "\\{$char}", $str);
        }

        return $str;
    }

    public static function segments($path)
    {
        $separators = \config('redistree.separators');
        $segs = [];

        $seg = '';
        foreach (str_split($path) as $char) {
            $seg = $seg . $char;
            if (in_array($char, $separators)) {
                $segs[] = $seg;
                $seg = '';
            }
        }

        return $segs;
    }
}
