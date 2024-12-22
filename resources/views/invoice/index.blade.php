@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'invoice')) }}
@endsection

@section('invoice-active')
    {{ 'active' }}
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('invoice.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-bordered table-hovered" id="invoice_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ strtoupper(str_replace('_', ' ', 'id')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'timestamp')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'category')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'supplier')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'customer')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'warehouse')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'grand_total')) }}<sub>({{$setup->currency->symbol}})</sub></th>
                                <th>{{ ucwords(str_replace('_', ' ', 'left')) }}<sub>({{$setup->currency->symbol}})</sub></th>
                                <th>{{ ucwords(str_replace('_', ' ', 'action')) }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#invoice_table').DataTable({
                layout: {
                    bottomStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('invoice.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'invoice_category_id',
                        name: 'invoice_category.name'
                    },
                    {
                        data: 'supplier_id',
                        name: 'supplier.name'
                    },
                    {
                        data: 'customer_id',
                        name: 'customer.name'
                    },
                    {
                        data: 'warehouse_id',
                        name: 'warehouse.name'
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return formatCurrency(data);
                        }
                    },
                    {
                        data: 'left',
                        name: 'left',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return formatCurrency(data);
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        render: function(data, type, row) {
                            return `
                                <div class="btn-group" role="group" aria-label="manage">
                                    <a href="{{ url('invoice') }}/${row.id}" class="btn btn-info btn-sm">Show</a>
                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" data-name="${row.id}">Delete</button>
                                </div>
                            `;
                        }
                    }
                ]
            });

            // Event delegation for delete buttons
            $(document).on('click', '.delete-btn', function(event) {
                event.preventDefault();
                const invoiceId = $(this).data('id');
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

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
                        const form = $('<form>', {
                            method: 'POST',
                            action: `{{ url('invoice') }}/${invoiceId}`
                        });

                        $('<input>', {
                            type: 'hidden',
                            name: '_method',
                            value: 'DELETE'
                        }).appendTo(form);

                        $('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: csrfToken
                        }).appendTo(form);

                        form.appendTo('body').submit();
                    }
                });
            });
        });
    </script>
@endsection
