@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_day')) }}
@endsection

@section('day-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("day.index") }}">{{ ucwords(str_replace('_', ' ', 'day')) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield("title")</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("day.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required autofocus>
                                </div>
                            </div>

                            {{-- <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="num">
                                    {{ ucwords(str_replace('_', ' ', 'num')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="num" name="num" value="{{ old("num") }}" required>
                                </div>
                            </div> --}}

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="salary_multiplier">
                                    {{ ucwords(str_replace('_', ' ', 'salary_multiplier')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" step="any" class="form-control" id="salary_multiplier" name="salary_multiplier" value="{{ old("salary_multiplier") }}" required>
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
@endsection
