<?php

use Mmeyer2k\RedisTree\RedisTreeModel;

$controller = '\Mmeyer2k\RedisTree\RedisTreeController';
?>
@extends('redistree::layout')

@section('title') RedisTree{{ strlen($path) ? ' - ' . $path  : '' }} @endsection

@section('content')
<div class="panel panel-default">
    <?php
    echo \view('redistree::keys.header', ['segs' => $segs, 'path' => $path]);
    ?>
    <div id="divRowData" class="panel-body">
        {{-- List folders at this node --}}
        <?php
        foreach ($data as $node) {

            if (!ends_with($node, $dirs)) {
                continue;
            }

            $nodeLink = '?node=' . bin2hex($path . $node);
            ?>
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a href="{!! $nodeLink !!}" class="btn btn-default monospace">
                        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>
                        {{ $node }}
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    @if (!RedisTreeModel::option('view_keys_only'))
                    <textarea
                        placeholder="Node value"
                        style="resize: vertical; height: 53px;"
                        class="form-control monospace">{{ \Redis::get($path . $node) }}</textarea>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 text-right">
                    @if (!RedisTreeModel::option('view_keys_only'))
                    <button 
                        onclick="ajaxUpdate($(this).attr('data-node'), $(this).parent().parent().find('textarea').val())"
                        data-node="{{ $path . $node }}"
                        class="btn btn-default">
                        <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                    </button>
                    @endif
                    <button 
                        onclick="ajaxDeleteNode($(this).attr('data-node'))"
                        data-node="{{ $path . $node }}"
                        class="btn btn-danger">
                        <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            <?php
        }
        foreach ($data as $key) {
            if (ends_with($key, $dirs)) {
                continue;
            }
            ?>
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 monospace divKeyNameCell">
                    {{ $key }}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    @if (!RedisTreeModel::option('view_keys_only'))
                    <textarea
                        placeholder="Key value"
                        style="resize: vertical; height: 53px;"
                        class="form-control monospace">{{ \Redis::get($path . $key) }}</textarea>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 text-right">
                    @if (!RedisTreeModel::option('view_keys_only'))
                    <button 
                        onclick="ajaxUpdate($(this).attr('data-key'), $(this).parent().parent().find('textarea').val())"
                        class="btn btn-default rowBtnSave"
                        data-key="{{ $path . $key }}">
                        <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                    </button>
                    @endif
                    <button 
                        onclick="ajaxDeleteKey($(this).attr('data-key'))"
                        class="btn btn-danger"
                        data-key="{{ $path . $key }}" >
                        <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            <?php
        }
        if (count($data) === 0) {
            ?>
            <h3>
                <span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>
                This keyspace is empty!
            </h3>
            <h4 style="padding-left: 35px;">
                Items can be added below.
            </h4>
            <?php
        }
        ?>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <input 
                    class="form-control input-lg monospace"
                    placeholder="Key (required)"
                    type="text">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <textarea 
                    class="form-control monospace"
                    style="resize: vertical; white-space: nowrap; height: 53px;"
                    placeholder="Value"></textarea>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 text-right">
                <button class="btn btn-defaul btn-primary btn-lg" disabled>
                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('head')
<style>
    @media (max-width: 768px) {
        div.col-xs-12  {
            text-align: left !important;
        }
    }
    .row:nth-of-type(odd) {
        background: #f9f9f9;
    }
    #divRowData {
        padding-top: 0px;
        padding-bottom: 0px;
    }
    #divRowData > div.row {
        padding-top: 6px;
        padding-bottom: 6px;
    }
    .divKeyNameCell {
        font-size: 18px;
        word-wrap: break-word;
    }
</style>
@endsection

@section('tail')
<script>

    var dangerPrompt = <?php echo (int) RedisTreeModel::option('danger_prompt') ?>;

    function ajaxDeleteNode(node) {
        if (dangerPrompt === 1) {
            if (!confirm('Are you sure you want to delete this node?\n\n' + node)) {
                return false;
            }
        }
        var data = 'node=' + encodeURIComponent(node);
        sendAjax('{!! \action("$controller@postDeleteNode") !!}', data);
    }

    function ajaxDeleteKey(key) {
        if (dangerPrompt === 1) {
            if (!confirm('Are you sure you want to delete this key?\n\n' + key)) {
                return false;
            }
        }
        var data = 'key=' + encodeURIComponent(key);
        sendAjax('{!! \action("$controller@postDeleteKey") !!}', data);
    }

    function ajaxUpdate(key, val) {
        if (dangerPrompt === 1) {
            if (!confirm('Are you sure you want to add/update this key?\n\n' + key)) {
                return false;
            }
        }
        var data = 'key=' + encodeURIComponent(key) + '&val=' + encodeURIComponent(val);
        sendAjax('{!! \action("$controller@postWriteKey") !!}', data);
    }

    function ajaxOptionSet(opt, val) {
        var data = 'opt=' + encodeURIComponent(opt) + '&val=' + encodeURIComponent(val);
        sendAjax('{!! \action("$controller@postOptionSet") !!}', data);
    }

    $(document).ready(function () {
        $('.panel-footer button').click(function () {
            var base = '{{ addslashes($path) }}';
            ajaxUpdate(base + $('.panel-footer input').val(), $('.panel-footer textarea').val());
        });
        $('.panel-footer input').on('keyup change blur', function () {
            var disabled = $(this).val().length === 0;
            $('.panel-footer button').prop('disabled', disabled);
        });
    });
</script>
@endsection