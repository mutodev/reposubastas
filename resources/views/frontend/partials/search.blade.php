<div class="properties-search position-relative overflow-hidden text-center bg-light">
    <div class="col-md-6 p-lg-5 mx-auto my-4 my-sm-5">
        <div class="bg-dark-blue p-2 p-sm-4 mx-auto rounded properties-search-box mw-75">
            <form method="get"
                action="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}">
                <h1 class="display-5 font-weight-normal">{{ __('YOUR SMART INVESTMENT IN PR') }}</h1>
                <div class="input-group mr-sm-2">
                    <select name="type" class="custom-select">
                        @foreach(App\Models\PropertyType::forSelect('All Properties') as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <input autocomplete="off" value="{{ request()->get('keywords') }}" name="keywords" type="text"
                        class="form-control w-50" id="keywords"
                        placeholder="{{ __('Address, region, city, Property ID') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary bg-light-red border-0">{{ __('Search') }}</button>
                    </div>
                </div>
            </form>

            <a class="btn btn-primary mt-3"
                href="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}?type[]=1&type[]=2&type[]=3">{{ __('Auction properties') }}</a>
            <a class="btn btn-secondary mt-3"
                href="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}?tags[]=7">{{ __('Resale properties') }}</a>
            <a class="btn btn-warning mt-3"
                href="{{ route('frontend.page', ['locale' => App::getLocale(), 'page' => 'properties']) }}?type[]=4">{{ __('View mortage notes') }}</a>
        </div>
    </div>
</div>