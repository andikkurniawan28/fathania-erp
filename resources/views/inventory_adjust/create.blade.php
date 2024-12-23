@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_inventory_adjust')) }}
@endsection

@section('inventory_adjust-active')
    {{ 'active' }}
@endsection

@section('content')
    <style>
        #inventory_adjust-details-table {
            width: 100%;
        }

        #inventory_adjust-details-table th,
        #inventory_adjust-details-table td {
            text-align: center;
            padding: 8px;
        }

        /* Define column widths */
        #inventory_adjust-details-table .col-material {
            width: 30%;
        }

        #inventory_adjust-details-table .col-qty {
            width: 15%;
        }

        #inventory_adjust-details-table .col-price {
            width: 20%;
        }

        #inventory_adjust-details-table .col-total {
            width: 20%;
        }

        #inventory_adjust-details-table th:last-child,
        #inventory_adjust-details-table td:last-child {
            width: auto; /* For Action column */
        }
    </style>

    <script>
        function fetchMaterialInfo(selectElement) {
            const materialId = selectElement.value;
            const apiUrl = `/api/generate_material_info/${materialId}`;

            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(materialData => {
                    const priceField = selectElement.closest('tr').querySelector('.price');
                    const unitField = selectElement.closest('tr').querySelector('.unit');
                    const priceKey = "buy_price";

                    priceField.value = materialData.material[priceKey] !== null ? formatCurrency(materialData.material[priceKey]) : 0;
                    unitField.innerHTML = materialData.material.unit.symbol;
                    updateTotals(); // Update totals after fetching material info
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }

        function formatCurrency(value) {
            const decimalSeparator = '{{ $setup->currency->decimal_separator }}'; // e.g., ','
            const thousandSeparator = '{{ $setup->currency->thousand_separator }}'; // e.g., '.'

            // Format the number
            value = parseFloat(value).toFixed(2); // Format to 2 decimal places
            let parts = value.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator); // Add thousand separator
            return parts.join(decimalSeparator); // Join with decimal separator
        }

        function removeFormatting(value) {
            const decimalSeparator = '{{ $setup->currency->decimal_separator }}'; // e.g., ','
            const thousandSeparator = '{{ $setup->currency->thousand_separator }}'; // e.g., '.'

            // Remove formatting using the currency separators
            value = value.replace(new RegExp(`\\${thousandSeparator}`, 'g'), ''); // Remove thousand separator
            value = value.replace(new RegExp(`\\${decimalSeparator}`, 'g'), '.'); // Convert decimal separator to dot

            return value; // Return the cleaned value
        }

        function updateTotals() {
            let totalSubtotal = 0;

            document.querySelectorAll('.total').forEach(function(input) {
                totalSubtotal += parseFloat(removeFormatting(input.value)) || 0;
            });

            document.getElementById('grand_total').value = formatCurrency(totalSubtotal);

            // Enable/Disable submit button based on totals
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = !(totalSubtotal > 0);
        }

        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 1;

            function initializeSelect2() {
                $('.select2').select2({
                    placeholder: "Select an option",
                    theme: 'bootstrap',
                    allowClear: true,
                    width: "100%"
                });
            }

            initializeSelect2(); // Initialize for existing rows

            document.getElementById('add-row').addEventListener('click', function() {
                let tableBody = document.querySelector('#inventory_adjust-details-table tbody');
                let newRow = `
                    <tr>
                        <td>
                            <select width="100%" name="details[${rowCount}][material_id]" class="form-control select2" required onchange="fetchMaterialInfo(this)">
                                <option disabled selected>Select a material</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="details[${rowCount}][qty]" class="form-control qty number-format" step="0.01" required>
                            <span class="unit"></span>
                        </td>
                        <td>
                            <input type="text" name="details[${rowCount}][price]" class="form-control price number-format" step="0.01" required>
                        </td>
                        <td>
                            <input type="text" name="details[${rowCount}][total]" class="form-control total" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', newRow);
                initializeSelect2(); // Re-initialize Select2 for new row
                rowCount++;
                updateTotals(); // Update totals after adding new row
            });

            document.querySelector('#inventory_adjust-details-table').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                    updateTotals(); // Update totals after removing a row
                }
            });

            document.querySelector('#inventory_adjust-details-table').addEventListener('input', function(e) {
                if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
                    let row = e.target.closest('tr');
                    let qty = parseFloat(removeFormatting(row.querySelector('.qty').value)) || 0;
                    let price = parseFloat(removeFormatting(row.querySelector('.price').value)) || 0;
                    let total = qty * price;
                    row.querySelector('.total').value = formatCurrency(total); // Format total before displaying
                    updateTotals(); // Update totals when values change
                }
            });

            easyNumberSeparator({
                selector: '.number-format',
                separator: '{{ $setup->currency->thousand_separator }}',
                decimalSeparator: '{{ $setup->currency->decimal_separator }}'
            });

            updateTotals(); // Initial totals calculation
        });

        function easyNumberSeparator({ selector, separator, decimalSeparator }) {
            document.querySelectorAll(selector).forEach(input => {
                input.addEventListener('input', function () {
                    let value = this.value.replace(new RegExp(`\\${separator}`, 'g'), ''); // Remove thousand separator
                    value = value.replace(new RegExp(`\\${decimalSeparator}`, 'g'), '.'); // Convert decimal separator to dot
                    this.value = formatCurrency(value); // Format the value
                    updateTotals(); // Update totals after formatting
                });
            });
        }
    </script>

    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('inventory_adjust.index') }}">{{ ucwords(str_replace('_', ' ', 'inventory_adjust')) }}</a></li>
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
                        <form action="{{ route('inventory_adjust.store') }}" method="POST" id="inventory_adjust-form">
                            @csrf @method('POST')

                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="stock_adjust_id">{{ ucwords(str_replace('_', ' ', 'stock_adjust')) }}</label>
                                            <select width="100%" id="stock_adjust_id" name="stock_adjust_id" class="form-control select2" required>
                                                <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'stock_adjust')) }}</option>
                                                @foreach ($stock_adjusts as $stock_adjust)
                                                    <option value="{{ $stock_adjust->id }}">{{ $stock_adjust->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="warehouse_id">{{ ucwords(str_replace('_', ' ', 'warehouse')) }}</label>
                                            <select width="100%" id="warehouse_id" name="warehouse_id" class="form-control select2" required>
                                                <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'warehouse')) }}</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <br>
                                        <table class="table table-bordered" id="inventory_adjust-details-table">
                                            <thead>
                                                <tr>
                                                    <th class="col-material">Material</th>
                                                    <th class="col-qty">Qty</th>
                                                    <th class="col-price">Price</th>
                                                    <th class="col-total">Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select width="100%" name="details[0][material_id]" class="form-control select2" required onchange="fetchMaterialInfo(this)">
                                                            <option disabled selected>Select a material</option>
                                                            @foreach ($materials as $material)
                                                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="details[0][qty]" class="form-control qty number-format" required>
                                                        <span class="unit"></span>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="details[0][price]" class="form-control price number-format" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="details[0][total]" class="form-control total" readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <br>

                                        <button type="button" id="add-row" class="btn btn-success">Add Row</button>

                                        <!-- Total Calculation -->
                                        <table class="table table-bordered mt-4">
                                            <thead>
                                                <tr>
                                                    <th>Grand Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" name="grand_total" id="grand_total" class="form-control" readonly></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <br>

                                        <button type="submit" class="btn btn-primary" id="submit-button" disabled>Submit</button>
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
