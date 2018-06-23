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
                <a  class="dropdown-item" href="{{ route('backend.properties.importcsv', ['event' => $event->id]) }}">
                    {{ __('Add Properties From CSV')  }}
                </a>
                <a target="_blank" class="dropdown-item" href="{{ route('backend.properties.pdf', ['event' => $event->id, 'locale' => 'es']) }}">
                    {{ __('Generate PDF (Spanish)') }}
                </a>
                <a target="_blank" class="dropdown-item" href="{{ route('backend.properties.pdf', ['event' => $event->id, 'locale' => 'en']) }}">
                    {{ __('Generate PDF (English)') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="properties-search position-relative overflow-hidden text-center bg-light">
        <form method="get" action="{{ route('backend.properties.index', ['event' => $event->id]) }}">
            <div class="input-group mr-sm-2">
                <select name="status" class="custom-select">
                    @foreach(App\Models\PropertyStatus::forSelect('-- Status --') as $value => $label)
                        <option @if($value == request()->get('status')) selected @endif value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select name="investor" class="custom-select">
                    @foreach(App\Models\Investor::forSelect('-- Investor --') as $value => $label)
                        <option @if($value == request()->get('investor')) selected @endif value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <input value="{{ request()->get('keywords') }}" name="keywords" type="text" class="form-control w-50" id="keywords" placeholder="{{ __('Address, city, Property ID') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary bg-light-red border-0">{{ __('Search') }}</button>
                </div>
            </div>
        </form>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>
                    {{ __('Number') }}
                </th>
                <th>
                    {{ __('Type') }}
                </th>
                <th>
                    {{ __('Address') }}
                </th>
                <th>
                    {{ __('Status') }}
                </th>
                <th>
                    {{ __('Start At') }}
                </th>
                <th>
                    {{ __('End At') }}
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
                    <td>{{ $model->type->name }}</td>
                    <td>{{ $model->address }}, {{ $model->city }}</td>
                    <td>@if($model->status_id){{ $model->status->name }}@endif</td>
                    <td>
                        {{ Jenssegers\Date\Date::parse($model->start_at)->format('j M Y, g:ia')}}
                    </td>
                    <td>
                        {{ Jenssegers\Date\Date::parse($model->end_at)->format('j M Y, g:ia')}}
                    </td>
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
