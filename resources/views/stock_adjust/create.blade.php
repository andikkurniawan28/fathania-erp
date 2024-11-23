@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_stock_adjust')) }}
@endsection

@section('stock_adjust-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("stock_adjust.index") }}">{{ ucwords(str_replace('_', ' ', 'stock_adjust')) }}</a></li>
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
                        <form action="{{ route("stock_adjust.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="stock_normal_balance">
                                    {{ ucwords(str_replace('_', ' ', 'stock_normal_balance')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="stock_normal_balance" name="stock_normal_balance_id" data-placeholder="Select a {{ ucwords(str_replace('_', ' ', 'stock_normal_balance')) }}">
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'stock_normal_balance')) }} :</option>
                                        @foreach($normal_balances as $normal_balance)
                                            <option value="{{ $normal_balance->id }}">{{ $normal_balance->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="profit_loss_account">
                                    {{ ucwords(str_replace('_', ' ', 'profit_loss_account')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="profit_loss_account" name="profit_loss_account_id" data-placeholder="Select a {{ ucwords(str_replace('_', ' ', 'profit_loss_account')) }}">
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'profit_loss_account')) }} :</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
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
        $('#profit_loss_account').select2({
            theme: 'bootstrap',
            placeholder: "Select an account"
        });
        $('#stock_normal_balance').select2({
            theme: 'bootstrap',
            placeholder: "Select a normal balance"
        });
    });
</script>
@endsection
