@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'show_payroll')) }}
@endsection

@section('payroll-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">{{ ucwords(str_replace('_', ' ', 'payroll')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                        <a href="{{ route('payroll.index') }}" class="btn btn-primary">Back to List</a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <h6>Employee Details</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $payroll->employee->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>ID</th>
                                            <td>{{ $payroll->employee->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Sub-Department</th>
                                            <td>{{ $payroll->employee->title->sub_department->name }} - {{ $payroll->employee->title->sub_department->department->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Title</th>
                                            <td>{{ $payroll->employee->title->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <br>
                            </div>

                            <div class="col-sm-6">
                                <h6>Payroll Period</h6>
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th>Year</th>
                                        <td>{{ $payroll->year }}</td>
                                    </tr>
                                    <tr>
                                        <th>Month</th>
                                        <td>{{ $payroll->month }}</td>
                                    </tr>
                                    <tr>
                                        <th>From</th>
                                        <td>{{ $payroll->from }}</td>
                                    </tr>
                                    <tr>
                                        <th>To</th>
                                        <td>{{ $payroll->to }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-sm-6">
                                <h6>Allowance</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Description</th>
                                            <th>Credit</th>
                                            <th>Basic</th>
                                            <th>Value</th>
                                        </tr>
                                        <tr>
                                            <td>Attendance</td>
                                            <td>{{ $payroll->attendance_credit }}</td>
                                            <td>{{ number_format($setup->daily_wage * $payroll->employee->title->salary_multiplier) }}</td>
                                            <td>{{ number_format($payroll->salary) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Overtime</td>
                                            <td>{{ $payroll->overtime_credit }}</td>
                                            <td>{{ number_format($setup->hourly_overtime) }}</td>
                                            <td>{{ number_format($payroll->overtime) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Leave</td>
                                            <td>{{ $payroll->leave_credit }}</td>
                                            <td>{{ number_format($setup->daily_wage * $payroll->employee->title->salary_multiplier) }}</td>
                                            <td>{{ number_format($payroll->leave) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Incentive</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>{{ number_format($payroll->incentive) }}</td>
                                        </tr>
                                        @php $totalAllowance = $payroll->salary + $payroll->overtime + $payroll->leave + $payroll->incentive; @endphp
                                        @foreach ($allowances as $allowance)
                                            @php
                                                $allowance_name = ucwords(str_replace(' ', '_', $allowance->name));
                                                $totalAllowance += $payroll->{$allowance_name};
                                            @endphp
                                            <tr>
                                                <td>{{ $allowance->name }}</td>
                                                <td>-</td>
                                                <td>{{ number_format($payroll->{$allowance_name}) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="3">Total Allowance</th>
                                            <th>{{ number_format($totalAllowance) }}</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <h6>Deduction</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Description</th>
                                            <th>Credit</th>
                                            <th>Value</th>
                                        </tr>
                                        @php $totalDeduction = 0; @endphp
                                        @foreach ($deductions as $deduction)
                                            @php
                                                $deduction_name = ucwords(str_replace(' ', '_', $deduction->name));
                                                $totalDeduction += $payroll->{$deduction_name};
                                            @endphp
                                            <tr>
                                                <td>{{ $deduction->name }}</td>
                                                <td>-</td>
                                                <td>{{ number_format($payroll->{$deduction_name}) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="2">Total Deduction</th>
                                            <th>{{ number_format($totalDeduction) }}</th>
                                        </tr>
                                    </table>
                                </div>
                                <br>
                            </div>

                            <div class="col-sm-6">
                                <h6>Net Salary</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Total Allowance</th>
                                            <td>{{ number_format($totalAllowance) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Deduction</th>
                                            <td>{{ number_format($totalDeduction) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Net Salary</th>
                                            {{-- <td>{{ number_format($totalAllowance - $totalDeduction) }}</td> --}}
                                            <td>{{ number_format($payroll->net_salary) }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <br>
                            </div>

                            <button class="btn btn-primary" onclick="window.print()">Print Payroll</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
