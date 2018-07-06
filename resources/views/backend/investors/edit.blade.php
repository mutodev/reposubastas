@extends('layouts.app')

@section('title', ($model ? __('Edit Investor') : __('New Investor')))

@section('content')
    {!! form($form) !!}
@endsection
