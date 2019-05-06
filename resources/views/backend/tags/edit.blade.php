@extends('layouts.app')

@section('title', ($model ? __('Edit Tag') : __('New Tag')))

@section('content')
    {!! form($form) !!}
@endsection
