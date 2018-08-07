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
    </style>
@endsection

@section('main')
    <div class="m-5">
        <live-component :auction="auction"></live-component>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('js/app.js', false) }}" defer></script>
@endsection
