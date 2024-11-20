@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'show_invoice')) }}
@endsection

@section('invoice-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">{{ ucwords(str_replace('_', ' ', 'invoice')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                        <a href="{{ route('invoice.index') }}" class="btn btn-primary">Back to List</a>
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
                                                <th>Category</th>
                                                <th>Customer/Supplier</th>
                                                <th>Date</th>
                                                <th>Timestamp</th>
                                                <th>Warehouse</th>
                                                <th>Admin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $invoice->id }}</td>
                                                <td>{{ $invoice->invoice_category->name }}</td>
                                                <td>{{ $invoice->supplier->name ?? $invoice->customer->name }}</td> <!-- Assuming 'party' refers to customer/supplier -->
                                                <td>{{ $invoice->created_at->format('d M Y') }}</td>
                                                <td>{{ $invoice->created_at }}</td>
                                                <td>{{ $invoice->warehouse->name }}</td>
                                                <td>{{ $invoice->user->name }}</td>
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
                                                <th>Discount</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice->invoice_detail as $detail)
                                                <tr>
                                                    <td>{{ $detail->item_order }}</td>
                                                    <td>{{ $detail->material->name }}</td> <!-- Assuming 'material' refers to a product/service -->
                                                    <td>{{ $detail->qty }}</td>
                                                    <td>{{ number_format($detail->price, 0) }}</td>
                                                    <td>{{ number_format($detail->discount, 0) }}</td>
                                                    <td>{{ number_format($detail->total, 0) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5">Subtotal</th>
                                                <th>{{ number_format($invoice->invoice_detail->sum('total'), 0) }}</th>
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
                                                <th>Subtotal</th>
                                                <th>Taxes</th>
                                                <th>Freight</th>
                                                <th>Discount</th>
                                                <th>Grand Total</th>
                                                <th>Left</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ number_format($invoice->subtotal, 0) }}</td>
                                                <td>{{ number_format($invoice->taxes, 0) }}</td>
                                                <td>{{ number_format($invoice->freight, 0) }}</td>
                                                <td>{{ number_format($invoice->discount, 0) }}</td>
                                                <td>{{ number_format($invoice->grand_total, 0) }}</td>
                                                <td>{{ number_format($invoice->left, 0) }}</td>
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
