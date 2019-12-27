@extends('layouts.app')

@section('title',  ($model ? $model->name . " - " : '') . 'Offers')

@section('toolbar')

@endsection

@section('content')
    <div class="position-relative overflow-hidden text-center">
        <form method="get" class="form-inline" action="{{ route('backend.users.offers') }}">
            <div class="form-group mb-2 mx-3">
                <label class="mr-1" for="event">Event</label>
                <select class="form-control" name="event">
                    @foreach (App\Models\Event::forSelect() as $eventId => $name)
                        <option @if(request()->get('event') == $eventId) selected @endif value="{{ $eventId }}">
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

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
                <label class="mr-1" for="investor">Type</label>
                <select class="form-control" name="type">
                    <option value="">
                        -- Select One --
                    </option>
                    <option @if(request()->get('type') == 'Financed') selected @endif value="Financed">
                        Financed
                    </option>
                    <option @if(request()->get('type') == 'Cash') selected @endif value="Cash">
                        Cash
                    </option>
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
                {{ __('Offer') }}
            </th>
            <th>
                {{ __('Property') }}
            </th>
            <th>
                {{ __('Date') }}
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($models as $bid)
            <tr>
                <td>{{ $bid->user }} <br />{{ $bid->user_phone }} - {{ $bid->user_email }}</td>
                <td>${{ number_format($bid->offer) }} - {{ $bid->type }}</td>
                <td>[{{ $bid->investor }}] {{ $bid->property_id }} - {{ $bid->address }}, {{ $bid->city }}</td>
                <td>{{ \Carbon\Carbon::parse($bid->created_at)->format('d/m/Y h:i A')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $models->appends(request()->query())->links() }}
@endsection
