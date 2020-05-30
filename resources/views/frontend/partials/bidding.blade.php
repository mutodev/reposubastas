<div class="border p-1">
    <strong>Balance: ${{ number_format($depositAmount) }}</strong>
    @if(!Auth::guest())
        @if($depositAmount <= 0)
            <br />{{ __('To be able to offer a deposit is required') }}<br />
        <table cellpadding="10">
            <tr>
                <td align="left">
                    {{__('Single property')}}<br />@include('frontend.partials.paypal', ['mode' => 'single'])
                </td>
                <td align="left">
                    {{__('Multiple properties')}}<br />@include('frontend.partials.paypal', ['mode' => 'multiple'])
                </td>
            </tr>
        </table>
        @else
            <br /><span>{{ __('You can bid on :left '. ($offersLeft > 1 ? 'properties' : 'property'), ['left' => $depositAmount < 5075 ? __('one property'): __('multiple properties') ]) }}</span>
        @endif
    @else
        <br />
        <a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'login']) }}">{{ __('Sign In') }}</a> {{ __('or') }} <a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'register']) }}">{{ __('Sign Up') }}</a> {{ __('to bid') }}
    @endif
</div>
