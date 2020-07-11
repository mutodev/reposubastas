@extends('frontend.base')

@section('page_title', "{$property->address}, {$property->city} {$property->region}")

@section('sub_header')
    @include('frontend.partials.search')
@endsection

@section('content')
    {!! $page->content !!}

    <div class="property pt-4 pb-4 blink-{{$property->id}}">
        @if ($property->reserve)
            <div style="display: none">
                <reserve-component :labelmet="'{{__('Reserve met')}}'" :labelnotmet="'{{__('Reserve not met')}}'" :reserve='{{$property->reserve}}' :property='{{$property->id}}' :current='{{ intval(@$bid->offer ? $bid->offer : 0) }}'></reserve-component>
            </div>
        @endif
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

            <div class="row mb-3">
                <div class="col-xs-12 col-sm-6">
                    @if(!\Auth::guest())
                    <button class="selectProperty btn btn btn-primary" data-url="{{ route('frontend.page', ['pageSlug' => 'property', 'locale' => \App::getLocale(), 'id' => $property->id]) }}">{{ __('Add to Watchlist') }}</button>
                    <a class="btn btn-primary" href="{{ route('frontend.page', ['pageSlug' => 'dashboard', 'locale' => \App::getLocale()]) }}">{{ __('Watch list') }}</a>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-6">
                    @include('frontend.partials.bidding', ['id' => $property->id])
                </div>
            </div>

            <strong class="text-dark-blue">{{ __('Property') }}</strong>
            <h2 class="m-0">{{ $property->address }}</h2>
            <p class="text-muted">{{ $property->city }}</p>

            <div class="container">
                <div class="row">
                    <div class="col-md-8 p-0">
                        <div id="gallery" class="carousel slide" data-ride="carousel" data-interval="false">
                            <ol class="carousel-indicators">
                                @if($property->youtube_video)
                                    <li data-target="#gallery" data-slide-to="0" class="active"></li>
                                @endif
                                @foreach(range(1, 10) as $index)
                                @if(!($image = $property->getImage($index)))
                                    @continue
                                @endif
                                <li data-target="#gallery" data-slide-to="{{ $property->youtube_video ? $index : $index - 1 }}" class="@if($index == 1 && !$property->youtube_video) active @endif"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @if($property->youtube_video)
                                    <div class="carousel-item active">
                                        <div class="wm">
                                            <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{$youtubeId}}?controls=0&rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                @endif
                                @foreach(range(1, 10) as $index)
                                @if(!($image = $property->getImage($index)))
                                    @continue
                                @endif
                                <div class="carousel-item @if($index == 1 && !$property->youtube_video) active @endif">
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

                        <div class="addthis_inline_share_toolbox_zkje"></div>

                        @if($property->type->slug === 'LAND')
                        <div class="text-danger">* {{ __('The photos of the land are for illustrative purposes and do not necessarily represent the exact boundaries and fit of the property') }}</div>
                        @endif

                        @include('frontend.partials.details', compact('property', 'online'))
                    </div>
                    <div class="col p-0 pl-sm-4 pr-sm-4 pt-sm-4">
                        <?php
                        $endAt = new Carbon\Carbon(($online ? $property->end_at : $property->event_live_at), 'America/Puerto_Rico');
                        $days = $endAt->diffInDays();
                        $hours = $endAt->diffInHours() - ($days * 24);
                        $minutes = $endAt->diffInMinutes() - ((($days * 24) + $hours) * 60);

                        $today = new Carbon\Carbon(null, 'America/Puerto_Rico');

                        $biddingStartAt = new Carbon\Carbon($property->bidding_start_at, 'America/Puerto_Rico');

                        $extended = 0;

                        if ($bid) {
                            $bidCreatedAt = new Carbon\Carbon($bid->created_at);

                            if ($bidCreatedAt->gte((clone $endAt)->subMinutes(5)) && $bidCreatedAt->lte($endAt)) {
                                $extended = 5;
                            } elseif ($bidCreatedAt->gte($endAt) && $bidCreatedAt->lte((clone $endAt)->addMinutes(5))) {
                                $extended = 8;
                            } elseif ($bidCreatedAt->gte((clone $endAt)->addMinutes(5)) && $bidCreatedAt->lte((clone $endAt)->addMinutes(8))) {
                                $extended = 9;
                            }

                            $endAt->addMinutes($extended);
                        }

                        $biddingStartAtText = $biddingStartAt->format('M j') === $endAt->format('M j') ? $biddingStartAt->format('M j, g:ia') . ' - ' . $endAt->format('g:ia') : $biddingStartAt->format('M j, g:ia') . ' - ' . $endAt->format('M j, g:ia');
                        ?>

                        @if ($biddingStartAt)
                            @if($today->lt($endAt))
                                <strong class="text-dark-blue">{{ __('Event ends in') }}:</strong>
                                <div class="property-remaining" style="font-size: 13px;">
                                    <vue-countdown-timer
                                            :start-time="{{$today->getTimestamp()}}"
                                            :end-time="{{$endAt->getTimestamp()}}"
                                            :interval="1000"
                                            :start-label="'Until start:'"
                                            :end-label="''"
                                            label-position="begin"
                                            :end-text="'Auction ended!'"
                                            :day-txt="'d'"
                                            :hour-txt="'h'"
                                            :minutes-txt="'m'"
                                            :seconds-txt="'s'">
                                    </vue-countdown-timer>
                                    <br />
                                    @if($online)
                                        @if($property->bidding_start_at)
                                            <div class="alert alert-danger">
                                                {{ __('Online Auction') }}: <br />{{ $biddingStartAtText }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-danger">
                                            {{ __('Live Auction') }}: <br />{{ Jenssegers\Date\Date::parse($property->event_live_at)->format('j M, g:ia')}}
                                        </div>
                                    @endif
                                </div>
                            @elseif($online)
                                <div class="alert alert-warning">
                                    {{ __('Online Auction Ended') }}
                                </div>
                            @endif
                        @endif

                        <div class="price mt-3">
                            <strong class="text-dark-blue">{{ __('Sale price') }}</strong>
                            <br />
                            <strong class="unit">${{ number_format(intval($property->price)) }} </strong>
                        </div>

                        <div class=" mt-3">
                            <strong class="text-dark-blue">{{ __('Commission to be paid by the Buyer') }}</strong>
                            <br />
                            <strong>@if($property->buyer_prima){{ number_format(intval($property->buyer_prima)) }}%@else 1%@endif</strong>
                        </div>

                        <?php
                            if (!$online) {
                                $endAt->subDays(2);
                            }
                        ?>

                        @if ($biddingStartAt)
                            @if($online && $today->gte($biddingStartAt) && $today->lte($endAt))

                                <div class="price mt-3">
                                    <strong class="text-dark-blue">{{ __('Current offer') }}</strong>
                                    <br />
                                    <strong class="unit"><bid-component :user='{{\Auth::guest() ? 'A' : \Auth::user()->id}}' :property='{{$property->id}}' :current='{{ intval(@$bid->offer ? $bid->offer : 0) }}'></bid-component></strong>

                                    @if (!\Auth::guest() && $bid && $bid->user_id === \Auth::user()->id)<small class="winning winning-{{\Auth::user()->id.$property->id}}">({{__('Winning')}})</small> @endif
                                </div>

                                @if($property->reserve)
                                    <reserve-component :labelmet="'{{__('Reserve met')}}'" :labelnotmet="'{{__('Reserve not met')}}'" :reserve='{{$property->reserve}}' :property='{{$property->id}}' :current='{{ intval(@$bid->offer ? $bid->offer : 0) }}'></reserve-component>
                                @endif

                                <div class="price mt-3">
                                    <strong class="text-dark-blue">{{ __('Make your offer') }} (No ",", only numbers)</strong>
                                </div>
                            @endif

                            @if($today->gte($biddingStartAt) && $today->lte($endAt) && ($online || ($property->status_id && !in_array($property->status->slug, ['OPTIONED', 'SOLD']))))
                                <div class="mt-3">
                                    {!! form($form) !!}

                                    <br />

                                    @if (!Auth::guest() && (!$userEvent || $userEvent->remaining_deposit <= 0))
                                        {{ __('You must present your purchase intention by processing a minimum deposit') }}
                                        <br />
                                        @include('frontend.partials.paypal')
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

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
