@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_checklog')) }}
@endsection

@section('checklog-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("checklog.index") }}">{{ ucwords(str_replace('_', ' ', 'checklog')) }}</a></li>
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
                        <form action="{{ route("checklog.store") }}" method="POST">
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
                                <label class="col-sm-2 col-form-label" for="time">
                                    {{ ucwords(str_replace('_', ' ', 'time')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="time" id="time" name="time" class="form-control" value="{{ date("H:i:s") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="today">
                                    {{ ucwords(str_replace('_', ' ', 'today')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="date" id="date" name="date" class="form-control" value="{{ date("Y-m-d") }}" required>
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
        });
    });
</script>
@endsection
