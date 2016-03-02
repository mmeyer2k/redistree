<?php

use Mmeyer2k\RedisTree\RedisTreeModel;
?>
@extends('redistree::layout')

@section('title') RedisTree - Options @endsection

@section('content')
<form id="formOptions">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="fa fa-cogs"></i>
                Options
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <input type="hidden" name="opts[danger_prompt]" value="0">
                    <input name="opts[danger_prompt]"
                           value="1"
                           type="checkbox"                            
                           <?php
                           if (RedisTreeModel::option('danger_prompt')) {
                               echo 'CHECKED';
                           }
                           ?>
                           data-reverse>
                </div>
                <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
                    Prompt user for confirmation before performing dangerous actions?
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <input type="hidden" name="opts[view_keys_only]" value="0">
                    <input name="opts[view_keys_only]"
                           value="1"
                           type="checkbox"                            
                           <?php
                           if (RedisTreeModel::option('view_keys_only')) {
                               echo 'CHECKED';
                           }
                           ?>
                           data-reverse>
                </div>
                <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
                    Speed up navigating through the key structure by not loading each key's data.
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <input type="hidden" name="opts[tooltips]" value="0">
                    <input name="opts[tooltips]"
                           value="1"
                           type="checkbox"                            
                           <?php
                           if (RedisTreeModel::option('tooltips')) {
                               echo 'CHECKED';
                           }
                           ?>
                           data-reverse>
                </div>
                <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
                    Enable interface tool tips.
                </div>
            </div>
        </div>
        <div class="panel-heading">
            <h3>
                <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>
                Keyspace Separators
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                @foreach(\config('redistree.separators') as $sep)
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <button type="button" class="btn btn-default toggleSep">
                        {{ $sep }}
                    </button>
                    <input name="opts[separators][]"
                           value="{{ $sep }}"
                           type="checkbox"                            
                           <?php
                           if (in_array($sep, RedisTreeModel::option('separators'))) {
                               echo 'CHECKED';
                           }
                           ?>
                           data-reverse>
                </div>
                @endforeach
            </div>
        </div>
        <div class="panel-footer">
            <button type="button" class="btn btn-default btn-primary">
                Save
            </button>
            @if (\Session::pull('optionsSaved'))
            <span id="spanSaved" style="margin-left: 4px;" class="label label-success">
                <span class="glyphicon glyphicon-saved" aria-hidden="true"></span>
                Saved
            </span>
            <script>
                $(document).ready(function () {
                    setTimeout(function () {
                        $('#spanSaved').fadeOut('slow');
                    }, 2000);
                });
            </script>
            @endif
        </div>
    </div>
</form>
@endsection

@section('head')
<script src="//cdn.rawgit.com/vsn4ik/bootstrap-checkbox/master/js/bootstrap-checkbox.js"></script>
<style>
    .col-xs-12 {
        text-align: center;
        margin-bottom: 30px;
    }
    .col-xs-12 > button {
        background-color: #eeeeee;
        font-weight: bolder;
        font-family: monospace;
        font-size: 14px;
        color: #303030;
    }
</style>
@endsection

@section('tail')
<script>
                $(document).ready(function () {
                    $(':checkbox').checkboxpicker();
                    $('.panel-footer > button').click(function () {
                        sendAjax('{!! $_SERVER["REQUEST_URI"] !!}', $('#formOptions').serialize());
                    });
                    $(".toggleSep").click(function () {
                        var v = $(this).next().prop('checked');
                        $(this).next().prop('checked', !v);
                    });
                });
</script>
@endsection