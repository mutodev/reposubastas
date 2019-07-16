@extends('layouts.app')

@section('title',  ($model ? $model->name . " - " : '') . 'Deposits')

@section('toolbar')

@endsection

@section('content')
    <div class="position-relative overflow-hidden text-center bg-light">
        <form class="form-inline" method="get" action="{{ route('backend.users.deposits') }}">
            <div class="form-group mb-2 mx-3">
                <label class="mr-1">User</label>
                <input type="text" value="{{ request()->get('user') }}" class="form-control" name="user" />
            </div>

            <div class="form-group mb-2 mx-3">
                <label class="mr-1" for="investor">Investor</label>
                <select class="form-control" name="investor">
                    @foreach (App\Models\Investor::forSelect() as $investorId => $name)
                        <option @if(request()->get('investor') == $investorId) selected @endif value="{{ $investorId }}">
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-2 mx-3">
                <label class="mr-1">Date from</label>
                <input type="date" value="{{ request()->get('date_from') }}" class="form-control" name="date_from" />
            </div>

            <div class="form-group mb-2 mx-3">
                <label class="mr-1">Date to</label>
                <input type="date" value="{{ request()->get('date_to') }}" class="form-control" name="date_to" />
            </div>

            <button type="submit" class="btn btn-primary bg-light-red border-0">{{ __('Search') }}</button>
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
                {{ __('Property') }}
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
                <td>{{ $deposit->user }}</td>
                <td>${{ number_format($deposit->amount) }}</td>
                <td>[{{ $deposit->investor }}] {{ $deposit->property_id }} - {{ $deposit->address }}, {{ $deposit->city }}</td>
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
