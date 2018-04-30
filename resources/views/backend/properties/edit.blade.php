@extends('layouts.app')

@section('title', "{$event->name} - ". ($model ? __('Edit Property') : __('New Property')))

@section('content')
    {!! form($form) !!}
@endsection
