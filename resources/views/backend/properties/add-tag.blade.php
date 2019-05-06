@extends('layouts.app')

@section('title', "{$model->address} - " . __('Add Tag'))

@section('content')
    {!! form($form) !!}
@endsection
