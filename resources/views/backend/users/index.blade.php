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
    <table class="table">
        <thead>
            <tr>
                @if ($event)
                <th>
                    {{ __('Number') }}
                </th>
                @endif
                <th>
                    {{ __('Name') }}
                </th>
                @if ($event)
                <th>
                    {{ __('Deposit') }}
                </th>
                <th>
                    {{ __('Remaining') }}
                </th>
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
                    <td width="1">
                        {{ $model->number }}
                    </td>
                    @endif
                    <td width="100%">{{ $model->name }}</td>
                    @if ($event)
                    <td>{{ number_format($model->original_deposit) }}</td>
                    <td>{{ number_format($model->remaining_deposit) }}</td>
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
                                @if (!$model->hasRole('System Admin'))
                                    <a  class="dropdown-item" href="{{ \App\User::url('register-to-event', $model->id) }}">
                                        {{ __('Register to Event')  }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $models->links() }}
@endsection
