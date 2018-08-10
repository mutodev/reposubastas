@extends('layouts.app')

@section('title', ($model ? __('Edit Bid') : __('New Bid')))

@section('content')
    {!! form($form) !!}
@endsection
