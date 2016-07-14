@extends('layouts.app')

@section('content')

    <div class="whitebox">

        <div style="margin: 0 16px;">
            <div class="pull-left">
                <a target="_blank" href="{{url('/')}}/annotate/{{$bookmark->id}}" class="btn btn-info">
                    <b class="glyphicon glyphicon-book"></b> Annotate Bookmarks
                </a>

            </div>

            <div class="pull-right">
                <div id="colors">
                    <strong>Colors:</strong>
                    <strong class="yellow">01+</strong>
                    <strong class="blue">25+</strong>
                    <strong class="green">50+</strong>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr>
        </div>

        @if(! count($folders))
            <div align="center" class="whitebox">
                <strong class="alert alert-info"><i class="glyphicon glyphicon-info-sign"></i> No bookmarks saved yet
                    :-(
                </strong>
            </div>
        @endif

        @foreach($folders as $folder)
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin: 13px 0;">
                <a href="{{url('/show_folder/' . $folder->id)}}">
                    <div class="pricing-table {{getFolderColor($folder)}}">
                        <div class="pt-header">
                            <div class="plan-pricing">
                                <div class="pricing">{{count($folder->bookmarks)}}</div>
                            </div>
                        </div>
                        <div class="pt-body">
                            <h4>{{$folder->name}}</h4>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection