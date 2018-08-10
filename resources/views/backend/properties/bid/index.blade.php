@extends('layouts.app')

@section('title', "{$event->name} - Property #{$modelEvent->number}" )

@section('toolbar')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm">
            <div class="card">
                <div class="card-header">
                    {{ __('Offers') }}
                </div>

                <ul class="list-group">
                    @foreach ($bids as $bid)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @if (!$bid->user_id)
                                <a class="btn btn-sm btn-primary" href="{{ route('backend.properties.bid.edit', ['model' => $model->id, 'event' => $event->id, 'bid_id' => $bid->id]) }}" >
                                    {{ __('Assign Number (# Paleta)') }}
                                </a>
                            @else
                                <strong>(Paleta #{{ $bid->number }}) {{ $bid->name }}</strong>
                            @endif
                            {{--@if ($bid->is_winner)--}}
                            {{--<span class="badge badge-success badge-pill">{{ __('winner') }}</span>--}}
                            {{--@endif--}}
                            <strong>${{ number_format($bid->offer) }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <div class="card-header">
                    {{ __('Make Offer') }}
                </div>

                <div class="card-body">
                    {!! form($form) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm">
            <div class="card">
                <div class="card-header">
                    {{ __('Users Registered') }}
                </div>

                <ul class="list-group">
                    @foreach ($users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            (Paleta #{{ $user->number }}) {{ $user->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
