@extends('layouts.app')

@section('title', __('Events'))

@section('toolbar')
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('backend.events.edit') }}" class="btn btn-sm btn-outline-primary">
            {{ __('Add New Event')  }}
        </a>
    </div>
@endsection

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>
                    {{ __('Name') }}
                </th>
                <th>
                    {{ __('Start At') }}
                </th>
                <th>
                    {{ __('End At') }}
                </th>
                <th>
                    {{ __('Active') }}
                </th>
                <th>
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $model)
                <tr>
                    <td width="60%">{{ $model->name }}</td>
                    <td>
                        @if($model->start_at){{ Jenssegers\Date\Date::parse($model->start_at)->format('j M Y, g:ia')}}@endif
                    </td>
                    <td>
                        @if($model->end_at){{ Jenssegers\Date\Date::parse($model->end_at)->format('j M Y, g:ia')}}@endif
                    </td>
                    <td>
                        {{ $model->is_active ? __('Yes') : __('No') }}
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Actions') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a  class="dropdown-item" href="{{ route('backend.events.view', ['id' => $model->id]) }}">
                                    {{ __('View')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ route('backend.properties.index', ['event' => $model->id]) }}">
                                    {{ __('Properties')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ \App\User::url('index', null, @$model->id) }}">
                                    {{ __('Users')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ route('backend.events.edit', ['id' => $model->id]) }}">
                                    {{ __('Edit')  }}
                                </a>
                                <a  class="dropdown-item" target="_blank" href="{{ route('backend.event.live', ['model' => $model->id]) }}">
                                    {{ __('Live')  }}
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
