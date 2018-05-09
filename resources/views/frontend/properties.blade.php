@extends('frontend.base')

@section('sub_header')
    @include('frontend.partials.search')
@endsection

@section('content')
    {!! $page->content !!}

    <div class="properties-results bg-light-grey pt-4 pb-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="properties-filters">
                <form method="get" action="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}">
                    <input type="hidden" name="type" value="{{ request()->get('type') }}" />
                    <input type="hidden" name="keywords" value="{{ request()->get('keywords') }}" />
                    <div class="form-row">
                        <div class="float-left mb-3">
                            <strong class="text-dark-blue">{{ __('View') }}:</strong><br />

                            <select class="form-control" name="event_type">
                                @foreach(['' => __('All'), 'LIVE' => __('Only live auctions'), 'ONLINE' => __('Only online auctions')] as $value => $label)
                                    <option @if(request()->get('event_type') == $value) selected @endif value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="width: 215px" class="float-left mb-3 ml-sm-3">
                            <strong class="text-dark-blue">{{ __('Price range') }}:</strong><br />
                            <input style="width: 100px" type="number" class="form-control d-inline" name="price_min" id="price-min" value="{{ request()->get('price_min', 0) }}">
                            <label for="price-max">-</label>
                            <input style="width: 100px" type="number" class="form-control d-inline" name="price_max" id="price-max" value="{{ request()->get('price_min', 9999999) }}">
                        </div>
                        <div class="float-right ml-sm-3">
                            <button class="btn btn-sm bg-light-red mt-sm-4" type="submit">
                                {{ __('Filter Results') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if(!$properties->total())
                <div class="py-5">
                    <h2 class="text-dark-blue">{{ __('Sorry, no results were found') }}</h2>
                </div>
            @endif

            <?php
                $perRow = 3;
                $perRowCount = 0;
            ?>
            @foreach($properties as $property)
                @if ($loop->first || $perRowCount == 0)
                <div class="card-deck mt-2 mb-4">
                @endif
                    <a href="{{ route('frontend.page', ['pageSlug' => 'property', 'locale' => \App::getLocale(), 'id' => $property->id]) }}" class="card col-md-4 p-0 border-0">
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
                                {{ __('Live Auction') }}: {{ Jenssegers\Date\Date::parse($property->event_start_at)->format('j M, g:i a')}}
                            </li>
                            @else
                            <li class="list-group-item border-0 bg-light-blue">
                                {{ __('Online Auction') }}: {{ Jenssegers\Date\Date::parse($property->event_start_at)->format('j M, g:i a')}}
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
