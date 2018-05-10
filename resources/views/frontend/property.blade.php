@extends('frontend.base')

@section('page_title', "{$property->address}, {$property->city} {$property->region}")

@section('sub_header')
    @include('frontend.partials.search')
@endsection

@section('content')
    {!! $page->content !!}

    <div class="property pt-4 pb-4">
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

            <strong class="text-dark-blue">{{ __('Property') }}</strong>
            <h2 class="m-0">{{ $property->address }}</h2>
            <p class="text-muted">{{ $property->city }}</p>

            <div class="clearfix">
                <div class="addthis_inline_share_toolbox_zkje float-right"></div>
            </div>

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

                                <div class="price mt-3">
                                    <strong class="text-dark-blue">{{ __('Current offer') }}</strong>
                                    <br />
                                    <strong class="unit">${{ number_format(intval($bid->offer ?? $property->price)) }}</strong>
                                </div>

                                <div class="mt-3">
                                    {!! form($form) !!}
                                </div>
                            @endif
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
