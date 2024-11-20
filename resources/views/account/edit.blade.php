@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_account')) }}
@endsection

@section('account-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('account.index') }}">{{ ucwords(str_replace('_', ' ', 'account')) }}</a></li>
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
                        <form action="{{ route('account.update', $account->id) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id">
                                    {{ ucwords(str_replace('_', ' ', 'id')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ old('id', $account->id) }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $account->name) }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="cash_flow_category">
                                    {{ ucwords(str_replace('_', ' ', 'cash_flow_category')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="cash_flow_category" name="cash_flow_category_id" width="100%">
                                        <option value="" selected>None</option> <!-- Menambahkan opsi untuk 'null' -->
                                        @foreach($cash_flow_categories as $cash_flow_category)
                                            <option value="{{ $cash_flow_category->id }}"
                                                {{ $account->cash_flow_category_id == $cash_flow_category->id ? 'selected' : '' }}>
                                                {{ $cash_flow_category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="sub_account">
                                    {{ ucwords(str_replace('_', ' ', 'sub_account')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="sub_account" name="sub_account_id" width="100%" required autofocus>
                                        <option disabled {{ is_null($account->sub_account_id) ? 'selected' : '' }}>
                                            Select a {{ ucwords(str_replace('_', ' ', 'sub_account')) }} :
                                        </option>
                                        @foreach($sub_accounts as $sub_account)
                                            <option value="{{ $sub_account->id }}"
                                                {{ $account->sub_account == $sub_account->id ? 'selected' : '' }}>
                                                {{ $sub_account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="initial_balance">
                                    {{ ucwords(str_replace('_', ' ', 'initial_balance')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="initial_balance" name="initial_balance" value="{{ old('initial_balance', $account->initial_balance) }}" required autofocus>
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

@section('additional_script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#cash_flow_category').select2({
            theme: 'bootstrap',
            placeholder: "Select a cash_flow_category",
            allowClear: true // Mengaktifkan opsi clearable
        }),
        $('#sub_account').select2({
            theme: 'bootstrap',
            placeholder: "Select a sub_account"
        }),
        $('#normal_balance').select2({
            theme: 'bootstrap',
            placeholder: "Select a normal balance"
        });
    });
</script>
@endsection
