@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_tax_rate')) }}
@endsection

@section('tax_rate-active')
    {{ 'active' }}
@endsection

@section('content')
    @php
        $tax_rate->rate = number_format($tax_rate->rate, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator);
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tax_rate.index') }}">{{ ucwords(str_replace('_', ' ', 'tax_rate')) }}</a></li>
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
                        <form action="{{ route('tax_rate.update', $tax_rate->id) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $tax_rate->name) }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="rate">
                                    {{ ucwords(str_replace('_', ' ', 'rate')) }}
                                    <sub>(%)</sub>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" step="any" class="form-control" id="rate" name="rate" value="{{ old('rate', $tax_rate->rate) }}" required>
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
