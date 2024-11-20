@extends('template.sneat.master')

@section('title', ucwords(str_replace('_', ' ', 'cash_flow')))

@section('cash_flow-active', 'active')

@section('content')

    <style>
        .account_group {
            padding-left: 20px;
        }

        .main_account {
            padding-left: 40px;
        }

        .sub_account {
            padding-left: 60px;
        }

        .account {
            padding-left: 80px;
        }

        .table-bordered th,
        .table-bordered td {
            text-align: left;
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4><strong>@yield('title')</strong></h4>

                <!-- Form untuk Tahun dan Bulan -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Year</label>
                        <input type="number" id="year" class="form-control" placeholder="Tahun" value="{{ date('Y') }}">
                    </div>
                    <div class="col-md-3">
                        <label>Month</label>
                        <input type="number" id="month" class="form-control" placeholder="Bulan (1-12)"
                            value="{{ date('m') }}" min="1" max="12">
                    </div>
                    <div class="col-md-6">
                        <br>
                        <button id="submit_button" class="btn btn-primary">Generate Report</button>
                        <button id="print_pdf_button" class="btn btn-secondary">Print PDF</button>
                    </div>
                </div>

                <!-- Laporan Arus Kas -->
                <div id="cash_flow">
                    <div class="row">
                        <div class="col-md-12">
                            @foreach($cash_flow_categories as $category)
                                <h6>{{ $category->name }}</h6>
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Account</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $category_total = 0; @endphp
                                        @foreach($category->account as $account)
                                            @php $category_total += $account->total; @endphp
                                            <tr>
                                                <td>{{ $account->id }}</td>
                                                <td>{{ $account->name }}</td>
                                                <td>{{ number_format($account->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"><strong>Total {{ $category->name }}</strong></td>
                                            <td><strong>{{ number_format($category_total, 2) }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#submit_button').click(function() {
                var year = $('#year').val();
                var month = $('#month').val();
                var url = "{{ url('cash_flow/data') }}/" + year + "/" + month;

                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        year: year,
                        month: month
                    },
                    success: function(response) {
                        console.log('API Response:', response); // Debug API response

                        $('#cash_flow').empty();

                        $.each(response.data, function(index, category) {
                            var category_html = `
                                <h6>${category.name}</h6>
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Account</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                            var category_total = 0;

                            $.each(category.account, function(index, account) {
                                category_total += parseFloat(account.total);

                                category_html += `
                                    <tr>
                                        <td>${account.id}</td>
                                        <td>${account.name}</td>
                                        <td>${number_format(account.total)}</td>
                                    </tr>`;
                            });

                            category_html += `
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"><strong>Total ${category.name}</strong></td>
                                            <td><strong>${number_format(category_total)}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>`;

                            $('#cash_flow').append(category_html);
                        });
                    }
                });
            });

            $('#print_pdf_button').click(function() {
                html2canvas(document.querySelector("#cash_flow"), {
                    scale: 2
                }).then(canvas => {
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const imgData = canvas.toDataURL('image/png');
                    const imgWidth = pdf.internal.pageSize.getWidth();
                    const imgHeight = canvas.height * imgWidth / canvas.width;
                    let heightLeft = imgHeight;
                    let position = 0;

                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pdf.internal.pageSize.height;

                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pdf.internal.pageSize.height;
                    }

                    pdf.save('cash_flow.pdf');
                });
            });

            function number_format(number) {
                if (number < 0) {
                    return `(${Math.abs(number).toLocaleString('en-US')})`;
                }
                return number.toLocaleString('en-US');
            }
        });
    </script>

@endsection
