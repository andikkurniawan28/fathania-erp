@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_repayment_category')) }}
@endsection

@section('repayment_category-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('repayment_category.index') }}">{{ ucwords(str_replace('_', ' ', 'repayment_category')) }}</a></li>
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
                        <form action="{{ route('repayment_category.update', $repayment_category->id) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="id">
                                    {{ ucwords(str_replace('_', ' ', 'id')) }}
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ old('id', $repayment_category->id) }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $repayment_category->name) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="deal_with">
                                    {{ ucwords(str_replace('_', ' ', 'deal_with')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="deal_with" name="deal_with" required>
                                        <option value="customers" {{ $repayment_category->deal_with == 'customers' ? 'selected' : '' }}>customers</option>
                                        <option value="suppliers" {{ $repayment_category->deal_with == 'suppliers' ? 'selected' : '' }}>suppliers</option>
                                        <option value="vendors" {{ $repayment_category->deal_with == 'vendors' ? 'selected' : '' }}>vendors</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Grand Total Account --}}
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="grand_total_account">
                                    {{ ucwords(str_replace('_', ' ', 'grand_total_account')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="grand_total_account" name="grand_total_account_id" required>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ $repayment_category->grand_total_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Grand Total Normal Balance --}}
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="grand_total_normal_balance">
                                    {{ ucwords(str_replace('_', ' ', 'grand_total_normal_balance')) }}
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="grand_total_normal_balance" name="grand_total_normal_balance_id">
                                        @foreach($normal_balances as $normal_balance)
                                            <option value="{{ $normal_balance->id }}" {{ $repayment_category->grand_total_normal_balance_id == $normal_balance->id ? 'selected' : '' }}>
                                                {{ $normal_balance->name }}
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
