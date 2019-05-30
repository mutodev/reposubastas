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
                <a target="_blank" class="dropdown-item" href="http://pdfmyurl.com/saveaspdf?url={{ urlencode(route('frontend.page', ['pageSlug' => 'properties', 'locale' => 'es', 'pdftest' => 1, 'event_type' => 'LIVE'])) }}">
                    {{ __('Generate PDF (Spanish)') }}
                </a>
                <a target="_blank" class="dropdown-item" href="http://pdfmyurl.com/saveaspdf?url={{ urlencode(route('frontend.page', ['pageSlug' => 'properties', 'locale' => 'en', 'pdftest' => 1, 'event_type' => 'LIVE'])) }}">
                    {{ __('Generate PDF (English)') }}
                </a>
                <a target="_blank" class="dropdown-item" href="http://pdfmyurl.com/saveaspdf?url={{ urlencode(route('frontend.page', ['pageSlug' => 'properties', 'locale' => 'en', 'pdftest' => 1, 'admin' => 1, 'event_type' => 'LIVE'])) }}">
                    {{ __('Generate Admin PDF (English)') }}
                </a>
                <a target="_blank" class="dropdown-item" href="http://pdfmyurl.com/saveaspdf?url={{ urlencode(route('frontend.page', ['pageSlug' => 'properties', 'locale' => 'en', 'pdftest' => 1, 'columns' => 1, 'event_type' => 'LIVE'])) }}">
                    {{ __('Generate Simple PDF (English)') }}
                </a>
                <a target="_blank"  class="dropdown-item" href="{{ route('backend.reports.report', ['event' => $event->id]) }}">
                    {{ __('Report')  }}
                </a>
                <button class="dropdown-item select" data-clear="clear" data-url="{{ route('backend.properties.select', ['event' => $event->id, 'model' => null, 'clear' => 'clear']) }}">
                   {{ __('Clear Selection') }}
                </button>
                <a target="_blank"  class="dropdown-item" href="{{ route('backend.reports.report', ['event' => $event->id]) }}">
                    {{ __('Print Selection')  }}
                </a>
                <a target="_blank" class="dropdown-item" href="{{ route('backend.properties.print.select', ['event' => $event->id, 'lang' => 'es']) }}">
                    {{ __('Generate Selection PDF (Spanish)') }}
                </a>
                <a target="_blank" class="dropdown-item" href="{{ route('backend.properties.print.select', ['event' => $event->id, 'lang' => 'en']) }}">
                    {{ __('Generate Selection PDF (English)') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="properties-search position-relative overflow-hidden text-center bg-light">
        <form method="get" action="{{ route('backend.properties.index', ['event' => $event->id]) }}">
            <div class="input-group mr-sm-2">
                <select name="type" class="custom-select">
                    @foreach(App\Models\PropertyType::forSelect('-- Type --') as $value => $label)
                        <option @if($value == request()->get('type')) selected @endif value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
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

    <div class="my-2 mx-2">
        {{ __('Total:') }} {{ $models->total() }}
    </div>

    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>Sum of bids</th>
            @foreach($byStatus as $status => $total)
                <th>{{ $status }}</th>
            @endforeach
        </tr>
        <tr>
            <td>${{ number_format($bidsTotal) }}</td>
            @foreach($byStatus as $statusItem)
                <td>{{ number_format($statusItem['total']) }} @if($statusItem['sum']) (${{number_format($statusItem['sum'])}}) @endif</td>
            @endforeach
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered mt-3">
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
                <tr class="{{ in_array($model->id, $selected) ? 'selected' : '' }}">
                    <td width="1">
                        {{ $model->number }}
                    </td>
                    <td>{{ $model->type->name }}</td>
                    <td>{{ $model->address }}, {{ $model->city }}</td>
                    <td>@if($model->status_id){{ $model->status->name }}@else{{__('Available')}}@endif</td>
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
                                <a  class="dropdown-item" href="{{ route('backend.properties.add-tag', ['event' => $event->id, 'model' => $model->id]) }}">
                                    {{ __('Tag')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ route('backend.properties.photos', ['event' => $event->id, 'model' => $model->id]) }}">
                                    {{ __('Photos')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ route('backend.properties.logs', ['event' => $event->id, 'model' => $model->id]) }}">
                                                                        {{ __('Logs')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ route('backend.properties.auction', ['event' => $event->id, 'model' => $model->id]) }}">
                                    {{ __('Live')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ route('backend.properties.bid.index', ['event' => $event->id, 'model' => $model->id]) }}">
                                    {{ __('Bids')  }}
                                </a>
                                <a  class="dropdown-item" href="{{ route('backend.properties.register-to-event', ['event' => $event->id, 'model' => $model->id]) }}">
                                    {{ __('Add to other Event')  }}
                                </a>
                                <a onclick="return confirm('Are you sure you want to delete this item?');" class="dropdown-item" href="{{ route('backend.properties.delete', ['event' => $event->id, 'model' => $model->id]) }}">
                                    {{ __('Delete')  }}
                                </a>
                                <button class="dropdown-item select" data-url="{{ route('backend.properties.select', ['event' => $event->id, 'model' => $model->id]) }}">
                                   {{ __('Select / Unselect') }}
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $models->appends(request()->query())->links() }}
@endsection
