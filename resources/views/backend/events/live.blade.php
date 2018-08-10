@extends('layouts.base')

@section('stylesheets')
    <link href="{{ asset('css/frontend.css', false) }}?v2" rel="stylesheet">
    <style>
        .property-badges .badge {
            font-size: 2.5em;
            padding: 10px 15px;
        }
        #currentBid {
            font-size: 4em;
        }

        .backgroundRed{
            background: #ff244d;
        }

        .auctionBackground .row {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            padding: 3rem!important;
        }
    </style>
@endsection

@section('main')
    <div class="auctionBackground p-5">
        <live-component :auction="auction"></live-component>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('js/app.js', false) }}?v2" defer></script>
@endsection
