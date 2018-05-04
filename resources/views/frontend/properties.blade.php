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

@section('content')
    {!! $page->content !!}

    <?php
        $perRow = 3;
        $perRowCount = 0;
    ?>
    @foreach($properties as $property)
        @if ($loop->first || $perRowCount == 0)
        <div class="card-deck mt-4 mb-4">
        @endif
            <div class="card">
                <img class="card-img-top" height="180" src="https://s3.amazonaws.com/reposubastas/{{ $property->image1 }}" alt="{{ $property->address }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $property->address }}</h5>
                    <p class="card-text">Blah blah</p>
                </div>
                <div class="card-footer">
                    <span>{{ __('Starting bid') }}: ${{ number_format($property->price) }}</span>
                </div>
            </div>
        <?php $perRowCount++; ?>
        @if ($loop->last || $perRowCount == $perRow)
        <?php $perRowCount = 0; ?>
        </div>
        @endif
    @endforeach

    {{ $properties->links() }}
@endsection
