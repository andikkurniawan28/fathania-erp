@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_currency')) }}
@endsection

@section('currency-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('currency.index') }}">{{ ucwords(str_replace('_', ' ', 'currency')) }}</a></li>
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
                        <form action="{{ route('currency.update', $currency->id) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $currency->name) }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="symbol">
                                    {{ ucwords(str_replace('_', ' ', 'symbol')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="symbol" name="symbol" value="{{ old('symbol', $currency->symbol) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="thousand_separator">
                                    {{ ucwords(str_replace('_', ' ', 'thousand_separator')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="thousand_separator" name="thousand_separator" value="{{ old('thousand_separator', $currency->thousand_separator) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="decimal_separator">
                                    {{ ucwords(str_replace('_', ' ', 'decimal_separator')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="decimal_separator" name="decimal_separator" value="{{ old('decimal_separator', $currency->decimal_separator) }}" required>
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
