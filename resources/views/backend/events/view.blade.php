@extends('layouts.app')

@section('title', $model->name)

@section('toolbar')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ __('Actions') }}
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ route('backend.users.edit') }}?event_id={{ $model->id }}">{{ __('Add New User') }}</a>
                <a class="dropdown-item" href="{{ route('backend.properties.edit', ['event' => $model->id]) }}">{{ __('Add New Property') }}</a>
                <a target="_blank" class="dropdown-item" href="{{ route('backend.reports.report', ['event' => $model->id]) }}">
                    {{ __('Report')  }}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <h4>{{ __('Stats') }}</h4>

    <table class="table">
        <tbody>
            <tr>
                <th>Sum of bids</th>
                <td>${{ number_format($bidsTotal) }}</td>
            </tr>
            @foreach($byStatus as $status => $total)
                <tr>
                    <th>{{ $status }}</th>
                    <td>{{ number_format($total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>{{ __('Users') }}</h4>

    <table class="table">
        <thead>
        <tr>
            <th>
                {{ __('Name') }}
            </th>
            <th>
                {{ __('Number') }}
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($model->users()->get() as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>
                    <form class="form" action="{{ route('backend.users.assign_number', ['model' => $user->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $model->id }}" />

                        <input class="form-control float-left" style="width: auto" type="number" name="number" value="{{ $user->pivot->number }}" />
                        <button class="btn btn-primary float-left ml-2" type="submit">{{ $user->pivot->number ? __('Update') : __('Assign') }}</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
