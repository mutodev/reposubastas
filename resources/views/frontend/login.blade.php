@extends('frontend.base')

@section('content_top')
    <div class="bg-light-grey p-4">
        <div class="container">
            <div>
                <h1 class="text-center display-6 text-uppercase m-0">{{ __('Sign In') }}</h1>
            </div>
        </div>
    </div>

    <div class="py-4">
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

            <div class="col-sm-6 mx-auto">
                {!! form($form) !!}
            </div>
        </div>
    </div>
@endsection
