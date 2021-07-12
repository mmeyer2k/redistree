@extends('redistree::layout')

@section('title') RedisTree - About @endsection

@section('content')
    <div class="jumbotron">
        <div class="row">
            <div class="col-md-4 col-lg-3 text-center">
                <span class="glyphicon glyphicon-tree-deciduous" aria-hidden="true" style="font-size: 240px;"></span>
            </div>
            <div class="col-md-8 col-lg-9">
                <h1>RedisTree</h1>
                <p>
                    The naturally beautiful Redis web client.
                </p>
                <a
                        class="btn btn-primary btn-lg icon-github"
                        target="_blank"
                        href="https://github.com/mmeyer2k/redistree"
                        role="button">
                    <i class="fa fa-github"></i>
                    View RedisTree on GitHub
                </a>
            </div>
        </div>
    </div>
@endsection

@section('head')
    <style>
        @media (max-width: 992px) {
            div.row > div {
                text-align: center !important;
            }
        }
    </style>
@endsection