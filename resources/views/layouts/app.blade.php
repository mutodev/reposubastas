@extends('layouts.base')

@section('stylesheets')
    <link href="{{ asset('css/backend.css') }}" rel="stylesheet">
@endsection

@section('main')
    @if (!Auth::guest())
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">{{ config('app.name') }}</a>
        {{--<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">--}}
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Sign out') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
    @endif
    <div class="container-fluid">
        <div class="row">
            @if (!Auth::guest())
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    {!! $SidebarMenu->asUl(['class' => 'nav flex-column']) !!}
                </div>
            </nav>
            @endif

            <main role="main" class="@if (!Auth::guest()) col-md-9 ml-sm-auto col-lg-10 px-4 @else col-12 @endif">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('title')</h1>
                    @yield('toolbar')
                </div>

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

                <div class="mb-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
@endsection
