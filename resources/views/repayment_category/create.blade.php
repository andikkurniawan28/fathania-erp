@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_repayment_category')) }}
@endsection

@section('repayment_category-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route("repayment_category.index") }}">{{ ucwords(str_replace('_', ' ', 'repayment_category')) }}</a></li>
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
                        <form action="{{ route("repayment_category.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="id">
                                    {{ ucwords(str_replace('_', ' ', 'id')) }}
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ old("id") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="deal_with">
                                    {{ ucwords(str_replace('_', ' ', 'deal_with')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="deal_with" name="deal_with" data-placeholder="Select a {{ ucwords(str_replace('_', ' ', 'deal_with')) }}" required autofocus>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'deal_with')) }} :</option>
                                        <option value="customers">customers</option>
                                        <option value="suppliers">suppliers</option>
                                        <option value="vendors">vendors</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="grand_total_account">
                                    {{ ucwords(str_replace('_', ' ', 'grand_total_account')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="grand_total_account" name="grand_total_account_id" data-placeholder="Select a {{ ucwords(str_replace('_', ' ', 'grand_total_account')) }}" required autofocus>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'grand_total_account')) }} :</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="grand_total_normal_balance">
                                    {{ ucwords(str_replace('_', ' ', 'grand_total_normal_balance')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="grand_total_normal_balance" name="grand_total_normal_balance_id" data-placeholder="Select a {{ ucwords(str_replace('_', ' ', 'grand_total_normal_balance')) }}">
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'grand_total_normal_balance')) }} :</option>
                                        @foreach($normal_balances as $normal_balance)
                                            <option value="{{ $normal_balance->id }}">{{ $normal_balance->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-8">
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
        // Inisialisasi Select2 untuk semua elemen dengan kelas .select2
        $('.select2').select2({
            theme: 'bootstrap',
            placeholder: function() {
                return $(this).data('placeholder');
            }
        });
    });
</script>
@endsection
