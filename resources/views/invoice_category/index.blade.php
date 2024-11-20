@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'invoice_category')) }}
@endsection

@section('invoice_category-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('invoice_category.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-hover table-bordered" id="example" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2">{{ strtoupper(str_replace('_', ' ', 'id')) }}</th>
                                <th rowspan="2">{{ ucwords(str_replace('_', ' ', 'name')) }}</th>
                                <th rowspan="2">{{ ucwords(str_replace('_', ' ', 'deal_with')) }}</th>
                                <th rowspan="2">{{ ucwords(str_replace('_', ' ', 'item')) }}</th>
                                <th rowspan="2">{{ ucwords(str_replace('_', ' ', 'price_used')) }}</th>
                                <th rowspan="2">{{ ucwords(str_replace('_', ' ', 'stock')) }}</th>
                                <th colspan="2">{{ ucwords(str_replace('_', ' ', 'subtotal')) }}</th>
                                <th colspan="2">{{ ucwords(str_replace('_', ' ', 'taxes')) }}</th>
                                <th colspan="2">{{ ucwords(str_replace('_', ' ', 'freight')) }}</th>
                                <th colspan="2">{{ ucwords(str_replace('_', ' ', 'discount')) }}</th>
                                <th colspan="2">{{ ucwords(str_replace('_', ' ', 'grand_total')) }}</th>
                                <th rowspan="2">{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
                            </tr>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'ac')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'nb')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'ac')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'nb')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'ac')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'nb')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'ac')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'nb')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'ac')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'nb')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice_categories as $invoice_category)
                                <tr>
                                    <td>{{ $invoice_category->id }}</td>
                                    <td>{{ $invoice_category->name }}</td>
                                    <td>{{ $invoice_category->deal_with }}</td>
                                    <td>{{ $invoice_category->item }}</td>
                                    <td>{{ $invoice_category->price_used }}</td>
                                    <td>{{ $invoice_category->stock_normal_balance->name ?? "-" }}</td>
                                    <td>{{ $invoice_category->subtotal_account->name }}</td>
                                    <td>{{ $invoice_category->subtotal_normal_balance->name }}</td>
                                    <td>{{ $invoice_category->taxes_account->name }}</td>
                                    <td>{{ $invoice_category->taxes_normal_balance->name }}</td>
                                    <td>{{ $invoice_category->freight_account->name }}</td>
                                    <td>{{ $invoice_category->freight_normal_balance->name }}</td>
                                    <td>{{ $invoice_category->discount_account->name }}</td>
                                    <td>{{ $invoice_category->discount_normal_balance->name }}</td>
                                    <td>{{ $invoice_category->grand_total_account->name }}</td>
                                    <td>{{ $invoice_category->grand_total_normal_balance->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="manage">
                                            <a href="{{ route('invoice_category.edit', $invoice_category->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $invoice_category->id }}" data-name="{{ $invoice_category->name }}">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const invoice_category_id = this.getAttribute('data-id');
                    const invoice_category_name = this.getAttribute('data-name');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You won\'t be able to revert this!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.setAttribute('method', 'POST');
                            form.setAttribute('action',
                                `{{ route('invoice_category.destroy', ':id') }}`.replace(
                                    ':id', invoice_category_id));
                            const csrfToken = document.getElementsByName("_token")[0].value;

                            const hiddenMethod = document.createElement('input');
                            hiddenMethod.setAttribute('type', 'hidden');
                            hiddenMethod.setAttribute('name', '_method');
                            hiddenMethod.setAttribute('value', 'DELETE');

                            const name = document.createElement('input');
                            name.setAttribute('type', 'hidden');
                            name.setAttribute('name', 'name');
                            name.setAttribute('value', invoice_category_name);

                            const csrfTokenInput = document.createElement('input');
                            csrfTokenInput.setAttribute('type', 'hidden');
                            csrfTokenInput.setAttribute('name', '_token');
                            csrfTokenInput.setAttribute('value', csrfToken);

                            form.appendChild(hiddenMethod);
                            form.appendChild(name);
                            form.appendChild(csrfTokenInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
