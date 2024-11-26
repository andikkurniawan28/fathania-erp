@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_journal')) }}
@endsection

@section('journal-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('journal.index') }}">{{ ucwords(str_replace('_', ' ', 'journal')) }}</a></li>
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
                        <form action="{{ route('journal.store') }}" method="POST" id="journal-form">
                            @csrf @method('POST')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id">
                                    {{ ucwords(str_replace('_', ' ', 'id')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ $id }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <table class="table table-bordered" id="journal-details-table">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th>Description</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select name="details[0][account_id]" class="form-control select2" required>
                                                        <option disabled selected>Select an {{ ucwords(str_replace('_', ' ', 'account')) }} :</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="details[0][description]" class="form-control" placeholder="Description" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="details[0][debit]" class="form-control debit number-format" step="0.01" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="details[0][credit]" class="form-control credit number-format" step="0.01" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <br>

                                    <button type="button" id="add-row" class="btn btn-success">Add Row</button>

                                    <!-- Total Debit and Credit in Table -->
                                    <table class="table table-bordered mt-4">
                                        <thead>
                                            <tr>
                                                <th>Total Debit</th>
                                                <th>Total Credit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="total-debit">0.00</td>
                                                <td id="total-credit">0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <br>

                                    <button type="submit" class="btn btn-primary" id="submit-button" disabled>Submit</button>

                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    let rowCount = 1;

    // Initialize Select2 for dynamically added rows
    function initializeSelect2() {
        $('.select2').select2({
            placeholder: "Select an account",
            theme: 'bootstrap',
            allowClear: true
        });
    }

    initializeSelect2(); // Initialize for existing row

    // Fungsi untuk format angka dengan separator sesuai pengaturan
    function formatCurrency(data) {
        if (data === '-') {
            return '-';
        }

        // Ambil nilai separator dari Blade
        var decimalSeparator = '{{ $setup->currency->decimal_separator }}'; // Misal: ','
        var thousandSeparator = '{{ $setup->currency->thousand_separator }}'; // Misal: '.'

        // Format angka dengan PHP-style number_format
        var formattedData = parseFloat(data).toFixed(2); // Format dengan 2 angka desimal

        // Ganti titik dengan separator desimal dan koma dengan separator ribuan sesuai pengaturan
        formattedData = formattedData.replace('.', decimalSeparator); // Ganti titik dengan separator desimal

        // Pisahkan angka menjadi ribuan dan format separator ribuan
        var parts = formattedData.split(decimalSeparator);
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator); // Format ribuan
        return parts.join(decimalSeparator); // Gabungkan kembali bagian ribuan dan desimal
    }

    function updateTotals() {
        let totalDebit = 0;
        let totalCredit = 0;

        // Menghitung total debit dan kredit
        document.querySelectorAll('.debit').forEach(function (input) {
            totalDebit += parseFloat(input.value.replace('{{ $setup->currency->thousand_separator }}', '').replace('{{ $setup->currency->decimal_separator }}', '.')) || 0;
        });

        document.querySelectorAll('.credit').forEach(function (input) {
            totalCredit += parseFloat(input.value.replace('{{ $setup->currency->thousand_separator }}', '').replace('{{ $setup->currency->decimal_separator }}', '.')) || 0;
        });

        // Update total dengan format yang sesuai
        document.getElementById('total-debit').textContent = formatCurrency(totalDebit);
        document.getElementById('total-credit').textContent = formatCurrency(totalCredit);

        // Enable/Disable submit button based on totals
        const submitButton = document.getElementById('submit-button');
        if (totalDebit > 0 && totalDebit === totalCredit) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    document.getElementById('add-row').addEventListener('click', function () {
        let tableBody = document.querySelector('#journal-details-table tbody');
        let newRow = `
            <tr>
                <td>
                    <select name="details[${rowCount}][account_id]" class="form-control select2" required>
                        <option disabled selected>Select an account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="details[${rowCount}][description]" class="form-control" placeholder="Description" required>
                </td>
                <td>
                    <input type="text" name="details[${rowCount}][debit]" class="form-control debit number-format" step="0.01" required>
                </td>
                <td>
                    <input type="text" name="details[${rowCount}][credit]" class="form-control credit number-format" step="0.01" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);
        initializeSelect2(); // Re-initialize Select2 for new row

        // Apply formatting for new rows
        easyNumberSeparator({
            selector: '.number-format',
            separator: '{{ $setup->currency->thousand_separator }}',
            decimalSeparator: '{{ $setup->currency->decimal_separator }}'
        });

        rowCount++;
        updateTotals(); // Update totals after adding new row
    });

    document.querySelector('#journal-details-table').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            updateTotals(); // Update totals after removing a row
        }
    });

    document.querySelector('#journal-details-table').addEventListener('input', function (e) {
        if (e.target.classList.contains('debit') || e.target.classList.contains('credit')) {
            updateTotals(); // Update totals when debit or credit values change
        }
    });

    updateTotals(); // Initial totals calculation
});

    </script>
@endsection
