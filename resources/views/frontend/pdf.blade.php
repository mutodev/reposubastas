@extends('layouts.base')

@section('main')
    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }

        .property {
            position: relative;
            min-width: 0;
            word-wrap: break-word;
            background-color: #E8E8E8;
            background-clip: border-box;
            border: 1px solid rgba(0,0,0,.125);
            border-radius: .25rem;
            width: 345px;
        }

        .property-footer {
            position: absolute;
            top: 375px;
            left: 20px;
            right: 20px;
            border-top: 2px solid white;
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
            top: 5px;
            left: 10px;
        }

        .text-mutted {
            font-size: 12px;
        }

        .card-body {
            padding: 15px;
            color: #3A3A3A;
        }

        .card-title {
            font-size: 19px;
            font-weight: 500;
            margin: 0;
            color: #3A3A3A;
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
    @foreach($propertiesByNumber as $property)
        @if ($pageCount == 0)
        @if (!$loop->first)
        <div style="page-break-after: always;"></div>
        @endif
        <table width="100%">
        @endif
            @if ($count == 0)
            <tr>
            @endif
                <td valign="top" style="height: 310px; @if($count == 0) padding: 0 20px 20px 0 @else padding-left: 0 0 20px 20px @endif">
                    <div class="property" style="height: 420px; overflow: hidden">
                        <img class="card-img-top" height="200" src="{{ $property->getImage() }}" alt="{{ $property->address }}">
                        <div class="property-badges">
                            @if($property->number)
                                <span class="badge badge-dark">{{ $property->number }}</span>
                            @else
                                <span class="badge badge-dark"><span class="oi oi-globe"></span></span>
                            @endif

                            @if($property->status_id)
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
                                        <strong>{{ __('Open house') }}:</strong> {{ $property->open_house }}
                                        @if($property->deposit)
                                            <br/><strong>{{ __('Deposit') }}:</strong> ${{ number_format($property->deposit) }}
                                        @endif
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
                </td>
            <?php $count++; $pageCount++; ?>
            @if ($loop->last || $count == 2)
            <?php $count = 0; ?>
            </tr>
            @endif

        @if ($loop->last || $pageCount == 4)
        <?php $pageCount = 0; ?>
        </table>
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
                <td class="text-center">
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
