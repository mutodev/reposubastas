<?php
$endAt = new Carbon\Carbon(($property->event_is_online ? $property->end_at : $property->event_live_at), 'America/Puerto_Rico');
$days = $endAt->diffInDays();
$hours = $endAt->diffInHours() - ($days * 24);
$minutes = $endAt->diffInMinutes() - ((($days * 24) + $hours) * 60);

$today = new Carbon\Carbon(null, 'America/Puerto_Rico');

$biddingStartAt = new Carbon\Carbon($property->bidding_start_at, 'America/Puerto_Rico');

//Get last bid
$bid = $property->getBids($property->event_id)->first();

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
<div class="card mb-3 p-2 blink-{{$property->id}}">
    <div class="row no-gutters">
        <div class="col-md-3">
            <a href="{{ route('frontend.page', ['pageSlug' => 'property', 'locale' => \App::getLocale(), 'id' => $property->id]) }}">
                <img src="{{ $property->getMainImage('_thumb') }}" class="card-img mt-5">
            </a>

            @if($property->reserve)
                <reserve-component :labelmet="'{{__('Reserve met')}}'" :labelnotmet="'{{__('Reserve not met')}}'" :reserve='{{$property->reserve}}' :property='{{$property->id}}' :current='{{ intval(@$bid->offer ? $bid->offer : 0) }}'></reserve-component>
            @endif
        </div>
        <div class="col-md-6">
            <div class="card-body">
                <h5 class="card-title">{{ $property->address }}, {{ $property->city }}</h5>
                <p class="card-text">
                    {{ __('Type') }}: {{ $property->type->name }}

                    @if($property->bedrooms)
                        &nbsp;&nbsp;{{ __('Beds') }}: {{ number_format($property->bedrooms) }}
                    @endif
                    @if($property->bathrooms)
                        &nbsp;{{ __('Baths') }}: {{ number_format($property->bathrooms) }}
                    @endif
                    @if($property->sqf_area)
                        &nbsp;{{ __('Square feets') }}: {{ round($property->sqf_area, 2) }}
                    @endif
                    @if($property->sqm_area)
                        &nbsp;{{ __('Square meters') }}: {{ round($property->sqm_area, 2) }}
                    @endif
                    @if($property->cuerdas)
                        &nbsp;{{ __('Cuerdas') }}: {{ round($property->cuerdas, 2) }}
                    @endif
                </p>

                @if($today->lt($endAt))
                    <strong class="text-dark-blue">{{ __('Event ends in') }}:</strong>
                    <span class="property-remaining" style="font-size: 13px;">
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
                            @if($extended)
                                <small>{{__('Extended :minutes minutes', ['minutes' => $extended])}}</small>
                            @endif
                            <br />
                            @if($property->event_is_online)
                            @if($property->bidding_start_at)
                                <div class="alert alert-danger">
                                        {{ __('Online Auction') }}: {{ $biddingStartAtText }}
                                    </div>
                            @endif
                        @else
                            <div class="alert alert-danger">
                                    {{ __('Live Auction') }}: {{ Jenssegers\Date\Date::parse($property->event_live_at)->format('j M, g:ia')}}
                                </div>
                        @endif
                        </span>
                @elseif($property->event_is_online)
                    <div class="alert alert-warning">
                        {{ __('Online Auction Ended') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <div class="price">
                    <strong class="text-dark-blue">{{ __('Sale price') }}</strong>
                    <strong class="unit">${{ number_format(intval($property->price)) }}</strong>
                </div>

                <?php
                if (!$property->event_is_online) {
                    $endAt->subDays(2);
                }
                ?>

                @if ($biddingStartAt)
                    @if($property->event_is_online && $today->gte($biddingStartAt) && $today->lte($endAt))

                        <div class="price mt-1">
                            <strong class="text-dark-blue">{{ __('Current offer') }}</strong>
                            <strong class="unit"><bid-component :property='{{$property->id}}' :current='{{ intval(@$bid->offer ? $bid->offer : ($property->reserve ?? 0)) }}'></bid-component></strong>
                        </div>
                    @endif

                    @if($today->gte($biddingStartAt) && $today->lte($endAt) && ($property->event_is_online || ($property->status_id && !in_array($property->status->slug, ['OPTIONED', 'SOLD']))))
                        <div class="mt-1">
                            <?php
                            $form = $formBuilder->create(App\Forms\Frontend\Property\OfferForm::class, [
                                'method' => 'POST',
                                'url' => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'dashboard', 'id' => $property->id]),
                                'model' => [
                                    'offer' => intval($bid->offer ?? $property->price)
                                ]
                            ], [
                                'is_cash_only' => $property->is_cash_only
                            ]);
                            ?>
                            {!! form($form) !!}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
