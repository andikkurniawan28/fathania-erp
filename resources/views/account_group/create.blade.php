@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_account_group')) }}
@endsection

@section('account_group-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("account_group.index") }}">{{ ucwords(str_replace('_', ' ', 'account_group')) }}</a></li>
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
                        <form action="{{ route("account_group.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id">
                                    {{ ucwords(str_replace('_', ' ', 'id')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ old("id") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="financial_statement">
                                    {{ ucwords(str_replace('_', ' ', 'financial_statement')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="financial_statement" name="financial_statement_id" width="100%" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'financial_statement')) }} :</option>
                                        @foreach ($financial_statements as $financial_statement)
                                            <option value="{{ $financial_statement->id }}">{{ $financial_statement->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="normal_balance">
                                    {{ ucwords(str_replace('_', ' ', 'normal_balance')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="normal_balance" name="normal_balance_id" width="100%" required autofocus>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'normal_balance')) }} :</option>
                                        @foreach($normal_balances as $normal_balance)
                                            <option value="{{ $normal_balance->id }}">{{ $normal_balance->name }}</option>
                                        @endforeach
                                    </select>
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

@section('additional_script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#financial_statement').select2({
            theme: 'bootstrap',
            placeholder: "Select a financial_statement"
        }),
        $('#normal_balance').select2({
            theme: 'bootstrap',
            placeholder: "Select a financial_statement"
        })
    });
</script>
@endsection
