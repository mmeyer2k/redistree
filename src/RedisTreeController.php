<?php

namespace Mmeyer2k\RedisTree;

use App\Http\Controllers\Controller;
use Mmeyer2k\RedisTree\RedisTreeModel;
use Predis\Collection\Iterator\Keyspace;

class RedisTreeController extends Controller
{

    const session = 'redistree';

    public function getAbout()
    {
        return \view('redistree::about');
    }

    public function getIndex()
    {
        // Find filter path and decode it
        $path = urldecode(request('node') ?? '');

        // Create escaped redis search string
        $escaped = RedisTreeModel::redisEscape($path);

        // Pull keys from redis matching search
        $c = \Redis::connection()->client();
        $k = new Keyspace($c, "$escaped*");
        $keys = [];
        foreach ($k as $key) {
            $keys[] = $key;
        }

        // Sort the keys
        sort($keys);
        
        $data = RedisTreeModel::digestKeyspace($keys, $path);

        return \view('redistree::keys.index', [
            'keys' => $keys,
            'path' => $path,
            'data' => $data,
            'dirs' => RedisTreeModel::option('separators'),
            'segs' => RedisTreeModel::segments($path),
        ]);
    }

    public function getOptions()
    {
        if (request()->method() === 'POST') {

        }

        return \view('redistree::options');
    }

    public function getStatistics()
    {
        $info = \Redis::info();

        return \view('redistree::statistics', [
            'info' => $info,
        ]);
    }

    public function postDeleteKey()
    {
        $key = \Request::input('key');
        \Redis::del($key);
    }

    public function postDeleteNode()
    {
        $node = request('node');

        $c = \Redis::connection()->client();
        $keys = new Keyspace($c, "*");

        foreach ($keys as $key) {
            if (starts_with($key, $node)) {
                \Redis::del($key);
            }
        }
    }

    public function postOptions()
    {
        $opts = request('opts');

        if (!isset($opts['separators'])) {
            $opts['separators'] = [];
        }

        session()->put(self::session, $opts);
        session()->put('optionsSaved', true);

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
