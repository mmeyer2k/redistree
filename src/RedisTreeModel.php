<?php

declare(strict_types=1);

namespace Mmeyer2k\RedisTree;

class RedisTreeModel
{
    public static function registerRoutes(string $prefix = 'redistree'): void
    {
        \Route::group(['prefix' => $prefix], function () {
            \Route::get('/', [RedisTreeController::class, "getIndex"])->name('mmeyer2k.redistree.index');
            \Route::get('about', [RedisTreeController::class, "getAbout"])->name('mmeyer2k.redistree.about');
            \Route::get('stats', [RedisTreeController::class, "getStatistics"])->name('mmeyer2k.redistree.stats');
            \Route::get('favicon', [RedisTreeController::class, "getFavicon"])->name('mmeyer2k.redistree.favicon');
            \Route::get('options', [RedisTreeController::class, "getOptions"])->name('mmeyer2k.redistree.options');
            \Route::get('key/{key}', [RedisTreeController::class, "getKey"])->name('mmeyer2k.redistree.key');
            \Route::post('options', [RedisTreeController::class, "postOptions"]);
            \Route::post('set-option', [RedisTreeController::class, "postOptionSet"])->name('mmeyer2k.redistree.option');
            \Route::post('key/{key}/set', [RedisTreeController::class, "postWriteKey"])->name('mmeyer2k.redistree.key.set');;
            \Route::post('key/{key}/del', [RedisTreeController::class, "postDeleteKey"])->name('mmeyer2k.redistree.key.del');
        });
    }

    public static function keyNodeLinks(string $key): string
    {
        $out = '';

        $separators = self::option('separators');

        $segments = self::explodeKey($key, $separators);

        $node = '';

        $base = urlencode(request('node') ?? '');

        foreach($segments as $key => $segment) {
            $node .= urlencode($segment);

            if ($key === array_key_last($segments)) {
                $route = \route('mmeyer2k.redistree.key', [$base . $node]);

                $out .= "<a href=\"$route\">$segment</a>";
            } else {
                $out .= "<a href=\"?node=$base$node\">$segment</a>";
            }
        }

        return $out;
    }

    public static function explodeKey(string $key, array $separators): array
    {
        $out = [];

        $seg = '';

        $split = str_split($key);

        while ($split) {
            $chr = array_shift($split);

            $seg .= $chr;

            if (in_array($chr, $separators)) {
                $out[] = $seg;
                $seg = '';
            }

            if (count($split) === 0) {
                $out[] = $seg;
            }
        }

        return $out;
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

    public static function option(string $opt)
    {
        $key = RedisTreeController::session;

        return session($key)[$opt] ?? config("$key.$opt");
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
