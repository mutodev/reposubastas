@extends('layouts.app')

@section('title', "{$event->name} - Property #{$modelEvent->number}" )

@section('toolbar')
@endsection

@section('content')
    <div class="row">
        <div class="col text-right mb-3">
            <div class="d-inline dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('Suspense') }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <button data-url="{{ route('backend.properties.auction', ['model' => $model->id, 'event' => $event->id, 'suspense' => 1]) }}" class="suspense dropdown-item">
                        {{ __('Start') }}
                    </button>
                    <button data-url="{{ route('backend.properties.auction', ['model' => $model->id, 'event' => $event->id, 'suspense' => 0]) }}" class="suspense dropdown-item">
                        {{ __('Stop') }}
                    </button>
                </div>
            </div>
            <button data-url="{{ route('backend.properties.auction', ['model' => $model->id, 'event' => $event->id, 'celebrate' => 1]) }}" class="celebrate dropdown-item">
                {{ __('Celebrate') }}
            </button>
            <div class="d-inline dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('Close Auction') }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    @foreach(App\Models\PropertyStatus::forSelect() as $value => $label)
                        @if($loop->first) @continue @endif
                        <a  class="dropdown-item" href="{{ route('backend.properties.auction.finish', ['model' => $model->id, 'event' => $event->id, 'status_id' => $value]) }}">
                            {{ $label  }}
                        </a>
                    @endforeach
                </div>
            </div>
            <a class="d-inline btn btn-outline-danger" href="{{ route('backend.properties.auction.next', ['event' => $event->id, 'number' => $modelEvent->number, 'operator' => '<']) }}">
                {{ __('Prev') }}
            </a>
            &nbsp;
            <a class="d-inline btn btn-outline-success" href="{{ route('backend.properties.auction.next', ['event' => $event->id, 'number' => $modelEvent->number]) }}">
                {{ __('Next') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm">
            <div class="card">
                <div class="card-header">
                    {{ __('Offers') }}
                </div>

                <ul class="list-group">
                    @foreach ($bids as $bid)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>(Paleta #{{ $bid->number }}) {{ $bid->name }}</strong>
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
