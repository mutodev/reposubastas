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
            margin: 0 20px 10px 0;
        }

        .property-footer {
            position: absolute;
            top: 205px;
            left: 10px;
            right: 10px;
            border-top: 2px solid white;
            font-size: 10px;
        }

        .badge {
            font-size: 12px;
        }

        .text-mutted {
            line-height: 12px;
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
            top: 6px;
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
            top: 100px;
            font-size: 14px;
            height: 20px;
            z-index: 100;
            background: rgba(255, 255, 255, 0.8);
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
            background: #33d1ea
        }

        .properties-index tbody tr td {
            padding: 10px 5px;
        }

        .text-center {
            text-align: center;
        }

        .card-img-top {
            border-top-left-radius: 4px !important;
            border-top-right-radius: 4px !important;
        }

        .adminText {
            font-size: 10px;
        }
    </style>

    <?php $propertiesByNumber->chunk(6, function($properties) { ?>
        <?php
            $count = 1;
            $row = 0;
            $admin = request('admin');
            $columns = request('columns', 2)
        ?>
        <div class="properties">
        <?php foreach($properties as $property): ?>
            <div class="property @if($count == $columns) mx-0 @endif">
                <img class="card-img-top" height="120" src="{{ $property->getMainImage('_thumb') }}" alt="{{ $property->address }}">
                <div class="property-city text-center">
                    {{ $property->city }}
                </div>
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
                    <h5 class="card-title"><strong>{{ $property->address }}</strong></h5>

                    <table class="text-mutted property-details" width="100%">
                        <tr>
                            <td valign="top">
                                @if (!$admin)
                                    <strong>{{ __('Type') }}:</strong> {{ $property->type->name_es }}<br />
                                    @if ((int)$property->sqm_area)
                                        <strong>{{ __('M2') }}:</strong> {{ round($property->sqm_area, 2) }}<br />
                                    @endif
                                    @if ((int)$property->sqf_area)
                                        <strong>{{ __('F2') }}:</strong> {{ round($property->sqf_area, 2) }}<br />
                                    @endif
                                    @if ((int)$property->cuerdas)
                                        <strong>{{ __('Cuerdas') }}:</strong> {{ round($property->cuerdas, 2) }}
                                    @endif
                                @else
                                    <span class="adminText">
                                        <strong>{{ __('Reserve') }}:</strong> ${{ number_format($property->reserve) }}<br />
                                        @if ($property->investor_id)
                                            <strong>{{ __('Investor') }}:</strong> {{ $property->investor->name }} (#{{ $property->investor_reference_id }})
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td valign="top">
                                @if (!$admin)
                                    @if($property->bedrooms)
                                        <br/><strong>{{ __('Beds') }}:</strong> {{ number_format($property->bedrooms) }}
                                    @endif
                                    @if($property->bathrooms)
                                        <br /><strong>{{ __('Baths') }}:</strong> {{ number_format($property->bathrooms) }}
                                    @endif
                                @else
                                    <span class="adminText"><strong>{{ __('Last Offer') }}:</strong> ${{ number_format(@$property->getBids($property->event_id)[0]['offer']) }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <strong>{{ __('Commission to be paid by the Buyer') }}</strong> @if($property->buyer_prima){{ number_format(intval($property->buyer_prima)) }}%@else 1%@endif
                </div>
            </div>
            @if ($count == $columns)
                <?php $count = 0; ?>
                <div style="clear: both"></div>
            @endif
            <?php $count++; ?>
        <?php endforeach; ?>
        </div>
        <div style="page-break-after: always;"></div>
    <?php }); ?>

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
            @foreach($propertiesByCity->get() as $property)
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
                    @if($property->price > 0) ${{ number_format($property->price) }} @else {{ __('Request price') }} @endif
                </td>
                <td>
                    @if($property->deposit)${{ number_format($property->deposit) }}@endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
