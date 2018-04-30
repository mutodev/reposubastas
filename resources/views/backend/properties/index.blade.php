@extends('layouts.app')

@section('title', "{$event->name} - ". __('Properties'))

@section('toolbar')
    <div class="btn-toolbar mb-2 mb-md-0">

        <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ __('Actions') }}
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a  class="dropdown-item" href="{{ route('backend.properties.edit', ['event' => $event->id]) }}">
                    {{ __('Add New Property')  }}
                </a>
                <a target="_blank" class="dropdown-item" href="{{ route('backend.properties.pdf', ['event' => $event->id]) }}">
                    {{ __('Generate PDF') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <table class="table mt-3">
        <thead>
            <tr>
                <th>
                    {{ __('Number') }}
                </th>
                <th>
                    {{ __('Address') }}
                </th>
                <th>
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $model)
                <tr>
                    <td width="1">
                        {{ $model->events[0]->pivot->number }}
                    </td>
                    <td width="100%">{{ $model->address }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Actions') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a  class="dropdown-item" href="{{ route('backend.properties.edit', ['event' => $event->id, 'model' => $model->id]) }}">
                                    {{ __('Edit')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ route('backend.properties.auction', ['event' => $event->id, 'model' => $model->id]) }}">
                                    {{ __('Start Auction')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ route('backend.properties.register-to-event', ['event' => $event->id, 'model' => $model->id]) }}">
                                    {{ __('Add to other Event')  }}
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $models->links() }}
@endsection
