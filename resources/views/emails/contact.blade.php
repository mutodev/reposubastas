@extends('layouts.email')

@section('content')
    @foreach($contact as $field => $value)
        {{ ucfirst(str_replace('_', ' ', $field)) }}: {{ $value }}<br />
    @endforeach
@endsection
