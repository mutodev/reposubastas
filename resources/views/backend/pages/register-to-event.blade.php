@extends('layouts.app')

@section('title', "{$model->name} - " . __('Register to event'))

@section('content')
    {!! form($form) !!}
@endsection
