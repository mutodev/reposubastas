@extends('frontend.base')

@section('page_title', "{$property->address}, {$property->city} {$property->region}")

@section('sub_header')
    <div class="properties-search position-relative overflow-hidden text-center bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-5">
            <div class="bg-dark-blue p-4 w-75 mx-auto rounded properties-search-box">
                <form method="get" action="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}">
                    <h1 class="display-5 font-weight-normal">{{ __('YOUR INTELLIGENT INVESTMENT BEGINS') }}</h1>
                    <div class="input-group mb-2 mr-sm-2">
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

    <div class="property pt-4 pb-4">
        <div class="container">
            <strong class="text-dark-blue">{{ __('Property') }}</strong>
            <h2 class="m-0">{{ $property->address }}</h2>
            <p class="text-muted">{{ $property->city }}</p>

            <div class="container">
                <div class="row">
                    <div class="col-md-8 p-0">
                        <div id="gallery" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach(range(1, 10) as $index)
                                @if(!($image = $property->getImage($index)))
                                    @continue
                                @endif
                                <li data-target="#gallery" data-slide-to="{{ $index - 1 }}" class="@if($index == 1) active @endif"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach(range(1, 10) as $index)
                                @if(!($image = $property->getImage($index)))
                                    @continue
                                @endif
                                <div class="carousel-item @if($index == 1) active @endif">
                                    <img class="d-block w-100" src="{{ $image }}" alt="First slide">
                                </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#gallery" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#gallery" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <div class="col p-0 @if($online) pl-sm-5 pr-sm-5 pt-sm-5 @else pl-sm-5 @endif">
                        @if(!$online)
                            @include('frontend.partials.details', compact('property', 'online'))
                        @else
                            <strong class="text-dark-blue">{{ __('Event ends in') }}:</strong>
                            <?php
                                $endAt = $property->end_at ?: $property->event_end_at;
                                $endAt = new Carbon\Carbon($endAt);
                                $days = $endAt->diffInDays();
                                $hours = $endAt->diffInHours() - ($days * 24);
                                $minutes = $endAt->diffInMinutes() - ((($days * 24) + $hours) * 60);
                            ?>

                            @if($days || $hours || $minutes)
                                <div class="property-remaining">
                                    @if($days)
                                        <strong class="unit">{{number_format($days)}}</strong>
                                        <span>d</span>
                                    @endif
                                    @if($hours)
                                        @if($days)
                                            <strong class="unit">:</strong>
                                        @endif
                                        <strong class="unit">{{number_format($hours)}}</strong>
                                        <span>h</span>
                                    @endif
                                    @if($minutes)
                                        @if($hours)
                                            <strong class="unit">:</strong>
                                        @endif
                                        <strong class="unit">{{number_format($minutes)}}</strong>
                                        <span>m</span>
                                    @endif
                                </div>
                            @endif

                            <div class="price mt-3">
                                <strong class="text-dark-blue">{{ __('Sale price') }}</strong>
                                <br />
                                <strong class="unit">${{ number_format($property->price) }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($online)
                @include('frontend.partials.details', compact('property', 'online'))
            @endif

            <h5 class="mt-3">{{ __('Map') }}</h5>

            <gmap-map
                    :center="{lat: {{ $property->latitude }}, lng: {{ $property->longitude }}}"
                    :zoom="16"
                    style="width:100%;  height: 300px;"
            >
                <gmap-marker
                        :position='{lat: {{ $property->latitude }}, lng: {{ $property->longitude }}}'
                ></gmap-marker>
            </gmap-map>
        </div>
    </div>
@endsection
