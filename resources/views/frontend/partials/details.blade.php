<div class="property-details mt-4">
    <div class="container">
        @if($property->description && $property->type->slug !== 'MORTGAGE-NOTE')
        <div class="row">
            <div class="col-12 p-0 col-md-8 mb-3">
                <strong class="text-dark-blue">{{ __('Description') }}</strong>
                <div>
                    <p>{{ $property->description }}</p>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-12 p-0 mb-3">
                <div>
                    <strong>{{ __('Original Loan Amount') }}:</strong> <span>{{ $property->notes_original_loan_amount }}</span>
                </div>

                <div>
                    <strong>{{ __('Current Balance') }}:</strong> <span>{{ $property->notes_current_balance }}</span>
                </div>

                <div>
                    <strong>{{ __('Term/Lenght') }}:</strong> <span>{{ $property->notes_term }}</span>
                </div>

                <div>
                    <strong>{{ __('CRIM Fee') }}:</strong> <span>{{ $property->notes_crim }}</span>
                </div>

                <br />

                <strong class="text-danger">{{ __('***NO INSPECTION IS ALLOWED. THIS IS A MORTGAGE NOTE AUCTION AND DOES NOT REPRESENT COLLATERAL ADQUISITION. THIS COLLATERAL IS STILL PRIVATE PROPERTY AND SHOULD NOT BE TRESPASSED. IT IS REQUIRED TO SIGN A CONFIDENTIALITY AGREEMENT FOR MORE INFORMATION. 787-418-3100***') }}</strong>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-12 p-0 col-md-4 mb-3">
                <strong class="text-dark-blue">{{ __('Collateral Details') }}</strong>

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
                        <strong>{{ __('Square feets') }}:</strong> <span>{{ round($property->sqf_area, 2) }}</span>
                    </div>
                @endif
                @if($property->sqm_area)
                    <div>
                        <strong>{{ __('Square meters') }}:</strong> <span>{{ round($property->sqm_area, 2) }}</span>
                    </div>
                @endif
                @if($property->cuerdas)
                    <div>
                        <strong>{{ __('Cuerdas') }}:</strong> <span>{{ round($property->cuerdas, 2) }}</span>
                    </div>
                @endif
                @if($property->catastro)
                    <div>
                        <strong>{{ __('CRIM ID') }}:</strong> <span><a href="https://www.satasgis.crimpr.net/cdprpc/" target="_blank">{{ $property->catastro }}</a></span>
                    </div>
                @endif
                @if($property->latitude && $property->longitude)
                    <div>
                        <strong>{{ __('Coordinates') }}:</strong> <span><a href="http://maps.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}" target="_blank">{{ $property->latitude }}, {{ $property->longitude }}</a></span>
                    </div>
                @endif
                @if($property->amenities)
                    <div>
                        <strong>{{ __('Amenities') }}:</strong> <span>{{ $property->amenities }}</span>
                    </div>
                @endif
            </div>
            <div class="col-12 p-0 col-md-4">
                @if($property->type->slug !== 'MORTGAGE-NOTE')
                    @if($online)
                        <strong class="text-dark-blue">{{ __('Inspection date') }}</strong>

                        <div class="mb-3">
                            <span>{{ __('Call for appointment') }}</span>
                        </div>
                    @else
                        @if($property->open_house)
                        <strong class="text-dark-blue">{{ __('Inspection date') }}</strong>

                        <div class="mb-3">
                            <span>{{ $property->open_house }}</span>
                        </div>
                        @endif
                    @endif
                @endif

                <strong class="text-dark-blue">{{ __('Transaction') }}</strong>

                <div class="mb-3">
                    <strong>{{ __('Payment method') }}:</strong> <span>{{ $property->is_cash_only ? __('Cash only') : __('Cash, Financed') }}</span>
                </div>

                @if(!$property->tags->count() || $property->tags->get(0)->id != 7)
                <strong class="text-dark-blue">{{ __('Event') }}</strong>

                <div>
                    <strong>{{ $online ? __('Online Auction') : __('Live Auction') }}</strong>
                </div>
                @endif

                @if(!$online)
                    <div>
                        <strong>{{ __('Auction place') }}:</strong> <span>{{ $property->event_location }}</span>
                    </div>

                    <div>
                        <strong>{{ __('Live Auction') }}:</strong> <span>{{ Jenssegers\Date\Date::parse($property->event_live_at)->format('j M, g:ia')}}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
