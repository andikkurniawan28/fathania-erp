@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_material')) }}
@endsection

@section('material-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("material.index") }}">{{ ucwords(str_replace('_', ' ', 'material')) }}</a></li>
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
                        <form action="{{ route("material.update", $material->id) }}" method="POST">
                            @csrf @method("PUT")

                            {{-- <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id">
                                    {{ ucwords(str_replace('_', ' ', 'id')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ old("id", $material->id) }}" required autofocus>
                                </div>
                            </div> --}}

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name", $material->name) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="material_sub_category">
                                    {{ ucwords(str_replace('_', ' ', 'material_sub_category')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="material_sub_category" name="material_sub_category_id" width="100%" required>
                                        <option disabled>Select a {{ ucwords(str_replace('_', ' ', 'material_sub_category')) }} :</option>
                                        @foreach ($material_sub_categories as $material_sub_category)
                                            <option value="{{ $material_sub_category->id }}" {{ $material->material_sub_category_id == $material_sub_category->id ? 'selected' : '' }}>
                                                {{ $material_sub_category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="unit">
                                    {{ ucwords(str_replace('_', ' ', 'unit')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="unit" name="unit_id" width="100%" required>
                                        <option disabled>Select a {{ ucwords(str_replace('_', ' ', 'unit')) }} :</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" {{ $material->unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="buy_price">
                                    {{ ucwords(str_replace('_', ' ', 'buy_price')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" step="any" class="form-control number-format" id="buy_price" name="buy_price" value="{{ old("buy_price", $material->buy_price) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="sell_price">
                                    {{ ucwords(str_replace('_', ' ', 'sell_price')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" step="any" class="form-control number-format" id="sell_price" name="sell_price" value="{{ old("sell_price", $material->sell_price) }}" required>
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
        $('#material_sub_category').select2({
            theme: 'bootstrap',
            placeholder: "Select a material_sub_category"
        }),
        $('#unit').select2({
            theme: 'bootstrap',
            placeholder: "Select a unit"
        });
    });
</script>
@endsection
