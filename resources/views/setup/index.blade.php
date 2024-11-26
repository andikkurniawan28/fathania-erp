@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'setup')) }}
@endsection

@section('setup-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                {{-- <li class="breadcrumb-item"><a href="{{ route("setup.index") }}">{{ ucwords(str_replace('_', ' ', 'setup')) }}</a></li> --}}
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
                        <form action="{{ route("setup.update", $setup->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method("PUT")
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="app_name">
                                    {{ ucwords(str_replace('_', ' ', 'app_name')) }}
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="app_name" name="app_name" value="{{ $setup->app_name }}" required autofocus>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="company_name">
                                    {{ ucwords(str_replace('_', ' ', 'company_name')) }}
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $setup->company_name }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="company_logo">
                                    {{ ucwords(str_replace('_', ' ', 'company_logo')) }}
                                </label>
                                <div class="col-sm-8">
                                    @if($setup->company_logo)
                                        <div class="mb-3">
                                            <img src="{{ asset($setup->company_logo) }}" alt="Company Logo" style="max-height: 100px; max-width: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="company_logo" name="company_logo" accept=".jpg, .jpeg, .png">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="currency_id">
                                    {{ ucwords(str_replace('_', ' ', 'currency')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="currency" name="currency_id" width="100%" required autofocus>
                                        <option disabled {{ is_null($setup->currency_id) ? 'selected' : '' }}>
                                            Select a {{ ucwords(str_replace('_', ' ', 'currency')) }} :
                                        </option>
                                        @foreach($currencys as $currency)
                                            <option value="{{ $currency->id }}"
                                                {{ $setup->currency_id == $currency->id ? 'selected' : '' }}>
                                                {{ $currency->symbol }} | {{ $currency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="retained_earning_id">
                                    {{ ucwords(str_replace('_', ' ', 'retained_earning_account')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="retained_earning" name="retained_earning_id" width="100%" required autofocus>
                                        <option disabled {{ is_null($setup->retained_earning_id) ? 'selected' : '' }}>
                                            Select an {{ ucwords(str_replace('_', ' ', 'account')) }} :
                                        </option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ $setup->retained_earning_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->id }} | {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="material_inventory_id">
                                    {{ ucwords(str_replace('_', ' ', 'material_inventory_account')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="material_inventory" name="material_inventory_id" width="100%" required autofocus>
                                        <option disabled {{ is_null($setup->material_inventory_id) ? 'selected' : '' }}>
                                            Select an {{ ucwords(str_replace('_', ' ', 'account')) }} :
                                        </option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ $setup->material_inventory_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->id }} | {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-8">
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

@section('additional_script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#retained_earning').select2({
            theme: 'bootstrap',
            placeholder: "Select an account"
        });
        $('#material_inventory').select2({
            theme: 'bootstrap',
            placeholder: "Select an account"
        });
        $('#currency').select2({
            theme: 'bootstrap',
            placeholder: "Select a currency"
        });
    });
</script>
@endsection
