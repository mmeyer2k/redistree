<?php

namespace Mmeyer2k\RedisTree;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Predis\Collection\Iterator\Keyspace;

class RedisTreeController extends Controller
{
    const session = 'redistree';
    const updated = 'redistree:updated:flag';

    public function getAbout(): View
    {
        return view('redistree::about');
    }

    public function getIndex(): View
    {
        // Find filter path and decode it
        $path = urldecode(request('node') ?? '');

        // Create escaped redis search string
        $escaped = RedisTreeModel::redisEscape($path);

        // Get pagination params
        $page = request('page') ?? 0;

        // Create the keyspace iterator object
        $c = \Redis::connection()->client();
        $k = new Keyspace($c, "$escaped*");

        $keys = [];
        $size = -1;
        $take = RedisTreeModel::option('pagination');

        foreach ($k as $key) {
            $size++;

            if ($size < $page * $take) {
                continue;
            }

            if ($size > $page * $take + $take - 1) {
                continue;
            }

            $keys[] = $key;
        }

        view()->share([
            'size' => $size,
            'take' => $take,
        ]);

        return view('redistree::keys.index', [
            'page' => $page,
            'keys' => $keys,
            'path' => $path,
            'dirs' => RedisTreeModel::option('separators'),
            'segs' => RedisTreeModel::segments($path),
        ]);
    }

    public function getKey(string $key): View
    {
        $type = (string)\Redis::type($key);

        $len = [
            'string' => 'strlen',
            'list' => 'llen',
            'zset' => 'zcard',
            'set' => 'smembers',
        ][$type];

        if ($type === 'string') {
            $data = \Redis::get($key);
        } else {
            $data = '';
        }

        return view('redistree::key', [
            'key' => $key,
            'len' => $len,
            'ttl' => (int)\Redis::ttl($key),
            'type' => $type,
            'data' => $data,
        ]);
    }

    public function getOptions(): View
    {
        return view('redistree::options')->with([
            'updated' => session()->pull(self::updated),
        ]);
    }

    public function getStatistics(): View
    {
        $info = \Redis::info();

        return view('redistree::statistics', [
            'info' => $info,
        ]);
    }

    public function redis(): JsonResponse
    {
        $verb = request('verb');

        $resp = \Redis::$verb(request('param0'));

        return response()->json($resp);
    }

    public function postDeleteKey(string $key): void
    {
        \Redis::del($key);
    }

    public function postOptions(): RedirectResponse
    {
        $opts = request('opts');

        if (!isset($opts['separators'])) {
            $opts['separators'] = [];
        }

        session()->put(self::session, $opts);

        // Add update flag to session which will be pulled on next request
        session()->put(self::updated, true);

        return response()->redirectToRoute('mmeyer2k.redistree.options');
    }

    public function postOptionSet(): void
    {
        $opt = request('opt');
        $val = request('val');

        $ext = session()->get(self::session);

        // If the user has not changed any options yet, load the defaults from config
        if (!is_array($ext)) {
            $ext = config('redistree');
        }

        $ext[$opt] = $val;

        session()->put(self::session, $ext);
    }

    public function postWriteKey(string $key)
    {
        $val = request('value');

        \Redis::set($key, $val);

        return redirect()->route('mmeyer2k.redistree.key', urlencode($key));
    }

    public function favicon(): Response
    {
        $data = file_get_contents(__DIR__ . '/../favicon.ico');

        return response()->make($data, 200, [
            'Content-Type' => 'image/png',
        ]);
    }
}
