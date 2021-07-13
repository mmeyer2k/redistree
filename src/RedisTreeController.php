<?php

namespace Mmeyer2k\RedisTree;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Predis\Collection\Iterator\Keyspace;

class RedisTreeController extends Controller
{
    const session = 'redistree';

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

        // Create the keyspace iterator object
        $c = \Redis::connection()->client();
        $k = new Keyspace($c, "$escaped*");

        $keys = [];

        foreach ($k as $key) {
            $keys[] = $key;
        }

        // Sort the keys
        sort($keys);

        $data = RedisTreeModel::digestKeyspace($keys, $path);

        return view('redistree::keys.index', [
            'keys' => $keys,
            'path' => $path,
            'data' => $data,
            'dirs' => RedisTreeModel::option('separators'),
            'segs' => RedisTreeModel::segments($path),
        ]);
    }

    public function getOptions(): View
    {
        return view('redistree::options');
    }

    public function getStatistics(): View
    {
        $info = \Redis::info();

        return view('redistree::statistics', [
            'info' => $info,
        ]);
    }

    public function postDeleteKey(): void
    {
        $key = request('key');
        \Redis::del($key);
    }

    public function postDeleteNode()
    {
        $node = RedisTreeModel::redisEscape(request('node'));

        $c = \Redis::connection()->client();
        $keys = new Keyspace($c, "$node*");

        foreach ($keys as $key) {
            \Redis::del($key);
        }
    }

    public function postOptions(): View
    {
        $opts = request('opts');

        if (!isset($opts['separators'])) {
            $opts['separators'] = [];
        }

        session()->put(self::session, $opts);

        return $this->getOptions();
    }

    public function postOptionSet()
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

    public function postWriteKey()
    {
        $key = request('key');
        $val = request('val');

        \Redis::set($key, $val);
    }
}
