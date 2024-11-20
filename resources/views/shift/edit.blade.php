@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_shift')) }}
@endsection

@section('shift-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shift.index') }}">{{ ucwords(str_replace('_', ' ', 'shift')) }}</a></li>
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
                        <form action="{{ route('shift.update', $shift->id) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $shift->name) }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="start">
                                    {{ ucwords(str_replace('_', ' ', 'start')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" id="start" name="start" value="{{ $shift->start }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="finish">
                                    {{ ucwords(str_replace('_', ' ', 'finish')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" id="finish" name="finish" value="{{ $shift->finish }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="start_break">
                                    {{ ucwords(str_replace('_', ' ', 'start_break')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" id="start_break" name="start_break" value="{{ $shift->start_break ?? null }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="finish_break">
                                    {{ ucwords(str_replace('_', ' ', 'finish_break')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" id="finish_break" name="finish_break" value="{{ $shift->finish_break ?? null }}">
                                </div>
                            </div>

                            {{-- <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="delta_day_of_start">
                                    {{ ucwords(str_replace('_', ' ', 'delta_day_of_start')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="delta_day_of_start" name="delta_day_of_start" value="{{ $shift->delta_day_of_start }}" step="any" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="delta_day_of_finish">
                                    {{ ucwords(str_replace('_', ' ', 'delta_day_of_finish')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="delta_day_of_finish" name="delta_day_of_finish" value="{{ $shift->delta_day_of_finish }}" step="any" required>
                                </div>
                            </div> --}}

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="salary_multiplier">
                                    {{ ucwords(str_replace('_', ' ', 'salary_multiplier')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="salary_multiplier" name="salary_multiplier" value="{{ $shift->salary_multiplier }}" step="any" required>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
