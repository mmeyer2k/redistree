<?php
$links = [
    'Statistics' => [
        route('mmeyer2k.redistree.stats'),
        '<i class="fa fa-line-chart"></i>',
    ],
    'Options' => [
        route('mmeyer2k.redistree.options'),
        '<i class="fa fa-cogs"></i>',
    ],
    'About RedisTree' => [
        route('mmeyer2k.redistree.about'),
        '<i class="fa fa-info-circle"></i>',
    ],
];
?>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button"
                    class="navbar-toggle collapsed"
                    data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{!! route('mmeyer2k.redistree.index') !!}">
                <span class="glyphicon glyphicon-tree-deciduous" aria-hidden="true"></span>
                RedisTree
            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @foreach($links as $name => $link)
                    @php
                    $active = $link[0] === request()->url();
                    @endphp
                    <li class="{{ $active ? 'active' : '' }}">
                        <a href="{!! $link[0] !!}">
                            {!! $link[1] ?? '' !!}
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>      
