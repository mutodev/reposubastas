@extends('frontend.base')

@section('sub_header')
    <div class="properties-search position-relative text-center p-5">
{{--        <h1 class="homeText">{{ __('NOW THE OPPORTUNITY IS AT REACH OF A CLICK') }}</h1>--}}

{{--        <a href="/{{ App::getLocale() }}/properties?type=&keywords=&event_type=ONLINE&price_min=0&price_max=9999999" class="icono-home col-xs-12"></a>--}}

        <table width="100%">
            <tr>
                <td>
                    <div class="col-md-5 mx-auto">
                        <div class="properties-search-box">
                            <form method="get" action="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}">
                                <div class="input-group mr-sm-2">
                                    <select name="type" class="custom-select">
                                        @foreach(App\Models\PropertyType::forSelect('All Properties') as $value => $label)
                                            <option @if($value == request()->get('type')) selected @endif value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input autocomplete="off" value="{{ request()->get('keywords') }}" name="keywords" type="text" class="form-control w-50" id="keywords" placeholder="{{ __('Address, region, city, Property ID') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary bg-light-red border-0">{{ __('Search') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection

