@extends('layouts.app')

@section('title', "{$event->name} - " . __('Add deposit'))

@section('content')
    {!! form($form) !!}
@endsection
