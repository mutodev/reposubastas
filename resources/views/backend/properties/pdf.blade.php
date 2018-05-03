@extends('layouts.base')

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
            width: 370px;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
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
                <td style="height: 280px; border-bottom: 1px solid black;">
                    <div class="image-container">
                        <img src="https://s3.amazonaws.com/reposubastas/{{ $property->image1 }}" height="180" />
                        <div class="property-number">
                            {{ $property->number }}
                        </div>
                        <div class="property-city"></div>
                        <div class="property-city-text">{{ $property->city }}</div>
                    </div>
                    {{ $property->address }}
                    <table width="100%">
                        <tr>
                            <td valign="top">
                                <strong>{{ __('Tipo') }}:</strong> {{ $property->type->name_es }}
                            </td>
                            <td valign="top">
                                <strong>{{ __('Precio') }}:</strong> ${{ number_format($property->price) }}
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <strong>{{ __('Area') }}:</strong>
                                @if ((int)$property->sqm_area)
                                    <br />{{ number_format($property->sqm_area) }} {{ __('m2') }}
                                @endif
                                @if ((int)$property->sqf_area)
                                    <br />{{ number_format($property->sqf_area) }} {{ __('p2') }}
                                @endif
                                @if ((int)$property->cuerdas)
                                    <br />{{ number_format($property->cuerdas) }} {{ __('cuerdas') }}
                                @endif
                            </td>
                            <td valign="top">
                                <strong>{{ __('Inspección') }}:</strong><br />
                                {{ $property->open_house }}
                            </td>
                        </tr>
                    </table>
                </td>
            <?php $count++; $pageCount++; ?>
            @if ($loop->last || $count == 2)
            <?php $count = 0; ?>
            </tr>
            @endif

        @if ($loop->last || $pageCount == 6)
        <?php $pageCount = 0; ?>
        </table>
        @endif
    @endforeach

    <div style="page-break-after: always;"></div>

    <table width="100%">
        <tr>
            <th>
                {{ __('Ciudad') }}
            </th>
            <th>
                {{ __('Dirección') }}
            </th>
            <th>
                {{ __('Inspección') }}
            </th>
            <th>
                #
            </th>
        </tr>
        @foreach($propertiesByCity as $property)
        <tr>
            <td>
                {{ $property->city }}
            </td>
            <td>
                {{ $property->address }}
            </td>
            <td>
                {{ $property->open_house }}
            </td>
            <td>
                {{ $property->number }}
            </td>
        </tr>
        @endforeach
    </table>
@endsection
