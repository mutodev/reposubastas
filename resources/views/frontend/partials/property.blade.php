@if($property->type->slug === 'MORTGAGE-NOTE')
    @include('frontend.partials.note', compact('property'))
@else
<a href="{{ route('frontend.page', ['pageSlug' => 'property', 'locale' => \App::getLocale(), 'id' => $property->id]) }}" class="card col-md-4 p-0 border-0 blink-{{$property->id}}">
    <div class="wm">
        @if($property->image1)
        <img class="card-img-top" height="200" src="{{ $property->getMainImage('_thumb') }}" alt="{{ $property->address }}" />
        @else
        <img class="card-img-top" height="200" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASIAAADICAYAAABMFuzmAAACUElEQVR42u3UMREAAAgEIO3f0vsYDsZwgRD0JFsAj1pEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCLg2wHnddqXj/FAiwAAAABJRU5ErkJggg==" alt="{{ $property->address }}" />
        @endif
    </div>
    <div class="property-badges">
        <span class="badge badge-dark">{{ $property->id }}</span>

        @if($property->status_id && $property->status->is_public)
            <span class="badge badge-danger">{{ $property->status->name }}</span>
        @endif

        @if($property->type_id && $property->type->slug === 'MORTGAGE-NOTE')
            <span class="badge badge-warning">{{ $property->status->name }}</span>
        @endif

        @if($property->tags->count() > 0)
            @if($property->tags->get(0)->id != 7)
            <span class="badge badge-danger">
            @else
            <span class="badge badge-secondary">
            @endif
            {{ $property->tags[0]['name_'.App::getLocale()] }}</span>
        @endif
    </div>
    <div class="card-body">
        <h5 class="card-title">{{ $property->address }}, {{ $property->city }}</h5>
        <p class="card-text text-muted">
            {{ __('Type') }}: {{ $property->type->name }}

            @if($property->bedrooms)
                <br />{{ __('Beds') }}: {{ number_format($property->bedrooms) }}
            @endif
            @if($property->bathrooms)
                <br />{{ __('Baths') }}: {{ number_format($property->bathrooms) }}
            @endif
            @if($property->sqf_area)
                <br />{{ __('Square feets') }}: {{ round($property->sqf_area, 2) }}
            @endif
            @if($property->sqm_area)
                <br />{{ __('Square meters') }}: {{ round($property->sqm_area, 2) }}
            @endif
            @if($property->cuerdas)
                <br />{{ __('Cuerdas') }}: {{ round($property->cuerdas, 2) }}
            @endif
        </p>
    </div>

    <ul class="list-group list-group-flush">
        @if($property->event_live_at && !$property->event_is_online)
            <li class="list-group-item border-0 bg-dark-blue">
                {{ __('Live Auction') }}: {{ Jenssegers\Date\Date::parse($property->event_live_at)->format('j M, g:ia')}}
            </li>
        @elseif(!$property->tags->count() || $property->tags->get(0)->id != 7)
            <li class="list-group-item border-0 bg-light-blue">
                <?php
                    $online = $property->event_is_online;
                    $endAt = new Carbon\Carbon(($online ? $property->end_at : $property->event_live_at), 'America/Puerto_Rico');
                    $biddingStartAt = new Carbon\Carbon($property->bidding_start_at, 'America/Puerto_Rico');
                    $biddingStartAtText = $biddingStartAt->format('M j') === $endAt->format('M j') ? $biddingStartAt->format('M j, g:ia') . ' - ' . $endAt->format('g:ia') : $biddingStartAt->format('M j, g:ia') . ' - ' . $endAt->format('M j, g:ia');
                    $bid = $property->getBids($property->event_id)->first();
                ?>

                @if($property->bidding_start_at)
                    {{ __('Online Auction') }}: <br />{{ $biddingStartAtText  }}
                @else
                    {{ __('Online Auction') }}
                @endif


                @if ($property->reserve)
                <div style="display: none">
                    <reserve-component :labelmet="'{{__('Reserve met')}}'" :labelnotmet="'{{__('Reserve not met')}}'" :reserve='{{$property->reserve}}' :property='{{$property->id}}' :current='{{ intval(@$bid->offer ? $bid->offer : 0) }}'></reserve-component>
                </div>
                @endif
            </li>
        @endif
        <li class="list-group-item border-0">
            <span>{{ __('Sale price') }}: @if($property->price > 0) ${{ number_format($property->price) }} @else {{ __('Request price') }} @endif</span>
                <br />
                <small style="font-size: .5em">{{ __('Commission to be paid by the Buyer') }}: @if($property->buyer_prima){{ number_format(intval($property->buyer_prima)) }}%@else 1%@endif</small>
        </li>
    </ul>
</a>
@endif
