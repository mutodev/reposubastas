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
                    {{ session('error') }}
                </div>
            @endif

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

            <form class="form">
                <div class="form-group">
                    {{ __('Name') }}:&nbsp;
                    <input required type="text" name="name" class="form-control bulk-name" />
                </div>
                <div class="form-group">
                    {{ __('Phone') }}:&nbsp;
                    <input required type="text" name="name" class="form-control bulk-phone" />
                </div>
                <div class="form-group">
                    {{ __('Total offer') }}:&nbsp;
                    <input required type="number" name="offer" class="form-control bulk-total-offer" />
                </div>

                <button class="btn bulkSendOffer" data-url="{{ route('frontend.page', ['pageSlug' => 'bulk', 'locale' => \App::getLocale()]) }}">{{ __('Send offer') }}</button>
            </form>
        </div>
    </div>
@endsection
