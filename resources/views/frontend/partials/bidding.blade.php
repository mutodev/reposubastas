<div class="border p-1">
    <strong>Balance: ${{ number_format($depositAmount) }}</strong>
    @if(!Auth::guest())
        @if($depositAmount <= 0)
            <br />{{ __('To be able to offer a deposit is required') }}
            @include('frontend.partials.paypal')
        @else
            <br /><span>{{ __('You can bid on :offersLeft '. ($offersLeft > 1 ? 'properties' : 'property'), compact('offersLeft')) }}</span>
        @endif
    @else
        <br />
        <a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'login']) }}">{{ __('Sign In') }}</a> {{ __('or') }} <a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'register']) }}">{{ __('Sign Up') }}</a> {{ __('to bid') }}
    @endif
</div>
