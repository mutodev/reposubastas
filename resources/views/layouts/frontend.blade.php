@extends('layouts.base')

@section('stylesheets')
    <link href="{{ asset('css/frontend.css', false) }}?v3" rel="stylesheet">
@endsection

@section('main')
    <nav class="site-header sticky-top py-1">
        <div class="container d-flex flex-column flex-md-row justify-content-between">
            <a class="py-2" href="{{ route('frontend.page', ['locale' => App::getLocale()]) }}">
                <img src="{{ asset('images/logo.png', false) }}" height="45" />
            </a>
            <div class="float-right">
                <a class="site-header-item py-2 d-inline-block mr-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'properties']) }}">{{ __('Properties') }}</a>
                <a class="site-header-item py-2 d-inline-block mr-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'contact']) }}">{{ __('Contact Us') }}</a>

                @if (Auth::guest())
                    <a class="site-header-item py-2 d-inline-block mr-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'register']) }}"><strong class="text-danger">{{ __('Sign Up') }}</strong></a>
                    <a class="site-header-item py-2 d-inline-block mr-3" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'login']) }}">{{ __('Sign In') }}</a>
                @else
                    <a class="site-header-item py-2 d-inline-block mr-3" href="/logout">{{ __('Logout') }}</a>
                @endif
                <div class="btn-group">
                    <button class="btn bg-light-blue btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ App::getLocale() == 'es' ? __('Español') : __('English') }}
                    </button>
                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 31px, 0px);">
                        @if (App::getLocale() == 'en')
                            <a class="dropdown-item" href="{{ route('frontend.page', ['locale' => 'es']) }}">{{ __('Español') }}</a>
                        @else
                            <a class="dropdown-item" href="{{ route('frontend.page', ['locale' => 'en']) }}">{{ __('English') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    @yield('sub_header')

    @yield('content')

    <footer class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md">
                    <img src="{{ asset('images/logo.png', false) }}" height="45" />
                    <small class="d-block mb-3 text-muted">&copy; {{ date('Y') }}</small>
                    <small class="d-block text-muted">{{ __('Phones') }}:</small>
                    <span class="text-muted"><a href="tel:7874183100">(787) 418-3100</a></span>
                    <small class="d-block mt-3 text-muted">{{ __('Address') }}:</small>
                    <span class="text-muted">1253 Ave. Fernandez Juncos, Esq. Roberto H. Todd, Pda. 18 Santurce, PR</span>

                </div>
                <div class="col-6 col-md">
                    <h5>{{ __('Navigate') }}</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'properties']) }}">{{ __('Properties') }}</a></li>
                        <li><a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'contact']) }}">{{ __('Contact Us') }}</a></li>
                        @if (Auth::guest())
                            <li><a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'register']) }}">{{ __('Sign Up') }}</a></li>
                            <li><a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'login']) }}">{{ __('Sign In') }}</a></li>
                        @else
                            <li><a class="text-muted" href="/logout">{{ __('Logout') }}</a></li>
                        @endif

                        <li><a class="text-muted" href="{{ route('backend.events.index') }}">{{ __('Administration') }}</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md">
                    <h5>{{ __('About') }}</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'privacy']) }}">{{ __('Privacy') }}</a></li>
                        <li><a class="text-muted" href="{{ route('frontend.page', ['locale' => App::getLocale(), 'pageSlug' => 'terms']) }}">{{ __('Terms') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
@endsection

@section('footer_scripts')
    <script type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5320741e1495deeb"></script>
    <script src="{{ asset('js/app.js', false) }}?v4" defer></script>
@endsection
