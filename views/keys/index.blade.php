<?php

use Mmeyer2k\RedisTree\RedisTreeModel;

$controller = '\Mmeyer2k\RedisTree\RedisTreeController';
?>
@extends('redistree::layout')

@section('title') RedisTree{{ strlen($path) ? ' - ' . $path  : '' }} @endsection

@section('content')
    <div class="panel panel-default">
        {!! view('redistree::keys.header', ['segs' => $segs, 'path' => $path]) !!}
        <div id="divRowData" class="panel-body">
            @foreach ($keys as $key)
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 monospace divKeyNameCell">
                        {!! RedisTreeModel::keyNodeLinks(substr($key, strlen($path))) !!}
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 text-right">
                        <a href="{!! route('mmeyer2k.redistree.key', [urlencode($key)]) !!}" class="btn btn-default">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                        </a>
                        <button class="btn btn-danger"
                                data-key="{{ $key }}"
                                data-cmd="{{ route('mmeyer2k.redistree.key.del', urlencode($key)) }}">
                            <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            @endforeach
            @if (count($keys) === 0)
                <h3>
                    <span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>
                    This keyspace is empty!
                </h3>
                <h4 style="padding-left: 35px;">
                    Items can be added below.
                </h4>
            @endif
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <input class="form-control input-lg monospace"
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
            div.col-xs-12 {
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

        function ajaxOptionSet(opt, val) {
            var data = 'opt=' + encodeURIComponent(opt) + '&val=' + encodeURIComponent(val);
            sendAjax('{!! route('mmeyer2k.redistree.option') !!}', data);
        }

        $(document).ready(function () {
            $('.btn-danger').click(function () {
                if (dangerPrompt === 1) {
                    let key = $(this).attr('data-key');
                    if (!confirm('Are you sure you want to delete this key?\n\n' + key)) {
                        return false;
                    }
                }
                sendAjax($(this).attr('data-cmd'));
            });

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
