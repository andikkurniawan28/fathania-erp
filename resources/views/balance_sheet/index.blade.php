@extends('template.sneat.master')

@section('title', ucwords(str_replace('_', ' ', 'balance_sheet')))

@section('balance_sheet-active', 'active')

@section('content')

    <style>
        .account_group {
            padding-left: 0px;
        }

        .main_account {
            padding-left: 20px;
        }

        .sub_account {
            padding-left: 40px;
        }

        .account {
            padding-left: 60px;
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4><strong>@yield('title')</strong></h4>

                <!-- Form untuk Tahun dan Bulan -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Year</label>
                        <input type="number" id="year" class="form-control" placeholder="Year" value="{{ date('Y') }}">
                    </div>
                    <div class="col-md-4">
                        <label>Month</label>
                        <input type="number" id="month" class="form-control" placeholder="Month (1-12)"
                            value="{{ date('m') }}" min="1" max="12">
                    </div>
                    <div class="col-md-4">
                        <br>
                        <button id="submit_button" class="btn btn-primary">Generate Report</button>
                        <button id="print_pdf_button" class="btn btn-secondary">Print PDF</button>
                    </div>
                </div>

                <!-- Laporan Balance Sheet -->
                <div id="balance_sheet_report">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm" id="assets_table">
                                <thead>
                                    <tr>
                                        <td class="text-primary"><strong>Assets</strong></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>

                            <br>
                        </div>
                        <div class="col-md-6">

                            <table class="table table-bordered table-sm" id="liabilities_table">
                                <thead>
                                    <tr>
                                        <td class="text-primary"><strong>Liabilities</strong></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>

                            <br>

                            <table class="table table-bordered table-sm" id="equity_table">
                                <thead>
                                    <tr>
                                        <td class="text-primary"><strong>Equity</strong></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>

                            <br>

                            <table class="table table-bordered table-sm" id="comparison_table">
                                <thead>
                                    <tr>
                                        <td class="text-primary"><strong>Comparison</strong></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("additional_script")
    <!-- Tambahkan link ke jsPDF dan html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#submit_button').click(function() {
                var year = $('#year').val();
                var month = $('#month').val();
                var url = "{{ url('balance_sheet/data') }}/" + year + "/" + month;  // Bangun URL dengan parameter

                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        year: year,
                        month: month
                    },
                    success: function(response) {
                        // Bersihkan tabel sebelum mengisi data
                        $('#assets_table tbody').empty();
                        $('#liabilities_table tbody').empty();
                        $('#equity_table tbody').empty();
                        $('#comparison_table tbody').empty();

                        // Inisialisasi total
                        var total_assets = 0;
                        var total_liabilities = 0;
                        var total_equity = 0;

                        // Render kolom Assets
                        var assets_html = '';
                        $.each(response.data, function(index, account_group) {
                            if (['Aktiva Lancar', 'Aktiva Tetap', 'Aktiva Lain-lain'].includes(account_group.name)) {
                                var group_total = account_group.balance_sheet;

                                assets_html += `
                                    <tr>
                                        <td><div class="account_group text-dark"><strong>${account_group.name}</strong></div></td>
                                        <td><div class="text-dark"><strong>${formatCurrency2(group_total)}</strong></div></td>
                                    </tr>`;

                                total_assets += group_total;

                                $.each(account_group.main_account, function(index, main_account) {
                                    var main_total = main_account.balance_sheet;

                                    assets_html += `
                                        <tr>
                                            <td><div class="main_account text-dark">${main_account.name}</div></td>
                                            <td>${formatCurrency2(main_total)}</td>
                                        </tr>`;

                                    $.each(main_account.sub_account, function(index, sub_account) {
                                        var sub_total = sub_account.balance_sheet;

                                        assets_html += `
                                            <tr>
                                                <td><div class="sub_account text-dark">${sub_account.name}</div></td>
                                                <td>${formatCurrency2(sub_total)}</td>
                                            </tr>`;

                                        $.each(sub_account.account, function(index, account) {
                                            var account_total = account.balance_sheet;

                                            assets_html += `
                                                <tr>
                                                    <td><div class="account text-dark">${account.name}</div></td>
                                                    <td>${formatCurrency2(account_total)}</td>
                                                </tr>`;
                                        });
                                    });
                                });
                            }
                        });
                        $('#assets_table tbody').append(assets_html);
                        $('#assets_table tbody').append(`
                            <tr>
                                <td><strong>Total Assets</strong></td>
                                <td><strong>${formatCurrency2(total_assets)}</strong></td>
                            </tr>
                        `);

                        // Render kolom Liabilities
                        var liabilities_html = '';
                        $.each(response.data, function(index, account_group) {
                            if (['Kewajiban Lancar', 'Kewajiban Jangka Panjang'].includes(account_group.name)) {
                                var group_total = account_group.balance_sheet;

                                liabilities_html += `
                                    <tr>
                                        <td><div class="account_group text-dark"><strong>${account_group.name}</strong></div></td>
                                        <td><div class="text-dark"><strong>${formatCurrency2(group_total)}</strong></div></td>
                                    </tr>`;

                                total_liabilities += group_total;

                                $.each(account_group.main_account, function(index, main_account) {
                                    var main_total = main_account.balance_sheet;

                                    liabilities_html += `
                                        <tr>
                                            <td><div class="main_account text-dark">${main_account.name}</div></td>
                                            <td>${formatCurrency2(main_total)}</td>
                                        </tr>`;

                                    $.each(main_account.sub_account, function(index, sub_account) {
                                        var sub_total = sub_account.balance_sheet;

                                        liabilities_html += `
                                            <tr>
                                                <td><div class="sub_account text-dark">${sub_account.name}</div></td>
                                                <td>${formatCurrency2(sub_total)}</td>
                                            </tr>`;

                                        $.each(sub_account.account, function(index, account) {
                                            var account_total = account.balance_sheet;

                                            liabilities_html += `
                                                <tr>
                                                    <td><div class="account text-dark">${account.name}</div></td>
                                                    <td>${formatCurrency2(account_total)}</td>
                                                </tr>`;
                                        });
                                    });
                                });
                            }
                        });
                        $('#liabilities_table tbody').append(liabilities_html);
                        $('#liabilities_table tbody').append(`
                            <tr>
                                <td><strong>Total Liabilities</strong></td>
                                <td><strong>${formatCurrency2(total_liabilities)}</strong></td>
                            </tr>
                        `);

                        // Render kolom Equity
                        var equity_html = '';
                        $.each(response.data, function(index, account_group) {
                            if (account_group.name === 'Modal') {
                                var group_total = account_group.balance_sheet;

                                equity_html += `
                                    <tr>
                                        <td><div class="account_group text-dark"><strong>${account_group.name}</strong></div></td>
                                        <td><div class="text-dark"><strong>${formatCurrency2(group_total)}</strong></div></td>
                                    </tr>`;

                                total_equity += group_total;

                                $.each(account_group.main_account, function(index, main_account) {
                                    var main_total = main_account.balance_sheet;

                                    equity_html += `
                                        <tr>
                                            <td><div class="main_account text-dark">${main_account.name}</div></td>
                                            <td>${formatCurrency2(main_total)}</td>
                                        </tr>`;

                                    $.each(main_account.sub_account, function(index, sub_account) {
                                        var sub_total = sub_account.balance_sheet;

                                        equity_html += `
                                            <tr>
                                                <td><div class="sub_account text-dark">${sub_account.name}</div></td>
                                                <td>${formatCurrency2(sub_total)}</td>
                                            </tr>`;

                                        $.each(sub_account.account, function(index, account) {
                                            var account_total = account.balance_sheet;

                                            equity_html += `
                                                <tr>
                                                    <td><div class="account text-dark">${account.name}</div></td>
                                                    <td>${formatCurrency2(account_total)}</td>
                                                </tr>`;
                                        });
                                    });
                                });
                            }
                        });
                        $('#equity_table tbody').append(equity_html);
                        $('#equity_table tbody').append(`
                            <tr>
                                <td><strong>Total Equity</strong></td>
                                <td><strong>${formatCurrency2(total_equity)}</strong></td>
                            </tr>
                        `);

                        // Tampilkan tabel perbandingan
                        $('#comparison_table tbody').append(`
                            <tr>
                                <td><strong>Total Liabilities + Equity</strong></td>
                                <td><strong>${formatCurrency2(total_liabilities + total_equity)}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Total Assets</strong></td>
                                <td><strong>${formatCurrency2(total_assets)}</strong></td>
                            </tr>
                        `);
                    }
                });
            });

            $('#print_pdf_button').click(function() {
                html2canvas(document.querySelector("#balance_sheet_report"), {
                    scale: 2 // Skala untuk meningkatkan kualitas gambar
                }).then(canvas => {
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const imgData = canvas.toDataURL('image/png');
                    const imgWidth = pdf.internal.pageSize.getWidth();
                    const imgHeight = canvas.height * imgWidth / canvas.width;
                    let heightLeft = imgHeight;
                    let position = 0;

                    // Menambahkan halaman pertama
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pdf.internal.pageSize.height;

                    // Menambahkan halaman-halaman berikutnya jika diperlukan
                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pdf.internal.pageSize.height;
                    }

                    pdf.save('balance_sheet.pdf');
                });
            });
        });

        function formatCurrency2(number) {
            if (number < 0) {
                return `(${formatCurrency(Math.abs(number))})`;
            }
            return `${formatCurrency(number)}`;
        }
    </script>
@endsection
