@extends('redistree::layout')

@section('title') RedisTree - Statistics @endsection

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h2>
            <i class="fa fa-line-chart"></i>
            Statistics
        </h2>
    </div>
    @foreach($info as $section => $data)
    <div class="panel-heading">
        <h4>
            {{ $section }}
        </h4>
    </div>
    {!! \Mmeyer2k\RedisTree\RedisTreeModel::array2table($data) !!}
    @endforeach
</div>
@endsection