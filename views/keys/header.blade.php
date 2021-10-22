<?php

use Mmeyer2k\RedisTree\RedisTreeModel;
?>
<div class="panel-heading">
    <a data-toggle="tooltip"
       data-placement="bottom"
       href="{!! route('mmeyer2k.redistree.index') !!}"
       title="Return to root namespace"
       class="btn btn-default btn-sm">
        <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>
    </a>

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

    <form style="width: 140px; display: inline-block;">
        <input name="node" type="text" class="form-control input-sm" placeholder="Jump to namespace">
    </form>

    <div style="float: right;">
        @if ($option('danger_prompt'))
            <button
                    data-toggle="tooltip"
                    data-placement="bottom"
                    onclick="ajaxOptionSet('danger_prompt', 0)"
                    title="Do not prompt before performing dangrous actions"
                    class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
            </button>
        @else
            <button
                    data-toggle="tooltip"
                    data-placement="bottom"
                    onclick="ajaxOptionSet('danger_prompt', 1)"
                    title="Prompt before performing dangrous actions"
                    class="btn btn-sm">
                <span class="glyphicon glyphicon-alert text-danger" aria-hidden="true"></span>
            </button>
        @endif

        <button id="btnRefresh"
                data-toggle="tooltip"
                data-placement="bottom"
                title="Refresh current view"
                class="btn btn-default btn-sm">
            <span id="btnRefreshIcon" class="glyphicon glyphicon-refresh"></span>
        </button>
    </div>
</div>