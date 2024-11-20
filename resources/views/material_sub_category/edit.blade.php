@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_material_sub_category')) }}
@endsection

@section('material_sub_category-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('material_sub_category.index') }}">{{ ucwords(str_replace('_', ' ', 'material_sub_category')) }}</a></li>
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
                        <form action="{{ route('material_sub_category.update', $material_sub_category->id) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="material_category">
                                    {{ ucwords(str_replace('_', ' ', 'material_category')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="material_category" name="material_category_id" width="100%" required>
                                        <option disabled>Select a {{ ucwords(str_replace('_', ' ', 'material_category')) }} :</option>
                                        @foreach ($material_categories as $material_category)
                                            <option value="{{ $material_category->id }}" {{ $material_sub_category->material_category_id == $material_category->id ? 'selected' : '' }}>
                                                {{ $material_category->name }}
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
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $material_sub_category->name) }}" required autofocus>
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
        $('#material_category').select2({
            theme: 'bootstrap',
            placeholder: "Select a material_category"
        });
    });
</script>
@endsection
