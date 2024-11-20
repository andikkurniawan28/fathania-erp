@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'adjust_permission')) }}
@endsection

@section('permission-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('permission.index') }}">{{ ucwords(str_replace('_', ' ', 'permission')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('permission.update', $role->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="role">
                                    {{ ucwords(str_replace('_', ' ', 'role')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="role" name="role" value="{{ $role->name }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label
                                    class="col-sm-2 col-form-label">{{ ucwords(str_replace('_', ' ', 'permissions')) }}</label>
                                <div class="col-sm-10">
                                    <!-- Checkbox Select All -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select_all">
                                        <label class="form-check-label" for="select_all">
                                            Select All
                                        </label>
                                    </div>
                                    <hr>
                                    <!-- Checkbox for each feature -->
                                    @foreach ($features as $feature)
                                        @php
                                            $isChecked = $permissions->contains('feature_id', $feature->id);
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input feature-checkbox" type="checkbox"
                                                name="feature_ids[]" value="{{ $feature->id }}"
                                                id="feature_{{ $feature->id }}" {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                {{ $feature->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Select All functionality -->
    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.feature-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
