@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_employee')) }}
@endsection

@section('employee-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("employee.index") }}">{{ ucwords(str_replace('_', ' ', 'employee')) }}</a></li>
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
                        <form action="{{ route("employee.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id">
                                    {{ ucwords(str_replace('_', ' ', 'id')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ old('id') }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="address">
                                    {{ ucwords(str_replace('_', ' ', 'address')) }}
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="address" id="address">{{ old("address") }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="place_of_birth">
                                    {{ ucwords(str_replace('_', ' ', 'place_of_birth')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="{{ old("place_of_birth") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="birthday">
                                    {{ ucwords(str_replace('_', ' ', 'birthday')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="birthday" name="birthday" value="{{ old("birthday") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="title">
                                    {{ ucwords(str_replace('_', ' ', 'title')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="title" name="title_id" width="100%" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'title')) }} :</option>
                                        @foreach ($titles as $title)
                                            <option value="{{ $title->id }}">{{ $title->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="employee_status">
                                    {{ ucwords(str_replace('_', ' ', 'employee_status')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="employee_status" name="employee_status_id" width="100%" required>
                                        <option disabled selected>Select an {{ ucwords(str_replace('_', ' ', 'employee_status')) }} :</option>
                                        @foreach ($employee_statuses as $employee_status)
                                            <option value="{{ $employee_status->id }}">{{ $employee_status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="education">
                                    {{ ucwords(str_replace('_', ' ', 'education')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="education" name="education_id" width="100%" required>
                                        <option disabled selected>Select an {{ ucwords(str_replace('_', ' ', 'education')) }} :</option>
                                        @foreach ($educations as $education)
                                            <option value="{{ $education->id }}">{{ $education->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="campus">
                                    {{ ucwords(str_replace('_', ' ', 'campus')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="campus" name="campus_id" width="100%" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'campus')) }} :</option>
                                        @foreach ($campuses as $campus)
                                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="major">
                                    {{ ucwords(str_replace('_', ' ', 'major')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="major" name="major_id" width="100%" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'major')) }} :</option>
                                        @foreach ($majors as $major)
                                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="religion">
                                    {{ ucwords(str_replace('_', ' ', 'religion')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="religion" name="religion_id" width="100%" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'religion')) }} :</option>
                                        @foreach ($religions as $religion)
                                            <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="marital_status">
                                    {{ ucwords(str_replace('_', ' ', 'marital_status')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="marital_status" name="marital_status_id" width="100%" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'marital_status')) }} :</option>
                                        @foreach ($marital_statuses as $marital_status)
                                            <option value="{{ $marital_status->id }}">{{ $marital_status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="bank">
                                    {{ ucwords(str_replace('_', ' ', 'bank')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="bank" name="bank_id" width="100%" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'bank')) }} :</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="leave">
                                    {{ ucwords(str_replace('_', ' ', 'leave')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="leave" name="leave" value="{{ old("leave") }}" required>
                                </div>
                            </div>

                            @foreach($employee_identities as $employee_identity)
                            @php $column_name = str_replace(' ', '_', $employee_identity->name); @endphp
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="{{ $column_name }}">{{ ucwords(str_replace('_', ' ', $employee_identity->name)) }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="{{ $column_name }}" name="{{ $column_name }}" value="">
                                </div>
                            </div>
                            @endforeach

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label skill-section">{{ ucwords(str_replace('_', ' ', 'skills')) }}</label>
                                <div class="col-sm-10 skill-section">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select_all">
                                        <label class="form-check-label" for="select_all">
                                            Select All
                                        </label>
                                    </div>
                                    <hr>
                                    <!-- Checkbox for each skill -->
                                    @foreach ($skills as $skill)
                                        <div class="form-check">
                                            <input class="form-check-input skill-checkbox" type="checkbox"
                                                name="skill_ids[]" value="{{ $skill->id }}"
                                                id="skill_{{ $skill->id }}">
                                            <label class="form-check-label" for="skill_{{ $skill->id }}">
                                                {{ $skill->name }}
                                            </label>
                                        </div>
                                    @endforeach
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
        $('#title').select2({
            theme: 'bootstrap',
            placeholder: "Select a title"
        });
        $('#employee_status').select2({
            theme: 'bootstrap',
            placeholder: "Select an employee_status"
        });
        $('#education').select2({
            theme: 'bootstrap',
            placeholder: "Select an education"
        });
        $('#campus').select2({
            theme: 'bootstrap',
            placeholder: "Select a campus"
        });
        $('#major').select2({
            theme: 'bootstrap',
            placeholder: "Select a major"
        });
        $('#religion').select2({
            theme: 'bootstrap',
            placeholder: "Select a religion"
        });
        $('#marital_status').select2({
            theme: 'bootstrap',
            placeholder: "Select a marital_status"
        });
        $('#bank').select2({
            theme: 'bootstrap',
            placeholder: "Select a bank"
        });
    });
</script>
@endsection
