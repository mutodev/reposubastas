<a href="{{ route('frontend.page', ['pageSlug' => 'property', 'locale' => \App::getLocale(), 'id' => $property->id]) }}" class="card col-md-4 p-0 border-0">
    <div class="wm">
        <img class="card-img-top" height="200" src="@if($property->image1){{ $property->getMainImage('_thumb') }}@elsedata:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASIAAADICAYAAABMFuzmAAACUElEQVR42u3UMREAAAgEIO3f0vsYDsZwgRD0JFsAj1pEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCJARICIRASICBCRiAARASISESAiQEQiAkQEiEhEgIgAEYkIEBEgIhEBIgJEJCLg2wHnddqXj/FAiwAAAABJRU5ErkJggg==@endif" alt="{{ $property->address }}">
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

        @if($property->tags->count() > 0)
            <span class="badge badge-danger">{{ $property->tags[0]['name_'.App::getLocale()] }}</span>
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
        @else
            <li class="list-group-item border-0 bg-light-blue">
                {{ __('Online Auction Ends') }}: {{ Jenssegers\Date\Date::parse($property->end_at)->format('j M, g:ia')}}
            </li>
        @endif
        <li class="list-group-item border-0">
            <span>{{ __('Sale price') }}: @if($property->price > 0)${{ number_format($property->price) }}@else<a href="{{ route('frontend.page', ['pageSlug' => 'contact', 'locale' => \App::getLocale()]) }}">{{ __('Request price') }}</a>@endif</span>
        </li>
    </ul>
</a>
