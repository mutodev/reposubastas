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
                    {!! session('success') !!}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {!! session('error') !!}
                </div>
            @endif

            <strong class="text-dark-blue">{{ __('Property') }}</strong>
            <h2 class="m-0">{{ $property->address }}</h2>
            <p class="text-muted">{{ $property->city }}</p>

            <div class="clearfix mb-3">
{{--                <div class="float-left">--}}
                    <a class="btn btn-primary" href="{{ route('frontend.page', ['pageSlug' => 'bulk', 'locale' => \App::getLocale()]) }}">{{ __('Saved list') }}</a>
                    <button class="selectProperty btn btn-secondary" data-url="{{ route('frontend.page', ['pageSlug' => 'property', 'locale' => \App::getLocale(), 'id' => $property->id]) }}">{{ __('Save') }}</button>
{{--                </div>--}}
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
                                    <div class="wm">
                                        <img class="d-block w-100" src="{{ $image }}" alt="First slide">
                                    </div>
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

                            @if($property->status_id && $property->status->is_public)
                                <span class="badge badge-danger">{{ $property->status->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col p-0 @if($online) pl-sm-5 pr-sm-5 pt-sm-5 @else pl-sm-5 @endif">
                        <?php
                        $endAt = new Carbon\Carbon(($online ? $property->end_at : $property->event_live_at));
                        $days = $endAt->diffInDays();
                        $hours = $endAt->diffInHours() - ($days * 24);
                        $minutes = $endAt->diffInMinutes() - ((($days * 24) + $hours) * 60);

                        $today = new Carbon\Carbon();
                        ?>

                        @if($today->lt($endAt))
                            <strong class="text-dark-blue">{{ __('Event ends in') }}:</strong>

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
                                <br />
                                @if($online)
                                    <strong>{{ __('Online Auction') }}: {{ Jenssegers\Date\Date::parse($property->end_at)->format('j M, g:ia')}}</strong>
                                @else
                                    <strong>{{ __('Live Auction') }}: {{ Jenssegers\Date\Date::parse($property->event_live_at)->format('j M, g:ia')}}</strong>
                                @endif
                            </div>
                        @endif

                        <div class="price mt-3">
                            <strong class="text-dark-blue">{{ __('Sale price') }}</strong>
                            <br />
                            <strong class="unit">${{ number_format(intval($property->price)) }}</strong>
                        </div>

                        <?php
                            if (!$online) {
                                $endAt->subDays(2);
                            }
                        ?>

                        @if($online && $today->lt($endAt))
                            <div class="price mt-3">
                                <strong class="text-dark-blue">{{ __('Current offer') }}</strong>
                                <br />
                                <strong class="unit">${{ number_format(intval($bid->offer ?? 0)) }}</strong>
                            </div>

                            <div class="price mt-3">
                                <strong class="text-dark-blue">{{ __('Make your offer') }}</strong>
                            </div>
                        @endif

                        @if($today->lt($endAt) && ($online || ($property->status_id && !in_array($property->status->slug, ['OPTIONED', 'SOLD']))))
                            <div class="mt-3">
                                {!! form($form) !!}

                                <br />

                                @if (!Auth::guest() && (!$userEvent || $userEvent->remaining_deposit <= 0))
                                    {{ __('You must present your purchase intention by processing a minimum deposit') }}
                                    <br />
                                    <paypal
                                            amount="1550.00"
                                            currency="USD"
                                            :client="credentials"
                                            env="production"
                                            v-on:payment-authorized="paymentAuthorized"
                                            v-on:payment-completed="paymentCompleted"
                                            v-on:payment-cancelled="paymentCancelled"
                                    >
                                    </paypal>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @include('frontend.partials.details', compact('property', 'online'))

            @if($property->latitude && $property->longitude)
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
            @endif
        </div>
    </div>
@endsection
