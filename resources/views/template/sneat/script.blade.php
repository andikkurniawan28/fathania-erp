<!-- Core JS -->
<script>
    // Fungsi untuk format angka dengan separator sesuai pengaturan
    function formatCurrency(data) {
        if (data === '-') {
            return '-';
        }

        // Ambil nilai separator dari Blade
        var decimalSeparator = '{{ $setup->currency->decimal_separator }}'; // Misal: ','
        var thousandSeparator = '{{ $setup->currency->thousand_separator }}'; // Misal: '.'

        // Format angka dengan PHP-style number_format
        // Misal format dengan 2 angka di belakang koma
        var formattedData = parseFloat(data).toFixed(2); // Format dengan 2 angka desimal

        // Ganti titik dengan separator desimal dan koma dengan separator ribuan sesuai pengaturan
        formattedData = formattedData.replace('.', decimalSeparator); // Ganti titik dengan separator desimal

        // Pisahkan angka menjadi ribuan dan format separator ribuan
        var parts = formattedData.split(decimalSeparator);
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator); // Format ribuan
        return parts.join(decimalSeparator); // Gabungkan kembali bagian ribuan dan desimal
    }
</script>

<!-- build:js /sneat/js/core.js -->
<script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('sneat/assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('sneat/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('sneat/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('sneat/assets/js/dashboards-analytics.js') }}"></script>

<!-- Datatable -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>

<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Bootstrap Select JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0/js/bootstrap-select.min.js"></script>

<!-- Bootstrap JS (needed for Bootstrap Select) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    new DataTable('#example', {
        order: [
            [0, 'desc']
        ],
        layout: {
            bottomStart: {
                buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
            },
        },
    });
</script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script><!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/amiryxe/easy-number-separator/easy-number-separator.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const thousandSeparator = '{{ $setup->currency->thousand_separator }}';
        const decimalSeparator = '{{ $setup->currency->decimal_separator }}';

        easyNumberSeparator({
            selector: '.number-format',
            separator: thousandSeparator,
            decimalSeparator: decimalSeparator
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        @endif

        @if (session('fail'))
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: '{{ session('fail') }}',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
            });
        @endif
    });
</script>
@yield('additional_script')
