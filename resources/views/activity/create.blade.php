@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_activity')) }}
@endsection

@section('activity-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("activity.index") }}">{{ ucwords(str_replace('_', ' ', 'activity')) }}</a></li>
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
                        <form action="{{ route("activity.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="prospect">
                                    {{ ucwords(str_replace('_', ' ', 'prospect')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="prospect" name="prospect_id" width="100%">
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'prospect')) }} :</option>
                                        @foreach ($prospects as $prospect)
                                            <option value="{{ $prospect->id }}">{{ $prospect->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="customer">
                                    {{ ucwords(str_replace('_', ' ', 'customer')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="customer" name="customer_id" width="100%">
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'customer')) }} :</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="subject">
                                    {{ ucwords(str_replace('_', ' ', 'subject')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="subject" name="subject" value="{{ old("subject") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="type">
                                    {{ ucwords(str_replace('_', ' ', 'type')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="type" name="type" value="{{ old("type") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="description">
                                    {{ ucwords(str_replace('_', ' ', 'description')) }}
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description">{{ old("description") }}</textarea>
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
        $('#prospect').select2({
            theme: 'bootstrap',
            placeholder: "Select a prospect"
        });
        $('#customer').select2({
            theme: 'bootstrap',
            placeholder: "Select a customer"
        });
    });
</script>
@endsection
