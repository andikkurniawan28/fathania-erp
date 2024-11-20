@extends('template.sneat.master')

@section('title', ucwords(str_replace('_', ' ', 'income_statement')))

@section('income_statement-active', 'active')

@section('content')

    <style>
        .account_group {
            padding-left: 20px; /* Adjust this value to match the padding for total cells */
        }

        .main_account {
            padding-left: 40px; /* Adjust if necessary */
        }

        .sub_account {
            padding-left: 60px; /* Adjust if necessary */
        }

        .account {
            padding-left: 80px; /* Adjust if necessary */
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
                <form action="{{ route('closing_entry') }}" method="POST">
                <div class="row mb-3">
                    @csrf @method("POST")
                    <div class="col-md-3">
                        <label>Year</label>
                        <input type="number" id="year" name="year" class="form-control" placeholder="Year" value="{{ date('Y') }}">
                    </div>
                    <div class="col-md-3">
                        <label>Month</label>
                        <input type="number" id="month" name="month" class="form-control" placeholder="Month (1-12)"
                            value="{{ date('m') }}" min="1" max="12">
                    </div>
                    <div class="col-md-6">
                        <br>
                        <a id="submit_button" class="btn btn-primary text-white">Generate Report</a>
                        <a id="print_pdf_button" class="btn btn-secondary text-white">Print PDF</a>
                        <button type href="{{ route('closing_entry') }}" class="btn btn-dark">Closing Entry</button>
                    </div>
                </div>
                </form>

                <!-- Laporan Income Statement -->
                <div id="income_statement_report">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm" id="income_statement_table">
                                <thead>
                                    <tr>
                                        <th class="text-primary">Description</th>
                                        <th class="text-primary">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th><strong>Total Revenue</strong></th>
                                        <td id="total_revenue"></td>
                                    </tr>
                                    <tr>
                                        <th><strong>Total COGS</strong></th>
                                        <td id="total_cogs"></td>
                                    </tr>
                                    <tr>
                                        <th><strong>Total Expenses</strong></th>
                                        <td id="total_expenses"></td>
                                    </tr>
                                    <tr>
                                        <th><strong>Net Income</strong></th>
                                        <td id="net_income"></td>
                                    </tr>
                                </tfoot>
                            </table>
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
                var url = "{{ url('income_statement/data') }}/" + year + "/" + month;

                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        year: year,
                        month: month
                    },
                    success: function(response) {
                        console.log('API Response:', response); // Debug API response

                        $('#income_statement_table tbody').empty();

                        var total_revenue = 0;
                        var total_cogs = 0;
                        var total_expenses = 0;

                        var data_html = '';

                        function renderAccount(account, level) {
                            var account_total = parseFloat(account.income_statement) || 0;
                            var padding_left = level * 20; // Adjust padding as needed

                            data_html += `
                                <tr>
                                    <td style="padding-left: ${padding_left}px;">${account.name}</td>
                                    <td>${number_format(account_total)}</td>
                                </tr>`;

                            return account_total;
                        }


                        $.each(response.data, function(index, account_group) {
                            if (['Pendapatan Penjualan', 'Pendapatan Lain-lain'].includes(account_group.name)) {
                                var group_total = parseFloat(account_group.income_statement) || 0;

                                console.log('Group Total (Revenue):', group_total);

                                data_html += `
                                    <tr>
                                        <td style="padding-left: 10px;"><strong>${account_group.name}</strong></td>
                                        <td>${number_format(group_total)}</td>
                                    </tr>`;

                                total_revenue += group_total;

                                $.each(account_group.main_account, function(index, main_account) {
                                    var main_total = renderAccount(main_account, 1);

                                    $.each(main_account.sub_account, function(index, sub_account) {
                                        var sub_total = renderAccount(sub_account, 2);

                                        $.each(sub_account.account, function(index, account) {
                                            var account_total = renderAccount(account, 3);
                                        });
                                    });
                                });
                            }
                        });

                        $.each(response.data, function(index, account_group) {
                            if (['Harga Pokok Penjualan'].includes(account_group.name)) {
                                var group_total = parseFloat(account_group.income_statement) || 0;

                                console.log('Group Total (COGS):', group_total);

                                data_html += `
                                    <tr>
                                        <td style="padding-left: 10px;"><strong>${account_group.name}</strong></td>
                                        <td>${number_format(group_total)}</td>
                                    </tr>`;

                                total_cogs += group_total;

                                $.each(account_group.main_account, function(index, main_account) {
                                    var main_total = renderAccount(main_account, 1);

                                    $.each(main_account.sub_account, function(index, sub_account) {
                                        var sub_total = renderAccount(sub_account, 2);

                                        $.each(sub_account.account, function(index, account) {
                                            var account_total = renderAccount(account, 3);
                                        });
                                    });
                                });
                            }
                        });

                        $.each(response.data, function(index, account_group) {
                            if (['Beban Operasional', 'Beban Lain-lain'].includes(account_group.name)) {
                                var group_total = parseFloat(account_group.income_statement) || 0;

                                console.log('Group Total (Expenses):', group_total);

                                data_html += `
                                    <tr>
                                        <td style="padding-left: 10px;"><strong>${account_group.name}</strong></td>
                                        <td>${number_format(group_total)}</td>
                                    </tr>`;

                                total_expenses += group_total;

                                $.each(account_group.main_account, function(index, main_account) {
                                    var main_total = renderAccount(main_account, 1);

                                    $.each(main_account.sub_account, function(index, sub_account) {
                                        var sub_total = renderAccount(sub_account, 2);

                                        $.each(sub_account.account, function(index, account) {
                                            var account_total = renderAccount(account, 3);
                                        });
                                    });
                                });
                            }
                        });

                        console.log('Generated HTML:', data_html);

                        $('#income_statement_table tbody').append(data_html);
                        $('#total_revenue').text(number_format(total_revenue));
                        $('#total_cogs').text(number_format(total_cogs));
                        $('#total_expenses').text(number_format(total_expenses));
                        $('#net_income').text(number_format(total_revenue - total_cogs - total_expenses));
                    }
                });
            });

            $('#print_pdf_button').click(function() {
                html2canvas(document.querySelector("#income_statement_report"), {
                    scale: 2 // Skala untuk meningkatkan kualitas gambar
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

                    pdf.save('income_statement.pdf');
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

