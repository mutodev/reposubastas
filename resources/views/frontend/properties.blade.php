@extends('frontend.base')

@section('sub_header')
    <div class="properties-search position-relative overflow-hidden text-center bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-4 my-sm-5">
            <div class="bg-dark-blue p-2 p-sm-4 mx-auto rounded properties-search-box mw-75">
                <form method="get" action="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}">
                    <h1 class="display-5 font-weight-normal">{{ __('YOUR INTELLIGENT INVESTMENT BEGINS') }}</h1>
                    <div class="input-group mr-sm-2">
                        <select name="type" class="custom-select">
                            @foreach($types as $value => $label)
                                <option @if($value == request()->get('type')) selected @endif value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <input value="{{ request()->get('keywords') }}" name="keywords" type="text" class="form-control w-50" id="keywords" placeholder="{{ __('Address, city, Property ID') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary bg-light-red border-0">{{ __('Search') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {!! $page->content !!}

    <div class="properties-results bg-light-grey pt-4 pb-4">
        <div class="container">
            <?php
                $perRow = 3;
                $perRowCount = 0;
            ?>
            @foreach($properties as $property)
                @if ($loop->first || $perRowCount == 0)
                <div class="card-deck mt-4 mb-4">
                @endif
                    <a href="{{ route('frontend.page', ['pageSlug' => 'property', 'locale' => \App::getLocale(), 'id' => $property->id]) }}" class="card col-md-4 p-0 border-0">
                        <img class="card-img-top" height="180" src="{{ $property->getImage() }}" alt="{{ $property->address }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $property->address }}</h5>
                            <p class="card-text text-muted">
                                {{ __('Type') }}: {{ $property->type->name }}

                                @if($property->sqf_area)
                                    <br />{{ __('Square feets') }}: {{ number_format($property->sqf_area) }}
                                @endif
                                @if($property->sqm_area)
                                    <br />{{ __('Square meters') }}: {{ number_format($property->sqm_area) }}
                                @endif
                            </p>
                        </div>

                        <ul class="list-group list-group-flush">
                            @if($property->event_start_at)
                            <li class="list-group-item border-0 bg-dark-blue">
                                {{ __('Live Auction') }}: {{ \Carbon\Carbon::parse($property->event_start_at)->format('j M, g:i a')}}
                            </li>
                            @else
                            <li class="list-group-item border-0 bg-light-blue">
                                {{ __('Online Auction') }}: {{ \Carbon\Carbon::parse($property->event_start_at)->format('j M, g:i a')}}
                            </li>
                            @endif
                            <li class="list-group-item border-0">
                                <span>{{ __('Sale price') }}: ${{ number_format($property->price) }}</span>
                            </li>
                        </ul>
                    </a>
                <?php $perRowCount++; ?>
                @if ($loop->last || $perRowCount == $perRow)
                <?php $perRowCount = 0; ?>
                </div>
                @endif
            @endforeach

            {{ $properties->links() }}
        </div>
    </div>
@endsection
