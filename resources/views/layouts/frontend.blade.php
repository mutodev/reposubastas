@extends('layouts.base')

@section('stylesheets')
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet">
@endsection

@section('main')
    <nav class="site-header sticky-top py-1">
        <div class="container d-flex flex-column flex-md-row justify-content-between">
            <a class="py-2" href="{{ route('frontend.page', ['locale' => App::getLocale()]) }}">
                <img src="{{ asset('images/logo.png') }}" width="160" />
            </a>
            <div class="float-right">
                <a class="py-2 d-inline-block mr-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'properties']) }}">{{ __('Properties') }}</a>
                <a class="py-2 d-inline-block mr-3" href="#">{{ __('Contact') }}</a>

                @if (Auth::guest())
                    <a class="py-2 d-inline-block mr-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'register']) }}">{{ __('Sign Up') }}</a>
                    <a class="py-2 d-inline-block" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'login']) }}">{{ __('Sign In') }}</a>
                @else
                    <a class="py-2 d-inline-block" href="/logout">{{ __('Logout') }}</a>
                @endif
            </div>
        </div>
    </nav>

    @yield('sub_header')

    @yield('content')

    <footer class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md">
                    <img src="{{ asset('images/logo.png') }}" width="160" />
                    <small class="d-block mb-3 text-muted">&copy; 2017-2018</small>
                </div>
                <div class="col-6 col-md">
                    <h5>Features</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Cool stuff</a></li>
                        <li><a class="text-muted" href="#">Random feature</a></li>
                        <li><a class="text-muted" href="#">Team feature</a></li>
                        <li><a class="text-muted" href="#">Stuff for developers</a></li>
                        <li><a class="text-muted" href="#">Another one</a></li>
                        <li><a class="text-muted" href="#">Last time</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md">
                    <h5>Resources</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Resource</a></li>
                        <li><a class="text-muted" href="#">Resource name</a></li>
                        <li><a class="text-muted" href="#">Another resource</a></li>
                        <li><a class="text-muted" href="#">Final resource</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md">
                    <h5>About</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Team</a></li>
                        <li><a class="text-muted" href="#">Locations</a></li>
                        <li><a class="text-muted" href="#">Privacy</a></li>
                        <li><a class="text-muted" href="#">Terms</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
@endsection
