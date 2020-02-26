@extends('layouts.base')

@section('stylesheets')
    <link href="{{ asset('css/frontend.css', false) }}?v5" rel="stylesheet">
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
    <script src="/js/app.js?v15" defer></script>
@endsection
