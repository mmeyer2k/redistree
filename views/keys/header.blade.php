<?php

namespace Mmeyer2k\RedisTree;
?>
<div class="panel-heading">
    <a data-toggle="tooltip"
       data-placement="bottom"
       href="{!! route('mmeyer2k.redistree.index') !!}"
       title="Return to root namespace"
       class="btn btn-default btn-sm">
        <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>
    </a>
    &nbsp;
    @if ($path)
        @foreach($segs as $i => $seg)
            <?php
            $root = '';
            for ($x = 0; $x < $i; $x++) {
                $root .= $segs[$x];
            }
            ?>
            <a href="?node={!! urlencode($root . $seg) !!}" class="btn btn-default btn-sm monospace">{{ $seg }}</a>
        @endforeach
    @endif

    <div style="float: right;">
        @if (!RedisTreeModel::option('danger_prompt'))
            <button
                    data-toggle="tooltip"
                    data-placement="bottom"
                    onclick="ajaxOptionSet('danger_prompt', 1)"
                    title="Prompt before performing dangrous actions"
                    class="btn btn-warning btn-sm">
                <span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
            </button>
        @else
            <button
                    data-toggle="tooltip"
                    data-placement="bottom"
                    onclick="ajaxOptionSet('danger_prompt', 0)"
                    title="Do not prompt before performing dangrous actions"
                    class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
            </button>
        @endif

        @if (!RedisTreeModel::option('view_keys_only'))
            <button
                    data-toggle="tooltip"
                    data-placement="bottom"
                    onclick="ajaxOptionSet('view_keys_only', 1)"
                    title="View keys only"
                    class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </button>
        @else
            <button
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="View keys and data"
                    onclick="ajaxOptionSet('view_keys_only', 0)"
                    class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            </button>
        @endif

        <button
                id="btnRefresh"
                data-toggle="tooltip"
                data-placement="bottom"
                title="Refresh current view"
                class="btn btn-default btn-sm">
            <span id="btnRefreshIcon" class="glyphicon glyphicon-refresh"></span>
        </button>
    </div>
</div>