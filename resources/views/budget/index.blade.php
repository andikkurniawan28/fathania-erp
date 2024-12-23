@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'budget')) }}
@endsection

@section('budget-active')
    {{ 'active' }}
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('budget.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-bordered table-hovered" id="budget_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ strtoupper(str_replace('_', ' ', 'id')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'updated_at')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'user')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'account')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'start')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'end')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'amount')) }}<sub>({{$setup->currency->symbol}})</sub></th>
                                <th>{{ ucwords(str_replace('_', ' ', 'spent')) }}<sub>({{$setup->currency->symbol}})</sub></th>
                                <th>{{ ucwords(str_replace('_', ' ', 'remaining')) }}<sub>({{$setup->currency->symbol}})</sub></th>
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
            $('#budget_table').DataTable({
                layout: {
                    bottomStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('budget.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'user_id',
                        name: 'user.name'
                    },
                    {
                        data: 'account_id',
                        name: 'account.name'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return formatCurrency(data);
                        }
                    },
                    {
                        data: 'spent',
                        name: 'spent',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return formatCurrency(data);
                        }
                    },
                    {
                        data: 'remaining',
                        name: 'remaining',
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
                                    <a href="{{ url('budget') }}/${row.id}/edit" class="btn btn-secondary btn-sm">Edit</a>
                                    <a href="{{ url('budget_refresh') }}/${row.id}" class="btn btn-success btn-sm">Refresh</a>
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
                const budgetId = $(this).data('id');
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
                            action: `{{ url('budget') }}/${budgetId}`
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
