@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_invoice')) }}
@endsection

@section('invoice-active')
    {{ 'active' }}
@endsection

@section('content')
    <style>
        #invoice-details-table {
            width: 100%;
        }

        #invoice-details-table th,
        #invoice-details-table td {
            text-align: center;
            padding: 8px;
        }

        /* Define column widths */
        #invoice-details-table .col-material {
            width: 30%;
        }

        #invoice-details-table .col-qty {
            width: 15%;
        }

        #invoice-details-table .col-price {
            width: 20%;
        }

        #invoice-details-table .col-discount {
            width: 15%;
        }

        #invoice-details-table .col-total {
            width: 20%;
        }

        #invoice-details-table th:last-child,
        #invoice-details-table td:last-child {
            width: auto;
            /* For Action column */
        }
    </style>


    <script>
        function fetchTaxRateInfo(selectElement) {
            const taxRateId = selectElement.value;
            const apiUrl = `/api/generate_tax_rate_info/${taxRateId}`;
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('rate').value = data.tax_rate.rate;
                    updateTotals();
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }

        function handlePaymentTermChange(selectElement, validUntil) {
            const paymentTermId = selectElement.value;
            const currentDate = validUntil; // Use the validUntil value from the input field
            const apiUrl = `/api/generate_valid_until/${paymentTermId}/${currentDate}`;

            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Asumsikan response dari API mengandung property `valid_until`
                    if (data.valid_until) {
                        // Menetapkan nilai valid_until di input
                        document.getElementById('valid_until').value = data.valid_until;
                    }
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }

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
                    // Get the selected invoice category
                    const invoiceCategorySelect = document.getElementById('invoice_category_id');
                    const invoiceCategoryId = invoiceCategorySelect.value;

                    // Fetch invoice category info
                    return fetch(`/api/generate_invoice_category_info/${invoiceCategoryId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(invoiceCategoryData => {
                            // Map the price_used to the corresponding price
                            const priceField = selectElement.closest('tr').querySelector('.price');
                            const discountField = selectElement.closest('tr').querySelector('.discount');
                            const unitField = selectElement.closest('tr').querySelector('.unit');
                            const priceKey = invoiceCategoryData.invoice_category.price_used;
                            discountField.value = 0;
                            priceField.value = materialData.material[priceKey] !== null ? materialData.material[
                                priceKey] : 0; // Dynamically assign sell_price or buy_price
                            unitField.innerHTML = materialData.material.unit
                                .symbol; // Dynamically assign sell_price or buy_price
                        });
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }

        function handleTransactionCategoryChange(selectElement) {
            const invoiceCategoryId = selectElement.value;
            // console.log('Selected Transaction Category ID:', invoiceCategoryId);
            const apiUrl = `/api/generate_invoice_id/${invoiceCategoryId}`;
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // console.log('Data fetched from API:', data);

                    // Asumsikan response dari API mengandung property `invoice_id`
                    if (data.invoice_id) {
                        // Menetapkan nilai invoice_id di input
                        document.getElementById('id').value = data.invoice_id;
                        deal_with = data.invoice_category.deal_with;
                        supplier_select = document.getElementById('supplier-select');
                        customer_select = document.getElementById('customer-select');
                        if(deal_with === "suppliers"){
                            supplier_select.style.display = "block";
                            customer_select.style.display = "none";
                        }
                        else if(deal_with === "customers"){
                            supplier_select.style.display = "none";
                            customer_select.style.display = "block";
                        }
                    }
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }

        function updateTotals() {
            let totalSubtotal = 0;
            let totalFreight = parseFloat(document.getElementById('freight')?.value) || 0;
            let totalDiscount = parseFloat(document.getElementById('discount')?.value) || 0;
            let totalPaid = parseFloat(document.getElementById('paid')?.value) || 0;

            document.querySelectorAll('.total').forEach(function(input) {
                totalSubtotal += parseFloat(input.value) || 0;
            });

            let totalTaxes = (parseFloat(document.getElementById('rate').value) / 100) * totalSubtotal;

            const grandTotal = totalSubtotal + totalTaxes + totalFreight - totalDiscount;
            const left = grandTotal - totalPaid;
            // const paid = totalGiven - left;

            document.getElementById('subtotal').value = totalSubtotal.toFixed(0);
            document.getElementById('taxes').value = totalTaxes.toFixed(0);
            document.getElementById('grand_total').value = grandTotal.toFixed(0);
            document.getElementById('left').value = left.toFixed(0);

            // Enable/Disable submit button based on totals
            const submitButton = document.getElementById('submit-button');
            if (totalSubtotal > 0 && grandTotal >= 0) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 1;

            // Initialize Select2 for dynamically added rows
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
                let tableBody = document.querySelector('#invoice-details-table tbody');
                let newRow = `
                    <tr>
                        <td>
                            <select width="100%" name="details[${rowCount}][material_id]" class="form-control select2 material-select" required onChange="fetchMaterialInfo(this)">
                                <option disabled selected>Select a material</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="details[${rowCount}][qty]" class="form-control qty" step="0.01" required>
                            <span class="unit"></span>
                        </td>
                        <td>
                            <input type="number" name="details[${rowCount}][price]" class="form-control price" step="0.01" required>
                        </td>
                        <td>
                            <input type="number" name="details[${rowCount}][discount]" class="form-control discount" step="0.01" required>
                        </td>
                        <td>
                            <input type="number" name="details[${rowCount}][total]" class="form-control total" step="0.01" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', newRow);
                initializeSelect2(); // Re-initialize Select2 for new row
                rowCount++;

                // Fetch material info for the newly added select element
                const newMaterialSelect = tableBody.querySelector(`tr:last-child .material-select`);
                fetchMaterialInfo(newMaterialSelect);
                updateTotals(); // Update totals after adding new row
            });


            document.querySelector('#invoice-details-table').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                    updateTotals(); // Update totals after removing a row
                }
            });

            document.querySelector('#invoice-details-table').addEventListener('input', function(e) {
                if (e.target.classList.contains('qty') || e.target.classList.contains('price') || e.target
                    .classList
                    .contains('discount')) {
                    let row = e.target.closest('tr');
                    let qty = parseFloat(row.querySelector('.qty').value) || 0;
                    let price = parseFloat(row.querySelector('.price').value) || 0;
                    let discount = parseFloat(row.querySelector('.discount').value) || 0;
                    let total = (qty * price) - discount;
                    row.querySelector('.total').value = total.toFixed(0);
                    updateTotals(); // Update totals when values change
                }
            });

            // Safely adding event listeners to 'taxes', 'freight', and 'discount' inputs
            const taxesInput = document.getElementById('taxes');
            const freightInput = document.getElementById('freight');
            const discountInput = document.getElementById('discount');
            const paidInput = document.getElementById('paid');

            if (taxesInput) {
                taxesInput.addEventListener('input', updateTotals);
            }

            if (freightInput) {
                freightInput.addEventListener('input', updateTotals);
            }

            if (discountInput) {
                discountInput.addEventListener('input', updateTotals);
            }

            if (paidInput) {
                paidInput.addEventListener('input', updateTotals);
            }

            updateTotals(); // Initial totals calculation
        });
    </script>
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('invoice.index') }}">{{ ucwords(str_replace('_', ' ', 'invoice')) }}</a></li>
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
                        <form action="{{ route('invoice.store') }}" method="POST" id="invoice-form">
                            @csrf @method('POST')

                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="invoice_category_id">
                                                {{ ucwords(str_replace('_', ' ', 'invoice_category')) }}
                                            </label>
                                            <select width="100%" id="invoice_category_id"
                                                name="invoice_category_id" class="form-control select2" required
                                                onChange="handleTransactionCategoryChange(this)">
                                                <option disabled selected>Select a
                                                    {{ ucwords(str_replace('_', ' ', 'invoice_category')) }}</option>
                                                @foreach ($invoice_categories as $category)
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
                                        <div class="mb-3">
                                            <label for="tax_rate_id">
                                                {{ ucwords(str_replace('_', ' ', 'tax_rate')) }}
                                            </label>
                                            <select id="tax_rate_id" name="tax_rate_id" class="form-control select2"
                                                onchange="fetchTaxRateInfo(this)" required>
                                                <option disabled selected>Select a
                                                    {{ ucwords(str_replace('_', ' ', 'tax_rate')) }}</option>
                                                @foreach ($tax_rates as $tax_rate)
                                                    <option value="{{ $tax_rate->id }}">{{ $tax_rate->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="rate">
                                        </div>
                                        <div class="mb-3">
                                            <label for="warehouse_id">
                                                {{ ucwords(str_replace('_', ' ', 'warehouse')) }}
                                            </label>
                                            <select width="100%" id="warehouse_id" name="warehouse_id"
                                                class="form-control select2" required>
                                                <option disabled selected>Select a
                                                    {{ ucwords(str_replace('_', ' ', 'warehouse')) }}</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3" style="display: none;" id="supplier-select">
                                            <label for="supplier_id">
                                                {{ ucwords(str_replace('_', ' ', 'supplier')) }}
                                            </label>
                                            <select width="100%" id="supplier_id" name="supplier_id"
                                                class="form-control select2">
                                                <option disabled selected>Select a
                                                    {{ ucwords(str_replace('_', ' ', 'supplier')) }}</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3" style="display: none;" id="customer-select">
                                            <label for="customer_id">
                                                {{ ucwords(str_replace('_', ' ', 'customer')) }}
                                            </label>
                                            <select width="100%" id="customer_id" name="customer_id"
                                                class="form-control select2">
                                                <option disabled selected>Select a
                                                    {{ ucwords(str_replace('_', ' ', 'customer')) }}</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="payment_term_id">
                                                {{ ucwords(str_replace('_', ' ', 'payment_term')) }}
                                            </label>
                                            <select width="100%" id="payment_term_id" name="payment_term_id"
                                                class="form-control select2" required
                                                onChange="handlePaymentTermChange(this, document.getElementById('valid_until_old').value)">
                                                <option disabled selected>Select a
                                                    {{ ucwords(str_replace('_', ' ', 'payment_term')) }}</option>
                                                @foreach ($payment_terms as $payment_term)
                                                    <option value="{{ $payment_term->id }}">{{ $payment_term->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="valid_until_old"
                                                value="{{ old('valid_until', date('Y-m-d')) }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="supplier_id">
                                                {{ ucwords(str_replace('_', ' ', 'valid_until')) }}
                                            </label>
                                            <input type="date" class="form-control" name="valid_until" id="valid_until"
                                                value="{{ date('Y-m-d') }}" readonly>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <br>
                                        <table class="table table-bordered" id="invoice-details-table">
                                            <thead>
                                                <tr>
                                                    <th class="col-material">Material</th>
                                                    <th class="col-qty">Qty</th>
                                                    <th class="col-price">Price</th>
                                                    <th class="col-discount">Discount</th>
                                                    <th class="col-total">Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select width="100%" name="details[0][material_id]"
                                                            class="form-control select2" required
                                                            onchange="fetchMaterialInfo(this)">
                                                            <option disabled selected>Select a material</option>
                                                            @foreach ($materials as $material)
                                                                <option value="{{ $material->id }}">{{ $material->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="details[0][qty]"
                                                            class="form-control qty" step="0.01" required>
                                                        <span class="unit"></span>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="details[0][price]"
                                                            class="form-control price" step="0.01" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="details[0][discount]"
                                                            class="form-control discount" step="0.01" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="details[0][total]"
                                                            class="form-control total" step="0.01" readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-danger remove-row">Remove</button>
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
                                                    <th>Subtotal</th>
                                                    <th>Taxes</th>
                                                    <th>Freight</th>
                                                    <th>Discount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="total-subtotal"><input type="number" name="subtotal" id="subtotal" class="form-control" readonly></td>
                                                    <td id="total-taxes"><input type="number" name="taxes" id="taxes" class="form-control" readonly></td>
                                                    <td id="total-freight"><input type="number" name="freight" id="freight" class="form-control" value="0" required></td>
                                                    <td id="total-discount"><input type="number" name="discount" id="discount" class="form-control" value="0" required></td>
                                                </tr>
                                            </tbody>
                                            <thead>
                                                <tr>
                                                    <th>Grand Total</th>
                                                    <th>Gateway</th>
                                                    <th>Paid</th>
                                                    <th>Left</th>
                                                    {{-- <th>Cashback</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="grand-total"><input type="number" name="grand_total" id="grand_total" class="form-control" readonly></td>
                                                    <td>
                                                        <select width="100%" id="payment_gateway_id" name="payment_gateway_id"
                                                            class="form-control select2">
                                                            <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'payment_gateway')) }}</option>
                                                            @foreach ($payment_gateways as $payment_gateway)
                                                                <option value="{{ $payment_gateway->id }}">{{ $payment_gateway->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td id="total-paid"><input type="number" name="paid" id="paid" class="form-control" required></td>
                                                    <td id="total-left"><input type="number" name="left" id="left" class="form-control" value="0"  readonly></td>
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
