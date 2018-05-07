@extends('layouts.app')

@section('title', __('Users'))

@section('toolbar')
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('backend.pages.edit') }}" class="btn btn-sm btn-outline-primary">
            {{ __('Add New Page')  }}
        </a>
    </div>
@endsection

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>
                    {{ __('Title') }}
                </th>
                <th>
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $model)
                <tr>
                    <td width="100%">{{ $model->title }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Actions') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a  class="dropdown-item" href="{{ route('backend.pages.edit', ['model' => $model->id]) }}">
                                    {{ __('Edit')  }}
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
