@extends('layouts.app')

@section('title', "{$event->name} - ". ($model->address) . ' - Logs')

@section('content')

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>
                    {{ __('Date') }}
                </th>
                <th>
                    {{ __('New Status') }}
                </th>
                <th>
                    {{ __('Old Status') }}
                </th>
                <th>
                    {{ __('Optioned By') }}
                </th>
                <th>
                    {{ __('Cancel Reason') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $log)
                <tr>
                    <td>
                        {{ Jenssegers\Date\Date::parse($log->created_at)->format('j M Y, g:ia')}}
                    </td>
                    <td>{{ $log->newStatus->name }}</td>
                    <td>@if($log->old_status_id){{ $log->oldStatus->name }}@endif</td>
                    <td>{{ $log->optionedBy->name }}</td>
                    <td>{{ @json_decode($log->payload, true)['cancel_reason'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
