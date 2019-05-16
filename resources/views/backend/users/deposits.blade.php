@extends('layouts.app')

@section('title',  ($model ? $model->name . " - " : '') . 'Offers / Deposits')

@section('toolbar')

@endsection

@section('content')
    <div class="row">
        <div class="col-sm">
            <div class="card">
                <div class="card-header">
                    {{ __('Offers') }}
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>
                                {{ __('User') }}
                            </th>
                            <th>
                                {{ __('Property') }}
                            </th>
                            <th>
                                {{ __('Offer') }}
                            </th>
                            <th>
                                {{ __('Date') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($bids as $bid)
                            <tr>
                                <td>{{ $bid->name }}</td>
                                <td>{{ $bid->address }}</td>
                                <td>${{ number_format($bid->offer) }}</td>
                                <td>{{ \Carbon\Carbon::parse($bid->created_at)->format('d/m/Y h:i A')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <div class="card-header">
                    {{ __('Deposits') }}
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
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
                </div>
            </div>
        </div>
    </div>
@endsection
