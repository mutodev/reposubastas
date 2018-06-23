@extends('layouts.app')

@section('title', "{$event->name} - Import CSV")

@section('content')
    {!! form($form) !!}
@endsection
