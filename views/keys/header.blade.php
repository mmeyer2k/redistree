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
@if ($size / $take > 1)
    <div class="panel-heading">
        <a data-toggle="tooltip"
           data-placement="bottom"
           title="Go to first page of keys"
           href="?node={!! request('node') !!}&page=0"
           @if (request('page') == 0)
           disabled="disabled"
           @endif
           class="btn btn-default btn-sm">
            <span id="btnRefreshIcon" class="glyphicon glyphicon-fast-backward"></span>
        </a>
        <a data-toggle="tooltip"
           data-placement="bottom"
           title="Go to previous page of keys"
           @if (request('page') == 0)
           disabled="disabled"
           @endif
           href="?node={!! request('node') !!}&page={!! request('page') - 1 !!}"
           class="btn btn-default btn-sm">
            <span id="btnRefreshIcon" class="glyphicon glyphicon-backward"></span>
        </a>
        <select class="input-sm" style="width: 70px;" id="pagechange">
            @foreach(range(0, floor($size / $take)) as $page)
                <option value="{!! $page !!}" {!! request('page') == $page ? 'selected="selected"' : '' !!}>
                    {!! $page !!}
                </option>
            @endforeach
        </select>
        <a data-toggle="tooltip"
           data-placement="bottom"
           title="Go to next page of keys"
           @if (request('page') == floor($size / $take))
           disabled="disabled"
           @endif
           href="?node={!! request('node') !!}&page={!! request('page') + 1 !!}"
           class="btn btn-default btn-sm">
            <span id="btnRefreshIcon" class="glyphicon glyphicon-forward"></span>
        </a>
        <a data-toggle="tooltip"
           data-placement="bottom"
           title="Go to last page of keys"
           @if (request('page') == floor($size / $take))
           disabled="disabled"
           @endif
           href="?node={!! request('node') !!}&page={!! floor($size / $take) !!}"
           class="btn btn-default btn-sm">
            <span id="btnRefreshIcon" class="glyphicon glyphicon-fast-forward"></span>
        </a>
        <select class="input-sm" style="width: 120px; float: right;" id="takechange">
            @foreach([25, 50, 100, 250, 500, 1000] as $i)
                <option value="{!! $i !!}" {!! $take == $i ? 'selected="selected"' : '' !!}>
                    {!! $i !!} per page
                </option>
            @endforeach
        </select>
    </div>
    <script>
        $("#takechange").change(function () {
            ajaxOptionSet('pagination', $(this).val());
            location.reload(true);
        });
        $("#pagechange").change(function () {
            window.location.href='?node={!! request('node') !!}&page=' + $(this).val();
        });
    </script>
@endif