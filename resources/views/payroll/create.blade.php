@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_payroll')) }}
@endsection

@section('payroll-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("payroll.index") }}">{{ ucwords(str_replace('_', ' ', 'payroll')) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield("title")</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("payroll.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="employee">
                                    {{ ucwords(str_replace('_', ' ', 'employee')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="employee" name="employee_id" width="100%" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'employee')) }} :</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="from">
                                    {{ ucwords(str_replace('_', ' ', 'from')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="date" id="from" name="from" class="form-control" value="{{ date("Y-m-d") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="to">
                                    {{ ucwords(str_replace('_', ' ', 'to')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="date" id="to" name="to" class="form-control" value="{{ date("Y-m-d") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="month">
                                    {{ ucwords(str_replace('_', ' ', 'month')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="month" id="month" name="month" class="form-control" value="{{ date("m") }}" required>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="salary">
                                    {{ ucwords(str_replace('_', ' ', 'salary')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="salary" name="salary" class="form-control" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="attendance_credit">
                                    {{ ucwords(str_replace('_', ' ', 'attendance_credit')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="attendance_credit" name="attendance_credit" class="form-control" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="overtime">
                                    {{ ucwords(str_replace('_', ' ', 'overtime')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="overtime" name="overtime" class="form-control" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="overtime_credit">
                                    {{ ucwords(str_replace('_', ' ', 'overtime_credit')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="overtime_credit" name="overtime_credit" class="form-control" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="leave">
                                    {{ ucwords(str_replace('_', ' ', 'leave')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="leave" name="leave" class="form-control" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="leave_credit">
                                    {{ ucwords(str_replace('_', ' ', 'leave_credit')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="leave_credit" name="leave_credit" class="form-control" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="incentive">
                                    {{ ucwords(str_replace('_', ' ', 'incentive')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="incentive" name="incentive" class="form-control" value="" readonly>
                                </div>
                            </div>

                            @foreach($allowances as $allowance)
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="{{ ucwords(str_replace(' ', '_', $allowance->name)) }}">
                                    {{ ucwords(str_replace('_', ' ', $allowance->name)) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="{{ ucwords(str_replace(' ', '_', $allowance->name)) }}" name="{{ ucwords(str_replace(' ', '_', $allowance->name)) }}" class="form-control" value="" required>
                                </div>
                            </div>
                            @endforeach

                            @foreach($deductions as $deduction)
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="{{ ucwords(str_replace(' ', '_', $deduction->name)) }}">
                                    {{ ucwords(str_replace('_', ' ', $deduction->name)) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="{{ ucwords(str_replace(' ', '_', $deduction->name)) }}" name="{{ ucwords(str_replace(' ', '_', $deduction->name)) }}" class="form-control" value="" required>
                                </div>
                            </div>
                            @endforeach

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="net_salary">
                                    {{ ucwords(str_replace('_', ' ', 'net_salary')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="net_salary" name="net_salary" class="form-control" value="" readonly>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_script')
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize select2 elements
        $('#employee').select2({
            theme: 'bootstrap',
            placeholder: "Select an employee"
        });

        // Function to call API and update salary details
        function fetchSalaryData() {
            var employee_id = $('#employee').val();
            var from = $('#from').val();
            var to = $('#to').val();

            // Build the URL with query parameters
            var url = "{{ url('/api/count_salary') }}" + "/" + employee_id + "/" + from + "/" + to;

            $.ajax({
                url: url, // Use the URL variable here
                method: "GET",
                success: function(response) {
                    // Update the salary fields with the response data
                    $('#salary').val(response.salary);
                    $('#attendance_credit').val(response.attendance_credit);
                    $('#overtime').val(response.overtime);
                    $('#overtime_credit').val(response.overtime_credit);
                    $('#leave').val(response.leave);
                    $('#leave_credit').val(response.leave_credit);
                    $('#incentive').val(response.incentive);

                    // Recalculate net salary after updating the fields
                    calculateSalary();
                },
                error: function(xhr) {
                    console.error("Error fetching salary data:", xhr);
                    alert("Failed to calculate salary. Please try again.");
                }
            });
        }

        // Function to calculate net salary
        function calculateSalary() {
            console.log("calculateSalary");

            // Parse the input values
            var salary = parseFloat($('#salary').val()) || 0;
            var overtime = parseFloat($('#overtime').val()) || 0;
            var leave = parseFloat($('#leave').val()) || 0;
            var incentive = parseFloat($('#incentive').val()) || 0;

            // Calculate total allowances
            var totalAllowances = 0;
            @foreach($allowances as $allowance)
                totalAllowances += parseFloat($('#{{ ucwords(str_replace(' ', '_', $allowance->name)) }}').val()) || 0;
            @endforeach

            // Calculate total deductions
            var totalDeductions = 0;
            @foreach($deductions as $deduction)
                totalDeductions += parseFloat($('#{{ ucwords(str_replace(' ', '_', $deduction->name)) }}').val()) || 0;
            @endforeach

            // Calculate net salary
            var netSalary = salary + overtime + leave + incentive + totalAllowances - totalDeductions;

            // Update net_salary field
            $('#net_salary').val(netSalary.toFixed(0));
        }

        // Attach event listeners to fields for fetching salary data
        $('#employee, #from, #to').change(function() {
            fetchSalaryData();
        });

        // Recalculate salary when allowances or deductions change
        @foreach($allowances as $allowance)
            $('#{{ ucwords(str_replace(' ', '_', $allowance->name)) }}').on('input', function() {
                calculateSalary();
            });
        @endforeach

        @foreach($deductions as $deduction)
            $('#{{ ucwords(str_replace(' ', '_', $deduction->name)) }}').on('input', function() {
                calculateSalary();
            });
        @endforeach
    });

</script>
@endsection

