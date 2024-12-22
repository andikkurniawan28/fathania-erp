@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_repayment')) }}
@endsection

@section('repayment-active')
    {{ 'active' }}
@endsection

@section('content')
    <style>
        #repayment-details-table {
            width: 100%;
        }

        #repayment-details-table th,
        #repayment-details-table td {
            text-align: center;
            padding: 8px;
        }

        /* Define column widths */
        #repayment-details-table .col-material {
            width: 30%;
        }

        #repayment-details-table .col-qty {
            width: 15%;
        }

        #repayment-details-table .col-price {
            width: 20%;
        }

        #repayment-details-table .col-discount {
            width: 15%;
        }

        #repayment-details-table .col-total {
            width: 20%;
        }

        #repayment-details-table th:last-child,
        #repayment-details-table td:last-child {
            width: auto; /* For Action column */
        }
    </style>

    <script>
        function easyNumberSeparator({ selector, separator, decimalSeparator }) {
            document.querySelectorAll(selector).forEach(input => {
                input.addEventListener('input', function () {
                    let value = this.value.replace(new RegExp(`\\${separator}`, 'g'), ''); // Remove thousand separator
                    value = value.replace(new RegExp(`\\${decimalSeparator}`, 'g'), '.'); // Convert decimal separator to dot
                    this.value = formatCurrency(value); // Format the value
                    updateGrandTotal(); // Update totals after formatting
                });
            });
        }

        function formatCurrency(value) {
            const decimalSeparator = '{{ $setup->currency->decimal_separator }}'; // Example: ','
            const thousandSeparator = '{{ $setup->currency->thousand_separator }}'; // Example: '.'

            // Format the number
            value = parseFloat(value).toFixed(2); // Format to 2 decimal places
            let parts = value.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator); // Add thousand separator
            return parts.join(decimalSeparator); // Join with decimal separator
        }

        function handleRepaymentCategoryChange(selectElement) {
            const repaymentCategoryId = selectElement.value;
            const apiUrl = `/api/generate_repayment_id/${repaymentCategoryId}`;

            // Reset semua dropdown
            document.getElementById('supplier_id').value = ""; // Reset supplier dropdown
            document.getElementById('customer_id').value = ""; // Reset customer dropdown
            document.getElementById('supplier-select').style.display = "none"; // Sembunyikan supplier select
            document.getElementById('customer-select').style.display = "none"; // Sembunyikan customer select

            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.repayment_id) {
                        document.getElementById('id').value = data.repayment_id;
                        const deal_with = data.repayment_category.deal_with;
                        const supplier_select = document.getElementById('supplier-select');
                        const customer_select = document.getElementById('customer-select');

                        if (deal_with === "suppliers") {
                            supplier_select.style.display = "block"; // Tampilkan supplier select
                        } else if (deal_with === "customers") {
                            customer_select.style.display = "block"; // Tampilkan customer select
                        }
                    }
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }

        function handleSupplierOrCustomerChange() {
            const repaymentCategoryId = document.getElementById('repayment_category_id').value;
            const supplierId = document.getElementById('supplier_id').value;
            const customerId = document.getElementById('customer_id').value;

            // Pastikan kita mendapatkan ID yang valid
            let selectedId = supplierId !== "" ? supplierId : (customerId !== "" ? customerId : null); // Pilih ID dari supplier atau customer yang terpilih

            // Cek apakah repaymentCategoryId dan selectedId valid
            if (repaymentCategoryId && selectedId) {
                const apiUrl = `/api/generate_unpaid_invoice/${repaymentCategoryId}/${selectedId}`;
                console.log(apiUrl);
                fetch(apiUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response from unpaid invoice API:', data);
                        if (Array.isArray(data.data)) {
                            if (data.data.length > 0) {
                                populateRepaymentTable(data.data);
                            } else {
                                clearRepaymentTable();
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Unpaid Invoice Not Found',
                                    text: 'No unpaid invoices were found for this supplier / customer.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        } else {
                            console.error('Unexpected data format:', data);
                        }
                    })
                    .catch(error => {
                        console.error('There has been a problem with your fetch operation:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while processing the request. Please try again later.',
                            confirmButtonText: 'OK'
                        });
                    });
            } else {
                console.error('Invalid repayment category or selected ID');
            }
        }

        function clearRepaymentTable() {
            const tableBody = document.querySelector('#repayment-details-table tbody');
            tableBody.innerHTML = '';
        }

        function updateTotal(id) {
            const discountInput = document.getElementById(`details[${id}][discount]`);
            const paidInput = document.getElementById(`details[${id}][paid]`);
            const totalInput = document.getElementById(`details[${id}][total]`);

            if (discountInput && paidInput && totalInput) {
                const discount = parseFloat(removeFormatting(discountInput.value)) || 0;
                const paid = parseFloat(removeFormatting(paidInput.value)) || 0;

                const total = discount + paid;
                totalInput.value = formatCurrency(total); // Format the total value

                updateGrandTotal();
            } else {
                console.error('One or more elements are missing:', {
                    discountInput,
                    paidInput,
                    totalInput
                });
            }
        }

        function updateGrandTotal() {
            const totalInputs = document.querySelectorAll('#repayment-details-table .total');
            let grandTotal = 0;
            const grandTotalElement = document.getElementById('grand_total');
            const submitButton = document.getElementById('submit-button');

            if (totalInputs.length === 0) {
                grandTotalElement.value = formatCurrency(0); // Format as currency
                submitButton.disabled = true;
                return;
            }

            totalInputs.forEach(input => {
                const value = parseFloat(removeFormatting(input.value)) || 0;
                grandTotal += value;
            });

            if (grandTotalElement) {
                grandTotalElement.value = formatCurrency(grandTotal); // Format the grand total
                submitButton.disabled = grandTotal === 0;
            } else {
                console.error('Element with ID "grand_total" not found.');
            }
        }

        function removeFormatting(value) {
            const decimalSeparator = '{{ $setup->currency->decimal_separator }}'; // Example: ','
            const thousandSeparator = '{{ $setup->currency->thousand_separator }}'; // Example: '.'

            // Remove formatting using the currency separators
            value = value.replace(new RegExp(`\\${thousandSeparator}`, 'g'), ''); // Remove thousand separator
            value = value.replace(new RegExp(`\\${decimalSeparator}`, 'g'), '.'); // Convert decimal separator to dot

            return value; // Return the cleaned value
        }

        function populateRepaymentTable(invoices) {
            const tableBody = document.querySelector('#repayment-details-table tbody');
            tableBody.innerHTML = '';

            invoices.forEach(item => {
                let newRow = `
            <tr>
                <td>
                    <input type="text" name="details[${item.id}][invoice_id]" id="details[${item.id}][invoice_id]" value="${item.id}" class="form-control" readonly>
                </td>
                <td>
                    <input type="text" name="details[${item.id}][left]" id="details[${item.id}][left]" value="${formatCurrency(item.left)}" class="form-control" readonly>
                </td>
                <td>
                    <input type="text" name="details[${item.id}][discount]" id="details[${item.id}][discount]" value="${formatCurrency(0)}" class="form-control discount number-format" oninput="updateTotal('${item.id}')">
                </td>
                <td>
                    <input type="text" name="details[${item.id}][paid]" id="details[${item.id}][paid]" value="${formatCurrency(item.left)}" class="form-control paid number-format" oninput="updateTotal('${item.id}')">
                </td>
                <td>
                    <input type="text" name="details[${item.id}][total]" id="details[${item.id}][total]" value="${formatCurrency(item.left)}" class="form-control total" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </td>
            </tr>
        `;
                tableBody.insertAdjacentHTML('beforeend', newRow);
            });

            updateGrandTotal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('#repayment-details-table').addEventListener('input', function(e) {
                if (e.target.classList.contains('discount') || e.target.classList.contains('paid')) {
                    let row = e.target.closest('tr');
                    let id = row.querySelector('[name^="details["]').getAttribute('name').match(/\d+/)[0];
                    updateTotal(id);
                }
            });

            document.querySelector('#repayment-details-table').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                    updateGrandTotal();
                }
            });

            function initializeSelect2() {
                $('.select2').select2({
                    placeholder: "Select an option",
                    theme: 'bootstrap',
                    allowClear: true,
                    width: "100%"
                });
            }

            initializeSelect2();

            document.getElementById('repayment_category_id').addEventListener('change', function() {
                handleRepaymentCategoryChange(this);
            });

            document.getElementById('supplier_id').addEventListener('change', handleSupplierOrCustomerChange);
            document.getElementById('customer_id').addEventListener('change', handleSupplierOrCustomerChange);

            easyNumberSeparator({
                selector: '.number-format',
                separator: '{{ $setup->currency->thousand_separator }}',
                decimalSeparator: '{{ $setup->currency->decimal_separator }}'
            });

            updateGrandTotal();
        });
    </script>
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('repayment.index') }}">{{ ucwords(str_replace('_', ' ', 'repayment')) }}</a></li>
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
                        <form action="{{ route('repayment.store') }}" method="POST" id="repayment-form">
                            @csrf @method('POST')

                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="repayment_category_id">
                                                {{ ucwords(str_replace('_', ' ', 'repayment_category')) }}
                                            </label>
                                            <select width="100%" id="repayment_category_id" name="repayment_category_id"
                                                class="form-control select2" required
                                                onChange="handleRepaymentCategoryChange(this)">
                                                <option disabled selected>Select a
                                                    {{ ucwords(str_replace('_', ' ', 'repayment_category')) }}</option>
                                                @foreach ($repayment_categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id">
                                                {{ ucwords(str_replace('_', ' ', 'ID')) }}
                                            </label>
                                            <input type="text" class="form-control" name="id" id="id"
                                                value="" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3" style="display: none;" id="supplier-select">
                                            <label for="supplier_id">
                                                {{ ucwords(str_replace('_', ' ', 'supplier')) }}
                                            </label>
                                            <select width="100%" id="supplier_id" name="supplier_id" class="form-control select2" onChange="handleSupplierOrCustomerChange(this)">
                                                <option value="" disabled selected>Select a supplier</option> <!-- Opsi default -->
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3" style="display: none;" id="customer-select">
                                            <label for="customer_id">
                                                {{ ucwords(str_replace('_', ' ', 'customer')) }}
                                            </label>
                                            <select width="100%" id="customer_id" name="customer_id" class="form-control select2" onChange="handleSupplierOrCustomerChange(this)">
                                                <option value="" disabled selected>Select a customer</option> <!-- Opsi default -->
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <br>
                                        <table class="table table-bordered" id="repayment-details-table">
                                            <thead>
                                                <tr>
                                                    <th class="col-id">ID</th>
                                                    <th class="col-last">Left</th>
                                                    <th class="col-discount">Discount</th>
                                                    <th class="col-paid">Paid</th>
                                                    <th class="col-total">Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                        <!-- Total Calculation -->
                                        <table class="table table-bordered mt-4">
                                            <thead>
                                                <tr>
                                                    <th>Grand Total</th>
                                                    <th>Gateway</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="grand-total"><input type="text" name="grand_total"
                                                            id="grand_total" class="form-control" readonly></td>
                                                    <td>
                                                        <select width="100%" id="payment_gateway_id"
                                                            name="payment_gateway_id" class="form-control select2" required>
                                                            <option disabled selected>Select a
                                                                {{ ucwords(str_replace('_', ' ', 'payment_gateway')) }}
                                                            </option>
                                                            @foreach ($payment_gateways as $payment_gateway)
                                                                <option value="{{ $payment_gateway->id }}">
                                                                    {{ $payment_gateway->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <br>

                                        <button type="submit" class="btn btn-primary" id="submit-button"
                                            disabled>Submit</button>

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
