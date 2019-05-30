@extends('layouts.app')

@section('title',  ($model ? $model->name . " - " : '') . 'Deposits')

@section('toolbar')

@endsection

@section('content')
    <div class="position-relative overflow-hidden text-center bg-light">
        <form method="get" action="{{ route('backend.users.deposits') }}">
            <div class="input-group mr-sm-2">
                <input type="date" value="{{ request()->get('date_from') }}" class="form-control" name="date_from" />
                <input type="date" value="{{ request()->get('date_to') }}" class="form-control" name="date_to" />
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary bg-light-red border-0">{{ __('Search') }}</button>
                </div>
            </div>
        </form>
    </div>

    <table class="table table-bordered my-3">
        <thead>
        <tr>
            <th>
                {{ __('User') }}
            </th>
            <th>
                {{ __('Amount') }}
            </th>
            <th>
                {{ __('Date') }}
            </th>
            <th>
                {{ __('Refund') }}
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($models as $deposit)
            <tr>
                <td>{{ $deposit->name }}</td>
                <td>${{ number_format($deposit->amount) }}</td>
                <td>{{ \Carbon\Carbon::parse($deposit->created_at)->format('d/m/Y h:i A')}}</td>
                <td>@if ($deposit->refunded)
                        Refunded
                    @else
                        <a  class="btn btn-primary" href="{{ \App\User::url('deposits', @$model->id, @$event->id) }}?deposit_id={{ $deposit->id }}">
                            {{ __('Refund')  }}
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $models->appends(request()->query())->links() }}
@endsection
