@extends('frontend.base')

@section('sub_header')
    @include('frontend.partials.search')
@endsection

@section('content_top')
    @if($event)
    <div class="bg-light-blue text-center p-2 display-6 text-uppercase">
        <strong>{{ __('NEXT EVENT') }}:</strong> {{ Jenssegers\Date\Date::parse($event->start_at)->format('l j F Y') }}
    </div>
    @endif
@endsection
