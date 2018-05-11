@extends('layouts.email')

@section('content')
    @foreach($contact as $field => $value)
        {{ ucfirst($field) }}: {{ $value }}<br />
    @endforeach
@endsection
