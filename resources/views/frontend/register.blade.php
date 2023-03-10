@extends('frontend.base')

@section('content_top')
    <div class="bg-light-grey p-4">
        <div class="container">
            <div>
                <h1 class="text-center display-6 text-uppercase m-0">{{ __('Sign Up') }}</h1>
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

            <div class="text-center mb-4">
                {{ __('Enter your data so you can bid on events and easily navigate our website, as well as keep you informed of new opportunities.') }}
            </div>

            <div class="col-sm-6 mx-auto">
                {!! form($form) !!}
            </div>
        </div>
    </div>
@endsection
