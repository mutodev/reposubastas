<a href="{{ route('frontend.page', ['pageSlug' => 'property', 'locale' => \App::getLocale(), 'id' => $property->id]) }}" class="card col-md-4 p-0 border-0">
    <img class="card-img-top" height="200" src="{{ $property->getImage() }}" alt="{{ $property->address }}">
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
    <div class="card-body">
        <h5 class="card-title">{{ $property->address }}</h5>
        <p class="card-text text-muted">
            {{ __('Type') }}: {{ $property->type->name }}

            @if($property->bedrooms)
                <br />{{ __('Beds') }}: {{ number_format($property->bedrooms) }}
            @endif
            @if($property->bathrooms)
                <br />{{ __('Baths') }}: {{ number_format($property->bathrooms) }}
            @endif
            @if($property->sqf_area)
                <br />{{ __('Square feets') }}: {{ number_format($property->sqf_area) }}
            @endif
            @if($property->sqm_area)
                <br />{{ __('Square meters') }}: {{ number_format($property->sqm_area) }}
            @endif
        </p>
    </div>

    <ul class="list-group list-group-flush">
        @if($property->event_end_at)
            <li class="list-group-item border-0 bg-dark-blue">
                {{ __('Live Auction') }}: {{ Jenssegers\Date\Date::parse($property->event_end_at)->format('j M, g:ia')}}
            </li>
        @else
            <li class="list-group-item border-0 bg-light-blue">
                {{ __('Online Auction Ends') }}: {{ Jenssegers\Date\Date::parse($property->end_at)->format('j M, g:ia')}}
            </li>
        @endif
        <li class="list-group-item border-0">
            <span>{{ __('Sale price') }}: ${{ number_format($property->price) }}</span>
        </li>
    </ul>
</a>
