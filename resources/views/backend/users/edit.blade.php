@extends('layouts.app')

@section('title', "{$event->name} - " . ($model ? __('Edit User') : __('New User')))

@section('content')
    {!! form($form) !!}
@endsection
