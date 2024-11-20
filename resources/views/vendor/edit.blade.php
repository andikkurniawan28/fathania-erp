@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_vendor')) }}
@endsection

@section('vendor-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">{{ ucwords(str_replace('_', ' ', 'vendor')) }}</a></li>
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
                        <form action="{{ route('vendor.update', $vendor->id) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="business">
                                    {{ ucwords(str_replace('_', ' ', 'business')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="business" name="business_id" width="100%" required autofocus>
                                        <option disabled {{ is_null($vendor->business_id) ? 'selected' : '' }}>
                                            Select a {{ ucwords(str_replace('_', ' ', 'business')) }} :
                                        </option>
                                        @foreach($businesses as $business)
                                            <option value="{{ $business->id }}"
                                                {{ $vendor->business_id == $business->id ? 'selected' : '' }}>
                                                {{ $business->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $vendor->name }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="phone_number">
                                    {{ ucwords(str_replace('_', ' ', 'phone_number')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $vendor->phone_number }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="address">
                                    {{ ucwords(str_replace('_', ' ', 'address')) }}
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="address" required autofocus>{{ $vendor->address }}</textarea>
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
        $('#business').select2({
            theme: 'bootstrap',
            placeholder: "Select a business"
        });
    });
</script>
@endsection
