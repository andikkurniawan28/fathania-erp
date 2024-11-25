@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_opportunity')) }}
@endsection

@section('opportunity-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('opportunity.index') }}">{{ ucwords(str_replace('_', ' ', 'opportunity')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('opportunity.update', $opportunity->id) }}" method="POST">
                            @csrf @method('PUT')

                            @if($opportunity->prospect_id != null)
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="prospect">
                                    {{ ucwords(str_replace('_', ' ', 'prospect')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="prospect" name="prospect_id" width="100%" required>
                                        <option disabled>Select a {{ ucwords(str_replace('_', ' ', 'prospect')) }} :</option>
                                        @foreach ($prospects as $prospect)
                                            <option value="{{ $prospect->id }}" {{ $opportunity->prospect_id == $prospect->id ? 'selected' : '' }}>
                                                {{ $prospect->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            @if($opportunity->customer_id != null)
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="customer">
                                    {{ ucwords(str_replace('_', ' ', 'customer')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="customer" name="customer_id" width="100%" required>
                                        <option disabled>Select a {{ ucwords(str_replace('_', ' ', 'customer')) }} :</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $opportunity->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="title">
                                    {{ ucwords(str_replace('_', ' ', 'title')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $opportunity->title) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="opportunity_status">
                                    {{ ucwords(str_replace('_', ' ', 'opportunity_status')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="opportunity_status" name="opportunity_status_id" width="100%" required>
                                        <option disabled>Select an {{ ucwords(str_replace('_', ' ', 'opportunity_status')) }} :</option>
                                        @foreach ($opportunity_statuses as $opportunity_status)
                                            <option value="{{ $opportunity_status->id }}" {{ $opportunity->opportunity_status_id == $opportunity_status->id ? 'selected' : '' }}>
                                                {{ $opportunity_status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="value">
                                    {{ ucwords(str_replace('_', ' ', 'value')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="value" name="value" value="{{ old('value', $opportunity->value) }}" required>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
        $('#prospect').select2({
            theme: 'bootstrap',
            placeholder: "Select a prospect"
        });
        $('#customer').select2({
            theme: 'bootstrap',
            placeholder: "Select a prospect"
        });
        $('#opportunity_status').select2({
            theme: 'bootstrap',
            placeholder: "Select an opportunity_status"
        });
        $('#prospect').on('change', function() {
            if ($(this).val()) {
                $('#customer').prop('disabled', true);
            } else {
                $('#customer').prop('disabled', false);
            }
        });
        $('#customer').on('change', function() {
            if ($(this).val()) {
                $('#prospect').prop('disabled', true);
            } else {
                $('#prospect').prop('disabled', false);
            }
        });
    });
</script>
@endsection
