@extends('layouts.frontend')

@section('page_title', $page->title)

@section('content')
    @yield('content_top')

    {!! $page->content !!}
@endsection
