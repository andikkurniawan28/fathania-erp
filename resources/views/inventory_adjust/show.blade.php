@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'show_inventory_adjust')) }}
@endsection

@section('inventory_adjust-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('inventory_adjust.index') }}">{{ ucwords(str_replace('_', ' ', 'inventory_adjust')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                        <a href="{{ route('inventory_adjust.index') }}" class="btn btn-primary">Back to List</a>
                    </div>
                    <div class="card-body">
                        <h6>Invoice Details</h6>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Adjust</th>
                                                <th>Date</th>
                                                <th>Timestamp</th>
                                                <th>Warehouse</th>
                                                <th>Admin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $inventory_adjust->id }}</td>
                                                <td>{{ $inventory_adjust->stock_adjust->name }}</td>
                                                <td>{{ $inventory_adjust->created_at->format('d M Y') }}</td>
                                                <td>{{ $inventory_adjust->created_at }}</td>
                                                <td>{{ $inventory_adjust->warehouse->name }}</td>
                                                <td>{{ $inventory_adjust->user->name }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <br>

                                <h6>Invoice Details</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Material</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inventory_adjust->inventory_adjust_entry as $detail)
                                                <tr>
                                                    <td>{{ $detail->item_order }}</td>
                                                    <td>{{ $detail->material->name }}</td> <!-- Assuming 'material' refers to a product/service -->
                                                    <td>{{ $detail->qty }}</td>
                                                    <td>{{ number_format($detail->price, 0) }}</td>
                                                    <td>{{ number_format($detail->total, 0) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4">Subtotal</th>
                                                <th>{{ number_format($inventory_adjust->inventory_adjust_entry->sum('total'), 0) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <br>

                                <h6>Invoice Summary</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Grand Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ number_format($inventory_adjust->grand_total, 0) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <br>
                                <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
