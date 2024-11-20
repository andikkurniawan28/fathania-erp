@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'shift')) }}
@endsection

@section('shift-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('shift.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-hover table-bordered" id="example" width="100%">
                        <thead>
                            <tr>
                                <th>{{ strtoupper(str_replace('_', ' ', 'id')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'name')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'start')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'finish')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'start_break')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'finish_break')) }}</th>
                                {{-- <th>{{ ucwords(str_replace('_', ' ', 'delta_day_of_start')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'delta_day_of_finish')) }}</th> --}}
                                <th>{{ ucwords(str_replace('_', ' ', 'salary_multiplier')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shifts as $shift)
                                <tr>
                                    <td>{{ $shift->id }}</td>
                                    <td>{{ $shift->name }}</td>
                                    <td>{{ $shift->start }}</td>
                                    <td>{{ $shift->finish }}</td>
                                    <td>{{ $shift->start_break ?? "-" }}</td>
                                    <td>{{ $shift->finish_break ?? "-" }}</td>
                                    {{-- <td>{{ $shift->delta_day_of_start }}</td>
                                    <td>{{ $shift->delta_day_of_finish }}</td> --}}
                                    <td>{{ number_format($shift->salary_multiplier, 2) }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="manage">
                                            <a href="{{ route('shift.edit', $shift->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $shift->id }}" data-name="{{ $shift->name }}">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const shift_id = this.getAttribute('data-id');
                    const shift_name = this.getAttribute('data-name');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You won\'t be able to revert this!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.setAttribute('method', 'POST');
                            form.setAttribute('action',
                                `{{ route('shift.destroy', ':id') }}`.replace(
                                    ':id', shift_id));
                            const csrfToken = document.getElementsByName("_token")[0].value;

                            const hiddenMethod = document.createElement('input');
                            hiddenMethod.setAttribute('type', 'hidden');
                            hiddenMethod.setAttribute('name', '_method');
                            hiddenMethod.setAttribute('value', 'DELETE');

                            const name = document.createElement('input');
                            name.setAttribute('type', 'hidden');
                            name.setAttribute('name', 'name');
                            name.setAttribute('value', shift_name);

                            const csrfTokenInput = document.createElement('input');
                            csrfTokenInput.setAttribute('type', 'hidden');
                            csrfTokenInput.setAttribute('name', '_token');
                            csrfTokenInput.setAttribute('value', csrfToken);

                            form.appendChild(hiddenMethod);
                            form.appendChild(name);
                            form.appendChild(csrfTokenInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
