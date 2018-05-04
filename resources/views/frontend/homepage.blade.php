@extends('frontend.base')

@section('sub_header')
    <div class="properties-search position-relative overflow-hidden text-center bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-5">
            <form method="get" action="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}">
                <h1 class="display-5 font-weight-normal">{{ __('Real Estate Auctions') }}</h1>
                <div class="input-group mb-2 mr-sm-2">
                    <select name="type" class="custom-select">
                        @foreach($types as $value => $label)
                            <option @if($value == request()->get('type')) selected @endif value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <input value="{{ request()->get('keywords') }}" name="keywords" type="text" class="form-control w-50" id="keywords" placeholder="{{ __('Address, city, Property ID') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
