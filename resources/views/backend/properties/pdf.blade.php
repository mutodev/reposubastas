@extends('layouts.base')

@section('stylesheets')
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet">
@endsection

@section('main')
    <style>
        .image-container {
            position: relative;
            height: 180px;
        }

        .property-number {
            border-radius: 15px;
            width: 30px;
            height: 30px;
            line-height: 25px;
            text-align: center;
            background: #ed1b24;
            color: white;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 200;
        }

        .property-city {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 25px;
            z-index: 100;
            opacity: 0.6;
            filter: alpha(opacity=60);
            background: white;
        }

        .property-city-text {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            text-align: center;
            z-index: 300;
            color: black;
            height: 25px;
        }

        .image-container img {
            max-width: none;
            width: 350px;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
        }

        .properties-index th {
            border-bottom: 2px solid black;
            padding: 2px 20px;
        }

        .properties-index tbody tr:nth-child(even) {
            background: #ddffda
        }

        .properties-index tbody tr td {
            padding: 10px 20px;
        }

        .text-center {
            text-align: center;
        }
    </style>

    <?php
        $count = 0;
        $pageCount = 0;
    ?>
    <?php
    $perRow = 3;
    $perRowCount = 0;
    ?>
    @foreach($propertiesByNumber as $property)
        @if ($loop->first || $perRowCount == 0)
            <div class="card-deck mt-2 mb-4">
                @endif
                @include('frontend.partials.property', compact('property'))
                <?php $perRowCount++; ?>
                @if ($loop->last || $perRowCount == $perRow)
                    <?php $perRowCount = 0; ?>
            </div>
        @endif
    @endforeach

    <div style="page-break-after: always;"></div>

    <table class="properties-index" width="485">
        <thead>
            <tr>
                <th class="text-center">
                    {{ __('City') }}
                </th>
                <th>
                    {{ __('Address') }}
                </th>
                <th class="text-center">
                    {{ __('Open House') }}
                </th>
                <th class="text-center">
                    #
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($propertiesByCity as $property)
            <tr>
                <td class="text-center" width="1">
                    {{ $property->city }}
                </td>
                <td>
                    {{ $property->address }}
                </td>
                <td class="text-center" width="1">
                    {{ $property->open_house }}
                </td>
                <td class="text-center" width="1">
                    {{ $property->number }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
