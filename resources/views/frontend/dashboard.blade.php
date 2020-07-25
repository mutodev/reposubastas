@extends('frontend.base')

@section('stylesheets')
    <link href="/css/frontend.css?v14" rel="stylesheet">
    <style>
        .form-group {
            margin-bottom: 5px !important;
        }

        .site-header .site-header-item {
            color: #20ABD5 !important;
        }
    </style>
@endsection

@section('content')
    {!! $page->content !!}

    <div class="properties-results bg-light-grey pt-4 pb-4">
        <div class="container">
            <div class="text-right">
                @include('frontend.partials.bidding')
            </div>

            @if (session('success'))
                <div class="alert alert-success mt-5">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mt-5">
                    {!! session('error') !!}
                </div>
            @endif

            <h4 class="mt-4 mb-4">{{__('Watch list')}}</h4>

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
                    <div class="mt-2 mb-4">
                        @endif
                        @include('frontend.partials.horizontal-property', compact('property', 'formBuilder'))
                        <?php $perRowCount++; ?>
                        @if ($loop->last || $perRowCount == $perRow)
                            <?php $perRowCount = 0; ?>
                    </div>
                @endif
            @endforeach

            <a style="width: 175px" class="btn btn-block btn-primary mx-auto" href="{{ route('frontend.page', ['pageSlug' => 'properties', 'locale' => \App::getLocale()]) }}">{{ __('View all properties') }}</a>
        </div>
    </div>
@endsection
