@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_attendance')) }}
@endsection

@section('attendance-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("attendance.index") }}">{{ ucwords(str_replace('_', ' ', 'attendance')) }}</a></li>
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
                        <form action="{{ route("attendance.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="date">
                                    {{ ucwords(str_replace('_', ' ', 'date')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="date" id="date" name="date" class="form-control" value="{{ date("Y-m-d") }}" required>
                                </div>
                            </div>

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
                                <label class="col-sm-2 col-form-label" for="shift">
                                    {{ ucwords(str_replace('_', ' ', 'shift')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="shift" name="shift_id" width="100%" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'shift')) }} :</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}"
                                                data-salary-multiplier="{{ $shift->salary_multiplier }}"
                                                data-start="{{ $shift->start }}"
                                                data-finish="{{ $shift->finish }}"
                                            >{{ $shift->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="check_in">
                                    {{ ucwords(str_replace('_', ' ', 'check_in')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="time" id="check_in" name="check_in" class="form-control" value="{{ date("H:i:s") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="check_out">
                                    {{ ucwords(str_replace('_', ' ', 'check_out')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="time" id="check_out" name="check_out" class="form-control" value="{{ date("H:i:s") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="early_check_in">
                                    {{ ucwords(str_replace('_', ' ', 'early_check_in')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="early_check_in" name="early_check_in" class="form-control" step="any" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="late_check_in">
                                    {{ ucwords(str_replace('_', ' ', 'late_check_in')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="late_check_in" name="late_check_in" class="form-control" step="any" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="early_check_out">
                                    {{ ucwords(str_replace('_', ' ', 'early_check_out')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="early_check_out" name="early_check_out" class="form-control" step="any" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="late_check_out">
                                    {{ ucwords(str_replace('_', ' ', 'late_check_out')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="late_check_out" name="late_check_out" class="form-control" step="any" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="credit">
                                    {{ ucwords(str_replace('_', ' ', 'credit')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="credit" name="credit" class="form-control" step="any" value="" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic_salary">
                                    {{ ucwords(str_replace('_', ' ', 'basic_salary')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="basic_salary" name="basic_salary" class="form-control" step="any" value="{{ $setup->daily_wage }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="net_salary">
                                    {{ ucwords(str_replace('_', ' ', 'net_salary')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" id="net_salary" name="net_salary" class="form-control" step="any" value="" readonly>
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

        $('#employee').select2({
            theme: 'bootstrap',
            placeholder: "Select a employee"
        }),
        $('#shift').select2({
            theme: 'bootstrap',
            placeholder: "Select a shift"
        });

        $('#shift, #check_in, #check_out').on('change', function() {
            calculateAttendanceValues();
        });

        function calculateAttendanceValues() {
        // Get the check_in and check_out values
        let checkIn = $('#check_in').val();
        let checkOut = $('#check_out').val();

        // Get shift details
        let shiftStart = $('#shift option:selected').data('start');
        let shiftFinish = $('#shift option:selected').data('finish');
        let shiftMultiplier = $('#shift option:selected').data('salary-multiplier') || 1;

        // Calculate early_check_in, late_check_in, early_check_out, late_check_out
        let earlyCheckIn = 0, lateCheckIn = 0, earlyCheckOut = 0, lateCheckOut = 0;

        if (checkIn && shiftStart) {
            let checkInTime = new Date(`1970-01-01T${checkIn}Z`);
            let shiftStartTime = new Date(`1970-01-01T${shiftStart}Z`);
            let diffInMinutes = (checkInTime - shiftStartTime) / (1000 * 60); // Convert ms to minutes

            if (diffInMinutes < 0) {
                earlyCheckIn = Math.abs(diffInMinutes);
            } else {
                lateCheckIn = diffInMinutes;
            }
        }

        if (checkOut && shiftFinish) {
            let checkOutTime = new Date(`1970-01-01T${checkOut}Z`);
            let shiftFinishTime = new Date(`1970-01-01T${shiftFinish}Z`);
            let diffInMinutes = (checkOutTime - shiftFinishTime) / (1000 * 60); // Convert ms to minutes

            if (diffInMinutes < 0) {
                earlyCheckOut = Math.abs(diffInMinutes);
            } else {
                lateCheckOut = diffInMinutes;
            }
        }

        // Calculate credit and net_salary
        let credit = 0, netSalary = 0;
        let realCredit = 0; // Initialize realCredit to avoid undefined errors

        if (checkIn && checkOut) {
            let checkInTime = new Date(`1970-01-01T${checkIn}Z`);
            let checkOutTime = new Date(`1970-01-01T${checkOut}Z`);
            let interval = (checkOutTime - checkInTime) / (1000 * 60 * 60); // Convert ms to hours

            credit = interval / 8;
            realCredit = credit > 1 ? 1 : credit;

            let basicSalary = parseFloat($('#basic_salary').val()) || 0;
            netSalary = basicSalary * realCredit * shiftMultiplier;
        }

        // Update the form fields
        $('#early_check_in').val(earlyCheckIn.toFixed(2));
        $('#late_check_in').val(lateCheckIn.toFixed(2));
        $('#early_check_out').val(earlyCheckOut.toFixed(2));
        $('#late_check_out').val(lateCheckOut.toFixed(2));
        $('#credit').val(realCredit.toFixed(2));
        $('#net_salary').val(netSalary.toFixed(0));
    }

    // Trigger initial calculation
    calculateAttendanceValues();

});

</script>
@endsection
