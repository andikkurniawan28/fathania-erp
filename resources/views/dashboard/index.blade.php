@extends('template.sneat.master')

@section('dashboard-active')
    {{ 'active' }}
@endsection

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4>{{ ucwords(str_replace(' ', '_', 'dashboard')) }}</h4>

        <div class="row">
            <!-- Orders Processed Chart -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sales Monthly</h5>
                        <canvas id="salesMonthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Finance Chart -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Revenue vs Expenses</h5>
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Receivable from Customer</h5>
                        <p class="card-text">{{ number_format($data['receivableVsPayable']['receivable']) }} IDR</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payable from Supplier</h5>
                        <p class="card-text">{{ number_format($data['receivableVsPayable']['payable']) }} IDR</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Revenue This Month</h5>
                        <p class="card-text">{{ number_format($data['revenueVsExpenses']['revenue']) }} IDR</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Expenses This Month</h5>
                        <p class="card-text">{{ number_format($data['revenueVsExpenses']['expenses']) }} IDR</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Material Loss This Month</h5>
                        <p class="card-text">{{ number_format($data['totalMaterialLoss']) }} IDR</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Supplier</h5>
                        <p class="card-text">{{ number_format($data['thirdParty']['suppliers']) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Customer</h5>
                        <p class="card-text">{{ number_format($data['thirdParty']['customers']) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Vendor</h5>
                        <p class="card-text">{{ number_format($data['thirdParty']['vendors']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection

@section('additional_script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Orders Processed Chart
        const ctx4 = document.getElementById('salesMonthlyChart').getContext('2d');
        const salesMonthlyData = @json($data['salesMonthly']); // Data dari controller
        const salesMonthlyChart = new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Orders Processed',
                    data: salesMonthlyData,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Finance Chart
        const ctx1 = document.getElementById('financeChart').getContext('2d');
        const financeChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Revenue', 'Expenses'],
                datasets: [{
                    label: 'Amount (IDR)',
                    data: [{{ $data['revenueVsExpenses']['revenue'] }}, {{ $data['revenueVsExpenses']['expenses'] }}],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
