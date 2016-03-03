<?php

namespace Mmeyer2k\RedisTree;

use Mmeyer2k\RedisTree\RedisTreeModel;

class RedisTreeController extends \App\Http\Controllers\Controller
{

    public function getAbout()
    {
        return \view('redistree::about');
    }

    public function getIndex()
    {
        // Find filter path and decode it
        $path = \Input::get('node');
        
        if ($path) {
            $path = hex2bin($path);
        }

        // Create escaped redis search string
        $escaped = str_replace('*', '\\*', $path);

        // Pull keys from redis matching search
        $keys = \Redis::keys("$escaped*");

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
        $key = \Input::get('key');
        \Redis::del($key);
    }

    public function postDeleteNode()
    {
        $node = \Input::get('node');
        foreach (\Redis::keys('*') as $key) {
            if (starts_with($key, $node)) {
                \Redis::del($key);
            }
        }
    }

    public function postOptions()
    {
        $opts = \Input::get('opts');

        if (!isset($opts['separators'])) {
            $opts['separators'] = [];
        }

        \Session::put('options', $opts);
        \Session::put('optionsSaved', true);
    }

    public function postOptionSet()
    {
        $opt = \Input::get('opt');
        $val = \Input::get('val');
        $ext = \Session::get('options');
        if (!is_array($ext)) {
            $ext = \config('redistree');
        }
        $ext[$opt] = $val;
        \Session::put('options', $ext);
    }

    public function postWriteKey()
    {
        $key = \Input::get('key');
        $val = \Input::get('val');

        \Redis::set($key, $val);
    }

}