@extends('layouts.app')

@section('title', "{$event->name} - Property #{$modelEvent->number}" )

@section('toolbar')
    @if ($bids && !$winner)
        <div class="dropdown">
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
    @else
        <a class="btn btn-outline-success" href="{{ route('backend.properties.auction.next', ['event' => $event->id, 'number' => $modelEvent->number]) }}">
            {{ __('Next Property Auction') }}
        </a>
    @endif
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
                            #{{ $bid->number }} - {{ $bid->name }}
                            @if ($bid->is_winner)
                                <span class="badge badge-success badge-pill">{{ __('winner') }}</span>
                            @endif
                            <span class="badge badge-success badge-pill">${{ number_format($bid->offer) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @if (!$winner)
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
        @endif
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
                            #{{ $user->number }} - {{ $user->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
