@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'attendance')) }}
@endsection

@section('attendance-active')
    {{ 'active' }}
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-bordered table-hovered" id="attendance_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ strtoupper(str_replace('_', ' ', 'id')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'date')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'employee')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'shift')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'check_in')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'eci')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'lci')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'check_out')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'eco')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'lco')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'credit')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'basic')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'net')) }}</th>
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
            $('#attendance_table').DataTable({
                layout: {
                    bottomStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('attendance.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'employee_id',
                        name: 'employee.name'
                    },
                    {
                        data: 'shift_id',
                        name: 'shift.name'
                    },
                    {
                        data: 'check_in',
                        name: 'check_in'
                    },
                    {
                        data: 'early_check_in',
                        name: 'early_check_in'
                    },
                    {
                        data: 'late_check_in',
                        name: 'late_check_in'
                    },
                    {
                        data: 'check_out',
                        name: 'check_out'
                    },
                    {
                        data: 'early_check_out',
                        name: 'early_check_out'
                    },
                    {
                        data: 'late_check_out',
                        name: 'late_check_out'
                    },
                    {
                        data: 'credit',
                        name: 'credit'
                    },
                    {
                        data: 'basic_salary',
                        name: 'basic_salary',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return data === '-' ? '-' : parseFloat(data).toLocaleString('en-US', {
                                maximumFractionDigits: 0 // Menghapus angka di belakang koma
                            });
                        }
                    },
                    {
                        data: 'net_salary',
                        name: 'net_salary',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return data === '-' ? '-' : parseFloat(data).toLocaleString('en-US', {
                                maximumFractionDigits: 0 // Menghapus angka di belakang koma
                            });
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        render: function(data, type, row) {
                            return `
                                <div class="btn-group" role="group" aria-label="manage">
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
                const attendanceId = $(this).data('id');
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
                            action: `{{ url('attendance') }}/${attendanceId}`
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
