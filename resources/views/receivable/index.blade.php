@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'receivable')) }}
@endsection

@section('receivable-active')
    {{ 'active' }}
@endsection

@section('content')
    <style>
        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4><strong>@yield('title')</strong></h4>

                <!-- Form untuk Select2 dan Tanggal -->
                <form action="{{ route('posting') }}" method="POST">
                @csrf @method("POST")
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Customer</label>
                        <select id="customer_select" class="form-control select2" name="third_party_id">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>From</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                               value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label>To</label>
                        <?php
                        // Mengatur tanggal hari ini
                        $today = new DateTime();
                        // Menambahkan satu hari
                        $nextDay = $today->modify('+1 day')->format('Y-m-d');
                        ?>
                        <input type="date" id="end_date" name="end_date" class="form-control" placeholder="End Date"
                            value="{{ $nextDay }}">
                    </div>
                    <div class="col-md-2">
                        <br>
                        <div class="btn-group" role="group" aria-label="Button group">
                            <a id="filter_button" class="btn btn-primary text-white">Filter</a>
                            {{-- <button type="submit" class="btn btn-secondary text-white">Posting</button> --}}
                        </div>
                    </div>
                </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered" id="receivable_table" width="100%">
                        <thead>
                            <tr>
                                <th class="text-left">{{ ucwords(str_replace('_', ' ', 'date')) }}</th>
                                <th class="text-left">{{ ucwords(str_replace('_', ' ', 'description')) }}</th>
                                <th class="text-right">{{ ucwords(str_replace('_', ' ', 'amount')) }}</th>
                                <th class="text-right">{{ ucwords(str_replace('_', ' ', 'balance')) }}</th>
                                <th class="text-left">{{ ucwords(str_replace('_', ' ', 'user')) }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#customer_select').select2({
                theme: 'bootstrap',
                placeholder: "Select an customer",
                allowClear: true // Mengaktifkan opsi clearable
            });

            // Inisialisasi DataTable
            var table = $('#receivable_table').DataTable({
                layout: {
                    bottomStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "",
                    type: "GET",
                    data: function(d) {
                        d.third_party_id = $('#customer_select').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                order: [
                    [0, 'asc']
                ],
                columns: [{
                        data: 'created_at',
                        name: 'created_at',
                        class: 'text-left'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        class: 'text-left'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return data === '-' ? '-' : parseFloat(data).toLocaleString('en-US', {
                                maximumFractionDigits: 0 // Menghapus angka di belakang koma
                            });
                        }
                    },
                    {
                        data: 'balance',
                        name: 'balance',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return data === '-' ? '-' : parseFloat(data).toLocaleString('en-US', {
                                maximumFractionDigits: 0 // Menghapus angka di belakang koma
                            });
                        }
                    },
                    {
                        data: 'user.name',
                        name: 'user.name',
                        class: 'text-left'
                    },
                ],
                headerCallback: function(thead, data, start, end, display) {
                    $(thead).find('th').each(function() {
                        var className = $(this).attr('class');
                        $(this).addClass(
                        className); // Ensure the header cells have correct alignment
                    });
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td').each(function() {
                        var className = $(this).attr('class');
                        $(this).addClass(
                        className); // Ensure the data cells have correct alignment
                    });
                },
                "bPaginate": false, // Menonaktifkan pagination
                "bFilter": false, // Menonaktifkan pencarian
                "bInfo": false, // Menonaktifkan informasi jumlah data
                "bServerSide": false, // Nonaktifkan server-side processing saat inisialisasi
            });

            // Event Listener untuk Filter Button
            $('#filter_button').on('click', function() {
                var third_party = "customer";
                var third_party_id = $('#customer_select').val();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                if (third_party_id && start_date && end_date) {
                    // Set ajax URL dengan parameter yang dibutuhkan dan reload DataTable
                    var url =
                        "{{ route('receivable.data', ['third_party' => ':third_party', 'third_party_id' => ':third_party_id', 'start_date' => ':start_date', 'end_date' => ':end_date']) }}";
                    url = url.replace(':third_party', third_party)
                        .replace(':third_party_id', third_party_id)
                        .replace(':start_date', start_date)
                        .replace(':end_date', end_date);

                    table.ajax.url(url).load();
                } else {
                    alert('Please select an customer and specify both start and end dates.');
                }
            });

        });
    </script>
@endsection
