@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'payable')) }}
@endsection

@section('payable-active')
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
                        <label>Supplier</label>
                        <select id="supplier_select" class="form-control select2" name="third_party_id">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                    <table class="table table-bordered" id="payable_table" width="100%">
                        <thead>
                            <tr>
                                <th class="text-left">{{ ucwords(str_replace('_', ' ', 'date')) }}</th>
                                <th class="text-left">{{ ucwords(str_replace('_', ' ', 'description')) }}</th>
                                <th class="text-right">{{ ucwords(str_replace('_', ' ', 'amount')) }}<sub>({{$setup->currency->symbol}})</sub></th>
                                <th class="text-right">{{ ucwords(str_replace('_', ' ', 'balance')) }}<sub>({{$setup->currency->symbol}})</sub></th>
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
            $('#supplier_select').select2({
                theme: 'bootstrap',
                placeholder: "Select an supplier",
                allowClear: true // Mengaktifkan opsi clearable
            });

            // Inisialisasi DataTable
            var table = $('#payable_table').DataTable({
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
                        d.third_party_id = $('#supplier_select').val();
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
                            return formatCurrency(data);
                        }
                    },
                    {
                        data: 'balance',
                        name: 'balance',
                        class: 'text-right',
                        render: function(data, type, row) {
                            return formatCurrency(data);
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
                var third_party = "supplier";
                var third_party_id = $('#supplier_select').val();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                if (third_party_id && start_date && end_date) {
                    // Set ajax URL dengan parameter yang dibutuhkan dan reload DataTable
                    var url =
                        "{{ route('payable.data', ['third_party' => ':third_party', 'third_party_id' => ':third_party_id', 'start_date' => ':start_date', 'end_date' => ':end_date']) }}";
                    url = url.replace(':third_party', third_party)
                        .replace(':third_party_id', third_party_id)
                        .replace(':start_date', start_date)
                        .replace(':end_date', end_date);

                    table.ajax.url(url).load();
                } else {
                    alert('Please select an supplier and specify both start and end dates.');
                }
            });

        });
    </script>
@endsection
