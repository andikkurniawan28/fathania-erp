@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'setup')) }}
@endsection

@section('setup-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                {{-- <li class="breadcrumb-item"><a href="{{ route("setup.index") }}">{{ ucwords(str_replace('_', ' ', 'setup')) }}</a></li> --}}
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
                        <form action="{{ route("setup.update", $setup->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method("PUT")
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="app_name">
                                    {{ ucwords(str_replace('_', ' ', 'app_name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="app_name" name="app_name" value="{{ $setup->app_name }}" required autofocus>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="company_name">
                                    {{ ucwords(str_replace('_', ' ', 'company_name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $setup->company_name }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="company_logo">
                                    {{ ucwords(str_replace('_', ' ', 'company_logo')) }}
                                </label>
                                <div class="col-sm-10">
                                    @if($setup->company_logo)
                                        <div class="mb-3">
                                            <img src="{{ asset($setup->company_logo) }}" alt="Company Logo" style="max-height: 100px; max-width: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="company_logo" name="company_logo" accept=".jpg, .jpeg, .png">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="retained_earning_id">
                                    {{ ucwords(str_replace('_', ' ', 'retained_earning_account')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="account" name="retained_earning_id" width="100%" required autofocus>
                                        <option disabled {{ is_null($setup->retained_earning_id) ? 'selected' : '' }}>
                                            Select a {{ ucwords(str_replace('_', ' ', 'account')) }} :
                                        </option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ $setup->retained_earning_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="daily_wage">
                                    {{ ucwords(str_replace('_', ' ', 'daily_wage')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="daily_wage" name="daily_wage" value="{{ $setup->daily_wage }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="hourly_wage">
                                    {{ ucwords(str_replace('_', ' ', 'hourly_wage')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="hourly_wage" name="hourly_wage" value="{{ $setup->hourly_wage }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="hourly_overtime">
                                    {{ ucwords(str_replace('_', ' ', 'hourly_overtime')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="hourly_overtime" name="hourly_overtime" value="{{ $setup->hourly_overtime }}">
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
        $('#account').select2({
            theme: 'bootstrap',
            placeholder: "Select a sub_account"
        });
    });
</script>
@endsection
