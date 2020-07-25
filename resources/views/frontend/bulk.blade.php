@extends('frontend.base')

@section('content')
    {!! $page->content !!}

    <div class="properties-results bg-light-grey pt-4 pb-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {!! session('error') !!}
                </div>
            @endif

            <div class="text-right">
                @include('frontend.partials.bidding')
            </div>

            @if(!$properties->total())
                <div class="py-5">
                    <h2 class="text-dark-blue">{{ __('Sorry, no results were found') }}</h2>
                </div>
            @endif

            <?php
            $perRow = 3;
            $perRowCount = 0;
            ?>
            @foreach($properties as $property)
                @if ($loop->first || $perRowCount == 0)
                    <div class="card-deck mt-2 mb-4">
                        @endif
                        @include('frontend.partials.bulk', compact('property'))
                        <?php $perRowCount++; ?>
                        @if ($loop->last || $perRowCount == $perRow)
                            <?php $perRowCount = 0; ?>
                    </div>
                @endif
            @endforeach

            <div class="col-sm-6 mx-auto">
                {!! form($form) !!}
            </div>
        </div>
    </div>
@endsection
