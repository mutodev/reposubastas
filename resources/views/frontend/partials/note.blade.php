<a href="{{ route('frontend.page', ['pageSlug' => 'property', 'locale' => \App::getLocale(), 'id' => $property->id]) }}"
    class="card col-md-4 p-0 border-0 blink-{{$property->id}}">
    <div class="wm">
        @if($property->image1)
        <img class="card-img-top" height="200" src="{{ $property->getMainImage('_thumb') }}"
            alt="{{ $property->address }}" />
        @else
        <img class="card-img-top" height="200"
            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASIAAADICAYAAABMFuzmAAACUElEQVR42u3UMREAAAgEIO3f0vsYDsZwgRD0JFsAj1pEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCLg2wHnddqXj/FAiwAAAABJRU5ErkJggg=="
            alt="{{ $property->address }}" />
        @endif
    </div>
    <div class="property-badges">
        @if(!$property->event_is_online)
        <span class="badge badge-dark">{{ $property->number }}</span>
        @else
        <span class="badge badge-dark"><span class="oi oi-globe"></span></span>
        @endif

        @if($property->status_id && $property->status->is_public)
        <span class="badge badge-danger">{{ $property->status->name }}</span>
        @endif

        @if($property->type_id && $property->type->slug === 'MORTGAGE-NOTE')
        <span class="badge badge-warning">{{ $property->type->name }}</span>
        @endif

        @if($property->tags->count() > 0)
        <span class="badge badge-danger">{{ $property->tags[0]['name_'.App::getLocale()] }}</span>
        @endif
    </div>
    <div class="card-body">
        <h5 class="card-title">{{ $property->description }}</h5>
        <p class="card-text text-muted">
            {{ __('Collateral Location') }}: {{ $property->city }}, PR
        </p>
    </div>

    <ul class="list-group list-group-flush">
        @if($property->event_live_at && !$property->event_is_online)
        <li class="list-group-item border-0 bg-dark-blue">
            {{ __('Live Auction') }}: {{ Jenssegers\Date\Date::parse($property->event_live_at)->format('j M, g:ia')}}
        </li>
        @else
        <li class="list-group-item border-0 bg-warning">
            <?php
                    $online = $property->event_is_online;
                    $endAt = new Carbon\Carbon(($online ? $property->end_at : $property->event_live_at), 'America/Puerto_Rico');
                    $biddingStartAt = new Carbon\Carbon($property->bidding_start_at, 'America/Puerto_Rico');
                    $biddingStartAtText = $biddingStartAt->format('M j') === $endAt->format('M j') ? $biddingStartAt->format('M j, g:ia') . ' - ' . $endAt->format('g:ia') : $biddingStartAt->format('M j, g:ia') . ' - ' . $endAt->format('M j, g:ia');
                    $bid = $property->getBids($property->event_id)->first();
                ?>
            @if($property->bidding_start_at)
            {{ __('Mortgage Note Sale') }}: <br />{{ $biddingStartAtText  }}
            @else
            {{ __('Mortgage Note Sale') }}
            @endif

            @if ($property->reserve)
            <div style="display: none">
                <reserve-component :labelmet="'{{__('Reserve met')}}'" :labelnotmet="'{{__('Reserve not met')}}'"
                    :reserve='{{$property->reserve}}' :property='{{$property->id}}'
                    :current='{{ intval(@$bid->offer ? $bid->offer : 0) }}'></reserve-component>
            </div>
            @endif
        </li>
        @endif
        <li class="list-group-item border-0">
            <small style="font-size: .8em">{{ __('*** Picture shown represents Mortgage Note Collateral.') }}</small>
        </li>
    </ul>
</a>