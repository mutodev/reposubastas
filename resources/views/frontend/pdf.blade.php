@extends('layouts.base')

@section('main')
    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 8px;
        }

        .property {
            position: relative;
            min-width: 0;
            word-wrap: break-word;
            background-color: #E8E8E8;
            background-clip: border-box;
            border: none;
            border-radius: .25rem;
            float: left;
            width: 209px;
            height: 230px;
            overflow: hidden;
            margin: 0 20px 0 0;
        }

        .property-footer {
            position: absolute;
            top: 205px;
            left: 10px;
            right: 10px;
            border-top: 2px solid white;
            font-size: 10px;
        }

        .text-mutted {
            line-height: 8px;
        }

        .card-img-top {
            width: 100%;
            border-top-left-radius: calc(.25rem - 1px);
            border-top-right-radius: calc(.25rem - 1px);
            vertical-align: middle;
            border-style: none;
        }

        .property-badges {
            position: absolute;
            top: 10px;
            left: 5px;
        }

        .card-body {
            padding: 9px;
            color: #3A3A3A;
        }

        .card-title {
            font-size: 10px;
            font-weight: 400;
            margin: 0;
            color: #3A3A3A;
        }

        .properties {
            padding-top: 5px;
        }

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
            padding: 2px 5px;
        }

        .properties-index tbody tr:nth-child(even) {
            background: #ddffda
        }

        .properties-index tbody tr td {
            padding: 10px 5px;
        }

        .text-center {
            text-align: center;
        }
    </style>

    <?php
        $count = 0;
        $pageCount = 0;
        $row = 0;
    ?>
    @foreach($propertiesByNumber as $property)
        @if ($pageCount == 0 && !$loop->first)
        <div style="page-break-after: always;"></div>
        @endif
        @if($count == 0)
        <div class="properties">
        @endif
            <div class="property">
                <img class="card-img-top" height="120" src="{{ $property->getImage() }}" alt="{{ $property->address }}">
                <div class="property-badges">
                    @if($property->number)
                        <span class="badge badge-dark">{{ $property->number }}</span>
                    @endif

                    @if($property->status_id && $property->status->is_public)
                        <span class="badge badge-danger">{{ $property->status->name }}</span>
                    @endif
                </div>
                <div class="property-footer">
                    <strong>{{ __('Sale price') }}:</strong> ${{ number_format($property->price) }}
                </div>
                <div class="card-body">
                    <h5 class="card-title"><strong>{{ $property->address }}, {{ $property->city }}</strong></h5>

                    <table class="text-mutted" width="100%">
                        <tr>
                            <td valign="top">
                                <strong>{{ __('Type') }}:</strong> {{ $property->type->name_es }}<br />
                                @if ((int)$property->sqm_area)
                                    <strong>{{ __('M2') }}:</strong> {{ number_format($property->sqm_area) }}<br />
                                @endif
                                @if ((int)$property->sqf_area)
                                    <strong>{{ __('F2') }}:</strong> {{ number_format($property->sqf_area) }}<br />
                                @endif
                                @if ((int)$property->cuerdas)
                                    <strong>{{ __('Cuerdas') }}:</strong> {{ number_format($property->cuerdas) }}
                                @endif
                            </td>
                            <td valign="top">
                                @if($property->bedrooms)
                                    <br/><strong>{{ __('Beds') }}:</strong> {{ number_format($property->bedrooms) }}
                                @endif
                                @if($property->bathrooms)
                                    <br /><strong>{{ __('Baths') }}:</strong> {{ number_format($property->bathrooms) }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php $count++; $pageCount++; ?>
        @if ($loop->last || $count == 2)
        <?php $count = 0; $row++; ?>
            <div style="clear: both"></div>
        </div>
        @endif

        @if ($loop->last || $pageCount == 6)
        <?php $pageCount = 0; $row = 0; ?>
        </div>
        @endif
    @endforeach

    <div style="page-break-after: always;"></div>

    <table class="properties-index" width="100%">
        <thead>
            <tr>
                <th class="text-center">
                    #
                </th>
                <th class="text-center">
                    {{ __('City') }}
                </th>
                <th>
                    {{ __('Address') }}
                </th>
                <th class="text-center">
                    {{ __('Open House') }}
                </th>
                <th>
                    {{ __('Price') }}
                </th>
                <th>
                    {{ __('Deposit') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($propertiesByCity as $property)
            <tr>
                <td class="text-center" width="1">
                    {{ $property->number }}
                </td>
                <td class="text-center">
                    {{ $property->city }}
                </td>
                <td>
                    {{ $property->address }}
                </td>
                <td class="text-center">
                    {{ $property->open_house }}
                </td>
                <td>
                    ${{ number_format($property->price) }}
                </td>
                <td>
                    @if($property->deposit)${{ number_format($property->deposit) }}@endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection