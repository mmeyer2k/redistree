@extends('redistree::layout')

@section('title') RedisTree - key: {{ $key }} @endsection

@section('content')
    <div class="panel panel-default">
        <h1 class="monospace" style="margin-left: 8px;">{{ $key }}</h1>
        <hr>
        <div>
            KEY NOT FOUND
        </div>
    </div>
@endsection
