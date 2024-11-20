@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'permission')) }}
@endsection

@section('permission-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="example" width="100%">
                        <thead>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'role')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'feature')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @foreach ($role->permission as $permission)
                                            <li>{{ $permission->feature->name }}</li>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="manage">
                                            <a href="{{ route('permission.adjust', $role->id) }}"
                                                class="btn btn-secondary btn-sm">Adjust</a>
                                        </div>
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
