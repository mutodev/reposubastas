@extends('layouts.app')

@section('title', ($model ? __('Edit Page') : __('New Page')))

@section('content')
    {!! form($form) !!}
@endsection
