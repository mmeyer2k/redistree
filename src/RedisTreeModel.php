<?php

namespace Mmeyer2k\RedisTree;

class RedisTreeModel
{
    public static function registerRoutes(string $prefix = 'redistree'): void
    {
        \Route::group(['prefix' => $prefix], function () {
            \Route::get('/', [RedisTreeController::class, "getIndex"])->name('mmeyer2k.redistree.index');
            \Route::get('about', [RedisTreeController::class, "getAbout"]);
            \Route::get('stats', [RedisTreeController::class, "getStatistics"]);
            \Route::get('options', [RedisTreeController::class, "getOptions"])->name('mmeyer2k.redistree.options');
            \Route::post('delete-node', [RedisTreeController::class, "postDeleteNode"]);
            \Route::post('delete-key', [RedisTreeController::class, "postDeleteKey"]);
            \Route::post('write-key', [RedisTreeController::class, "postWriteKey"]);
            \Route::post('options', [RedisTreeController::class, "postOptions"]);
            \Route::post('set-option', [RedisTreeController::class, "postOptionSet"])->name('mmeyer2k.redistree.option');
        });
    }

    public static function array2table(array $data): string
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

    public static function digestKeyspace($keys, string $path)
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

    public static function option(string $opt)
    {
        $ses = RedisTreeController::session;

        if (session()->has($ses)) {
            $opts = session()->get($ses);

            return $opts[$opt];
        }

        return config("$ses.$opt");
    }

    public static function redisEscape(string $str): string
    {
        foreach (['*', '\\'] as $char) {
            $str = str_replace($char, "\\{$char}", $str);
        }

        return $str;
    }

    public static function segments(string $path): array
    {
        $separators = config('redistree.separators');
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
