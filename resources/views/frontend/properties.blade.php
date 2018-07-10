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

                            <a target="_blank" href="{{ route('frontend.page', array_merge(['pageSlug' => 'properties', 'locale' => App::getLocale(), 'pdf' => 1], request()->all())) }}" class="btn btn-sm mt-sm-4 ml-2">
                                {{ __('Print Results') }}
                            </a>
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
                    @include('frontend.partials.property', compact('property'))
                <?php $perRowCount++; ?>
                @if ($loop->last || $perRowCount == $perRow)
                <?php $perRowCount = 0; ?>
                </div>
                @endif
            @endforeach

            {{ $properties->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
