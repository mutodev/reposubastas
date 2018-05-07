@extends('frontend.base')

@section('content_top')
    <div class="bg-light-grey p-4">
        <div class="container">
            <div class="w-50">
                {!! form($form) !!}
            </div>
        </div>
    </div>
@endsection
