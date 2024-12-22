@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'material')) }}
@endsection

@section('material-active')
    {{ 'active' }}
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('material.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-bordered table-hovered" id="material_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ strtoupper(str_replace('_', ' ', 'id')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'name')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'sub_category')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'unit')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'sell')) }}<sub>({{$setup->currency->symbol}})</sub></th>
                                <th>{{ ucwords(str_replace('_', ' ', 'buy')) }}<sub>({{$setup->currency->symbol}})</sub></th>
                                @foreach($warehouses as $warehouse)
                                <th>{{ ucwords(str_replace('_', ' ', $warehouse->name)) }}</th>
                                @endforeach
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
            $('#material_table').DataTable({
                layout: {
                    bottomStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('material.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'material_sub_category_id',
                        name: 'material_sub_category.name'
                    },
                    {
                        data: 'unit_id',
                        name: 'unit.name'
                    },
                    {
                        data: 'sell_price',
                        name: 'sell_price',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return data === null ? '-' : formatCurrency(data);
                        }
                    },
                    {
                        data: 'buy_price',
                        name: 'buy_price',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return data === null ? '-' : formatCurrency(data);
                        }
                    },
                    @foreach($warehouses as $warehouse)
                    {
                        data: '{{ str_replace(" ", "_", $warehouse->name) }}',
                        name: '{{ str_replace(" ", "_", $warehouse->name) }}',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return data === null ? '-' : formatCurrency(data);
                        }
                    },
                    @endforeach
                    {
                        data: null,
                        name: 'actions',
                        render: function(data, type, row) {
                            return `
                                <div class="btn-group" role="group" aria-label="manage">
                                    <a href="{{ url('material') }}/${row.id}/edit" class="btn btn-secondary btn-sm">Edit</a>
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
                const materialId = $(this).data('id');
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
                            action: `{{ url('material') }}/${materialId}`
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
