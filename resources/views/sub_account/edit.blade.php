@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_sub_account')) }}
@endsection

@section('sub_account-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("sub_account.index") }}">{{ ucwords(str_replace('_', ' ', 'sub_account')) }}</a></li>
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
                        <form action="{{ route("sub_account.update", $sub_account->id) }}" method="POST">
                            @csrf @method("PUT")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id">
                                    {{ ucwords(str_replace('_', ' ', 'id')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ old("id", $sub_account->id) }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name", $sub_account->name) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="main_account">
                                    {{ ucwords(str_replace('_', ' ', 'main_account')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="main_account" name="main_account_id" width="100%" required>
                                        <option disabled>Select a {{ ucwords(str_replace('_', ' ', 'main_account')) }} :</option>
                                        @foreach ($main_accounts as $main_account)
                                            <option value="{{ $main_account->id }}" {{ $sub_account->main_account_id == $main_account->id ? 'selected' : '' }}>
                                                {{ $main_account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Save</button>
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
        $('#main_account').select2({
            theme: 'bootstrap',
            placeholder: "Select a main_account"
        })
    });
</script>
@endsection
