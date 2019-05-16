@extends('layouts.app')

@section('title', ($event ? "{$event->name} - " : '') .__('Users'))

@section('toolbar')
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ \App\User::url('edit', null, @$event->id) }}" class="btn btn-sm btn-outline-primary">
            {{ __('Add New User')  }}
        </a>
    </div>
@endsection

@section('content')
    <div class="properties-search position-relative overflow-hidden text-center bg-light">
        <form method="get" action="{{ \App\User::url('index', null, @$event->id) }}">
            <div class="input-group mr-sm-2">
                <input value="{{ request()->get('keywords') }}" name="keywords" type="text" class="form-control w-50" id="keywords" placeholder="{{ __('Name') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary bg-light-red border-0">{{ __('Search') }}</button>
                </div>
            </div>
        </form>
    </div>

    @if($allmodels->count())
    <table class="table mb-3">
        <thead>
        <tr>
            <th>
                {{ __('Name') }}
            </th>
            <th>
                {{ __('Actions') }}
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($allmodels as $model)
            <tr>
                <td width="100%">{{ $model->name }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Actions') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a  class="dropdown-item" href="{{ \App\User::url('register-to-event', $model->id, $event->id) }}">
                                {{ __('Register to Event')  }}
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif

    <div class="my-2 mx-2">
        {{ __('Total:') }} {{ $models->total()  }}
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                @if ($event)
                <th>
                    {{ __('Number (# Paleta)') }}
                </th>
                @endif
                <th>
                    {{ __('Client') }}
                </th>
                <th>
                    {{ __('Spouse') }}
                </th>
                <th>
                    {{ __('Broker') }}
                </th>
                <th>
                    {{ __('Email') }}
                </th>
                <th>
                    {{ __('Phone') }}
                </th>
                @if ($event)
                <th>
                    {{ __('Active') }}
                </th>
                @endif
                <th>
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $model)
                <tr>
                    @if ($event)
                    <td width="150">
                        {{ $model->number }}
                    </td>
                    @endif
                    <td>{{ $model->name }}</td>
                    <td>{{ $model->spouse_name }}</td>
                    <td>{{ $model->broker_name }}</td>
                    <td>{{ $model->email }}</td>
                    <td>{{ $model->phone }}</td>
                    @if ($event)
                    <td>
                        {{ $model->event_is_active ? __('Yes') : __('No') }}
                    </td>
                    @endif
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Actions') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a  class="dropdown-item" href="{{ \App\User::url('edit', @$model->id, @$event->id) }}">
                                    {{ __('Edit')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ \App\User::url('deposits', @$model->id, @$event->id) }}">
                                    {{ __('Offers \ Deposits')  }}
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
