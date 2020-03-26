@extends('frontend.base')

@section('sub_header')
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="homepage-top">
                <div class="container">
                    <h1 class="homeText">{{ __('THE') }}<br /> <strong>{{ __('BIGGEST AUCTION') }} </strong><br />{{ __('OF THE YEAR') }}</h1>
                    <span>{{ __('NOVEMBER') }} 23, 2019 | VIVO BEACH CLUB</span><br />
                    <a class="btn btn-primary mt-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}">{{ __('View properties') }}</a>
{{--                    <a class="btn btn-danger mt-3" target="_blank" href="https://pdfmyurl.com/saveaspdf?url={{ urlencode(route('frontend.page', array_merge(['pageSlug' => 'properties', 'locale' => App::getLocale(), 'pdftest' => 1], request()->all()))) }}">{{ __('Download catalog') }}</a>--}}
                    <a class="btn btn-outline-secondary ml-sm-2 mt-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'register']) }}">{{ __('Register now!') }}</a>
                </div>
            </div>
            <div class="carousel-item active">
                <img class="d-block w-100" src="/images/aerial.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="/images/residential.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="/images/metro.jpg" alt="Third slide">
            </div>
        </div>
    </div>

    <div style="margin-top: 40px">
        <div class="container position-relative text-center">
            <div class="box-top-left"></div>
            <div class="box-top-right"></div>
            <div class="box-bottom-left"></div>
            <div class="box-bottom-right"></div>
            <img style="position: absolute;top: -125px;left: 50%;margin-left: -50px; z-index: 2000" src="/images/logo-icon.svg" width="100" />

            <div style="position: relative; z-index: 99999999">
                <div class="display-6 text-uppercase font-weight-bold">{{ __('WELCOME') }}</div>

                <p class="mt-3">{{ __('Reposubasta welcomes you to a new business opportunity where your smart investment is our priority. Each event offers you commercial, residential and land properties with the best discounts on the Puerto Rico market. Participate in the experience and have a firm start your financial independence.') }}</p>

                <br /><br /><a href="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'terms']) }}">{{ __('Terms and Conditions') }}</a>
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

