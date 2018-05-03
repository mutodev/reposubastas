@extends('layouts.base')

@section('main')
    <div class="mt-3">
        <live-component :auction="auction"></live-component>
    </div>
@endsection
