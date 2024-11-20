@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_budget')) }}
@endsection

@section('budget-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("budget.index") }}">{{ ucwords(str_replace('_', ' ', 'budget')) }}</a></li>
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
                        <form action="{{ route("budget.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="account">
                                    {{ ucwords(str_replace('_', ' ', 'account')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="account" name="account_id" width="100%" required autofocus>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'account')) }} :</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="start_date">
                                    {{ ucwords(str_replace('_', ' ', 'start_date')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="date" step="any" class="form-control" id="start_date" name="start_date" value="{{ old("start_date") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="end_date">
                                    {{ ucwords(str_replace('_', ' ', 'end_date')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="date" step="any" class="form-control" id="end_date" name="end_date" value="{{ old("end_date") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="amount">
                                    {{ ucwords(str_replace('_', ' ', 'amount')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" step="any" class="form-control" id="amount" name="amount" value="{{ old("amount") }}" required autofocus>
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
        $('#account').select2({
            theme: 'bootstrap',
            placeholder: "Select a account"
        });
    });
</script>
@endsection
