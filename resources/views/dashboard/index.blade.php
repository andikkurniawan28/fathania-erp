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
                        <h5 class="card-title">Orders Processed Over Time</h5>
                        <canvas id="ordersProcessedChart"></canvas>
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

            <!-- Customer Satisfaction Chart -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Customer Satisfaction Over Time</h5>
                        <canvas id="customerSatisfactionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Training Hours Chart -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Training Hours Per Month</h5>
                        <canvas id="trainingHoursChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Finance Section -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue</h5>
                        <p class="card-text">{{ number_format(15000000) }} IDR</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenses</h5>
                        <p class="card-text">{{ number_format(8000000) }} IDR</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profit/Loss</h5>
                        <p class="card-text">{{ number_format(7000000) }} IDR</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Accounts Payable</h5>
                        <p class="card-text">{{ number_format(2000000) }} IDR</p>
                    </div>
                </div>
            </div>

            <!-- Supply Chain Section -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Inventory</h5>
                        <p class="card-text">{{ number_format(3000) }} Units</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Orders Processed</h5>
                        <p class="card-text">{{ number_format(120) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Shipments</h5>
                        <p class="card-text">{{ number_format(30) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Suppliers</h5>
                        <p class="card-text">{{ number_format(25) }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Relationship Section -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">New Customers</h5>
                        <p class="card-text">{{ number_format(50) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Customers</h5>
                        <p class="card-text">{{ number_format(500) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Customer Satisfaction</h5>
                        <p class="card-text">85%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tickets Resolved</h5>
                        <p class="card-text">{{ number_format(75) }}</p>
                    </div>
                </div>
            </div>

            <!-- Human Resource Section -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Employees</h5>
                        <p class="card-text">{{ number_format(120) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">New Hires</h5>
                        <p class="card-text">{{ number_format(5) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Employee Turnover</h5>
                        <p class="card-text">3%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Training Hours</h5>
                        <p class="card-text">{{ number_format(300) }}</p>
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
        const ctx4 = document.getElementById('ordersProcessedChart').getContext('2d');
        const ordersProcessedChart = new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Orders Processed',
                    data: [15, 20, 30, 25, 40, 35, 50],
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
                    data: [15000000, 8000000],
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

        // Customer Satisfaction Chart
        const ctx2 = document.getElementById('customerSatisfactionChart').getContext('2d');
        const customerSatisfactionChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Customer Satisfaction (%)',
                    data: [80, 85, 90, 88, 92, 95, 93],
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
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

        // Training Hours Chart
        const ctx3 = document.getElementById('trainingHoursChart').getContext('2d');
        const trainingHoursChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Training Hours',
                    data: [30, 45, 40, 50, 60, 55, 70],
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                    borderColor: 'rgba(255, 206, 86, 1)',
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
