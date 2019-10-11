@extends('frontend.base')

@section('sub_header')
    <div class="homepage-top position-relative p-5">
        <div class="container">
            <h1 class="homeText">{{ __('THE') }}<br /> <strong>{{ __('BIGGEST AUCTION') }} </strong><br />{{ __('OF THE YEAR') }}</h1>
            <span>{{ __('NOVEMBER') }} 23, 2019 | VIVO BEACH CLUB</span><br />
            <a class="btn btn-primary mt-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'register']) }}">{{ __('Register now!') }}</a>
            <a class="btn btn-outline-secondary ml-2 mt-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}">{{ __('View properties') }}</a>
        </div>
    </div>

    <div>
        <div class="container position-relative text-center">
            <div class="box-top-left"></div>
            <div class="box-top-right"></div>
            <div class="box-bottom-left"></div>
            <div class="box-bottom-right"></div>
            <img style="position: absolute;top: -125px;left: 50%;margin-left: -50px; z-index: 2000" src="/images/logo-icon.svg" width="100" />

            <div style="position: relative; z-index: 99999999">
                <div class="display-6 text-uppercase font-weight-bold">{{ __('WELCOME') }}</div>

                <p class="mt-3">{{ __('Reposubasta welcomes you to new business opportunities where your smart investment is our priority. Each event offers you commercial, residential and land properties with the best discounts on the market. Participate in the experience and start your financial independence with solidity.') }}</p>

                <br /><br /><a href="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'terms']) }}">Términos y Condiciones</a>
            </div>
        </div>
    </div>

{{--    <div class="homepage-bottom text-center position-relative p-5">--}}
{{--        <div class="container position-relative" style="margin-top: 100px !important;">--}}
{{--            <h1 class="display-6 text-uppercase font-weight-bold">{{ __('PARTICIPATING IN REPOSUBASTA IS EASY') }}<br /> {{ __('You just have to follow the steps:') }}</h1>--}}

{{--            <div class="btn-group btn-group-toggle mt-3 steps-control" data-toggle="buttons">--}}
{{--                <button type="button" class="btn btn-primary active" data-toggle="button" aria-pressed="false">--}}
{{--                    {{ __('Live Auction') }}--}}
{{--                </button>--}}
{{--                <button type="button" class="btn btn-primary" data-toggle="button" aria-pressed="false">--}}
{{--                    {{ __('Online Auction') }}--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="properties-search position-relative text-center p-5">--}}
{{--        <h1 class="homeText">{{ __('NOW THE OPPORTUNITY IS AT REACH OF A CLICK') }}</h1>--}}

{{--        <a href="/{{ App::getLocale() }}/properties?type=&keywords=&event_type=ONLINE&price_min=0&price_max=9999999" class="icono-home col-xs-12"></a>--}}

{{--        <table width="100%">--}}
{{--            <tr>--}}
{{--                <td>--}}
{{--                    <div class="col-md-5 mx-auto">--}}
{{--                        <div class="properties-search-box">--}}
{{--                            <form method="get" action="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}">--}}
{{--                                <div class="input-group mr-sm-2">--}}
{{--                                    <select name="type" class="custom-select">--}}
{{--                                        @foreach(App\Models\PropertyType::forSelect('All Properties') as $value => $label)--}}
{{--                                            <option @if($value == request()->get('type')) selected @endif value="{{ $value }}">{{ $label }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                    <input autocomplete="off" value="{{ request()->get('keywords') }}" name="keywords" type="text" class="form-control w-50" id="keywords" placeholder="{{ __('Address, region, city, Property ID') }}">--}}
{{--                                    <div class="input-group-append">--}}
{{--                                        <button type="submit" class="btn btn-primary bg-light-red border-0">{{ __('Search') }}</button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        </table>--}}
{{--    </div>--}}

@endsection

