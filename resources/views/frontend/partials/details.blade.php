<div class="property-details mt-4">
    <div class="container">
        <div class="row">
            <div class="col-12 p-0 col-md-4 mb-3">
                <strong class="text-dark-blue">{{ __('Details') }}</strong>

                <div>
                    <strong>{{ __('Type') }}:</strong> <span>{{ $property->type->name }}</span>
                </div>
                @if($property->zonification)
                    <div>
                        <strong>{{ __('Zonification') }}:</strong> <span>{{ $property->zonification }}</span>
                    </div>
                @endif
                @if($property->levels)
                    <div>
                        <strong>{{ __('Levels') }}:</strong> <span>{{ $property->levels }}</span>
                    </div>
                @endif
                @if($property->roof_height)
                    <div>
                        <strong>{{ __('Roof height') }}:</strong> <span>{{ $property->roof_height }}</span>
                    </div>
                @endif
                @if($property->sqf_area)
                    <div>
                        <strong>{{ __('Square feets') }}:</strong> <span>{{ number_format($property->sqf_area) }}</span>
                    </div>
                @endif
                @if($property->sqm_area)
                    <div>
                        <strong>{{ __('Square meters') }}:</strong> <span>{{ number_format($property->sqm_area) }}</span>
                    </div>
                @endif
                @if($property->open_house)
                    <div>
                        <strong>{{ __('Open house') }}:</strong> <span>{{ $property->open_house }}</span>
                    </div>
                @endif
                @if($property->catastro)
                    <div>
                        <strong>{{ __('Catastro') }}:</strong> <span>{{ $property->catastro }}</span>
                    </div>
                @endif
                @if($property->latitude && $property->longitude)
                    <div>
                        <strong>{{ __('Coordenates') }}:</strong> <span>{{ $property->latitude }}, {{ $property->longitude }}</span>
                    </div>
                @endif
                @if($property->amenities)
                    <div>
                        <strong>{{ __('Amenities') }}:</strong> <span>{{ $property->amenities }}</span>
                    </div>
                @endif
                @if($property->description)
                    <div>
                        <strong>{{ __('Open house') }}:</strong> <span>{{ $property->description }}</span>
                    </div>
                @endif
            </div>
            <div class="col-12 p-0 col-md-4">
                <strong class="text-dark-blue">{{ __('Transaction') }}</strong>

                <div class="mb-3">
                    <strong>{{ __('Payment method') }}:</strong> <span>{{ $property->is_cash_only ? __('Cash only') : __('Cash, Financed') }}</span>
                </div>

                <strong class="text-dark-blue">{{ __('Event') }}</strong>

                <div>
                    <strong>{{ __('Type of auction') }}:</strong> <span>{{ $online ? __('Online') : __('Live') }}</span>
                </div>

                @if(!$online)
                    <div>
                        <strong>{{ __('Auction place') }}:</strong> <span>{{ $property->event_location }}</span>
                    </div>

                    <?php $daysLeft = (new Carbon\Carbon($property->end_at))->diffInDays(); ?>
                    @if($daysLeft > 0)
                    <div class="mt-4">
                        <span>{{ __('Event begins in') }}:</span><br />
                        <strong class="unit">{{ number_format($daysLeft) }} {{ __('days') }}</strong>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>