@extends('layouts.base')

@section('stylesheets')
    <link href="/css/frontend.css?v5" rel="stylesheet">
    <style>
        .property-badges .badge {
            font-size: 2.5em;
            padding: 10px 15px;
        }
        #currentBid {
            font-size: 4em;
        }

        .auctionBackground .row {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            padding: 3rem!important;
        }

        .celebrate {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999999999;
            overflow: hidden;
        }

        .auctionBackground {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .backgroundRed {
            background: #ff244d;
        }

        .backgroundRed .bidingArea {
            color: white;
        }

        .celebrate img {
            width: 100%;
        }
    </style>
    <link href="https://vjs.zencdn.net/7.3.0/video-js.css" rel="stylesheet">
    <link href="https://unpkg.com/silvermine-videojs-quality-selector@1.1.2/dist/css/quality-selector.css" rel="stylesheet">
@endsection

@section('main')
    <div class="celebrate">
        <img src="">
    </div>
    <div class="background"></div>
    <div class="auctionBackground p-5">
        <live-component :auction="auction"></live-component>
    </div>
@endsection

@section('footer_scripts')
    <script src="/js/app.js?v16" defer></script>

    <script src="https://vjs.zencdn.net/7.3.0/video.js"></script>

    <script src="https://unpkg.com/silvermine-videojs-quality-selector/dist/js/silvermine-videojs-quality-selector.min.js"></script>
    <script type="text/javascript">
        setTimeout(function() {
          var player = videojs('videojs');
          console.log('Loaded');
        }, 5000)
    </script>
@endsection
