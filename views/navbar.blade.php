<?php
$controller = '\Mmeyer2k\RedisTree\RedisTreeController';
$links = [
    'Statistics' => [
        \action("$controller@getStatistics"),
        '<i class="fa fa-line-chart"></i>',
    ],
    'Options' => [
        \action("$controller@getOptions"),
        '<i class="fa fa-cogs"></i>',
    ],
    'About RedisTree' => [
        \action("$controller@getAbout"),
        '<i class="fa fa-info-circle"></i>',
    ],
];
?>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{!! \action('\Mmeyer2k\RedisTree\RedisTreeController@getIndex') !!}">
                <span class="glyphicon glyphicon-tree-deciduous" aria-hidden="true"></span>
                RedisTree
            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @foreach($links as $name => $link)
                <?php
                $active = $link[0] === \Request::url();
                ?>
                <li class="{{ $active ? 'active' : '' }}">
                    <a href="{!! $link[0] !!}">
                        {!! $link[1] or '' !!}
                        {{ $name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>      
