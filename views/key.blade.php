@extends('redistree::layout')

@section('title') RedisTree - key: {{ $key }} @endsection

@section('head')
    <style>
        td {
            font-family: monospace;
        }
    </style>
    <script>
        $(document).ready(function () {
            $("#btn-save").click(function (event) {
                if (dangerPrompt === 1) {
                    if (!confirm('Are you sure you want to perform this action?')) {
                        event.preventDefault();
                    }
                }

                let url = @json(route('mmeyer2k.redistree.key.set', urlencode($key)));
                let val = $("#txt-value").val();

                sendAjax(url, {value: val});
            });
        });
    </script>
@endsection

@section('content')
    <div class="panel panel-default">
        <h1 class="monospace" style="margin-left: 8px;">{{ $key }}</h1>
        <hr>
        <table class="table">
            <tr>
                <th>Key name</th>
                <td>{{ $key }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ $type }}</td>
            </tr>
            <tr>
                <th>Size ({!! $len !!})</th>
                <td>{{ \Redis::$len($key) }}</td>
            </tr>
            <tr>
                <th>Time to live</th>
                <td>
                    {!! $ttl === -1 ? 'forever' : now()->addSeconds($ttl)->diffForHumans() !!}
                </td>
            </tr>
            <tr>
                <th>Content</th>
                <td>
                    <textarea
                            id="txt-value"
                            style="overflow: scroll; white-space: nowrap; width: 100%; height: 200px; resize: vertical;"
                            name="value"
                            class="monospace">{{ $data }}</textarea>
                    <button class="btn btn-primary" id="btn-save">
                        Save
                    </button>
                </td>
            </tr>
        </table>
    </div>
@endsection