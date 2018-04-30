@extends('layouts.app')

@section('title', $model ? __('Edit Event') : __('New Event'))

@section('content')
    {!! form($form) !!}
@endsection
