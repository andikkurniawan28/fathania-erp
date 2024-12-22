<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
      {{ ucwords(str_replace('_', ' ', $setup->app_name)) }} |
      {{ ucwords(str_replace(['.', '_', 'index'], [' ', ' ', ''], Route::currentRouteName())) }}</title>
  <link rel="icon" type="image/x-icon" href="{{ asset($setup->company_logo) }}">
  <link rel="stylesheet" href="{{ asset('inv/style.css') }}" type="text/css" media="all" />
</head>

<body>
  <div>
    <div class="py-4">
      <div class="px-14 py-6">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-full align-top">
                <div>
                  <img src="{{ asset($setup->company_logo) }}" class="h-12" />
                </div>
              </td>

              <td class="align-top">
                <div class="text-sm">
                  <table class="border-collapse border-spacing-0">
                    <tbody>
                      <tr>
                        <td class="border-r pr-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Date</p>
                            <p class="whitespace-nowrap font-bold text-main text-right">{{ date('d-m-Y', strtotime($invoice->created_at)) }}</p>
                          </div>
                        </td>
                        <td class="pl-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Invoice #</p>
                            <p class="whitespace-nowrap font-bold text-main text-right">{{ $invoice->id }}</p>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="bg-slate-100 px-14 py-6 text-sm">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-1/2 align-top">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">{{ $setup->company_name }}</p>
                  <p>Phone: {{ $setup->company_phone }}</p>
                  <p>Email: {{ $setup->company_email }}</p>
                  <p>Address: {{ $setup->company_address }}</p>
                  <p>{{ $setup->company_city }}</p>
                  <p>{{ $setup->company_country }}</p>
                </div>
              </td>
              {{-- Uncomment if you want to show customer/supplier details
              <td class="w-1/2 align-top text-right">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">Customer Company</p>
                  <p>Number: 123456789</p>
                  <p>VAT: 23456789</p>
                  <p>9552 Vandervort Spurs</p>
                  <p>Paradise, 43325</p>
                  <p>United States</p>
                </div>
              </td>
              --}}
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        {{-- <h6>Invoice Details</h6> --}}
        <div class="table-responsive">
          <table class="w-full border-collapse border-spacing-0">
            <thead>
              <tr>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main text-center">#</td>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main text-center">Material</td>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main text-center">Qty</td>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main text-center">Price<sub>({{ $setup->currency->symbol }})</sub></td>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main text-center">Discount<sub>({{ $setup->currency->symbol }})</sub></td>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main text-center">Total<sub>({{ $setup->currency->symbol }})</sub></td>
              </tr>
            </thead>
            <tbody>
              @foreach($invoice->invoice_detail as $detail)
                <tr>
                  <td class="border-b py-3 pl-3 text-center">{{ $detail->item_order }}</td>
                  <td class="border-b py-3 pl-3 text-center">{{ $detail->material->name }}</td>
                  <td class="border-b py-3 pl-3 text-center">{{ $detail->qty }}</td>
                  <td class="border-b py-3 pl-3 text-center">{{ number_format($detail->price, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
                  <td class="border-b py-3 pl-3 text-center">{{ number_format($detail->discount, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
                  <td class="border-b py-3 pl-3 text-center">{{ number_format($detail->total, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="5">Subtotal</th>
                <th>{{ number_format($invoice->invoice_detail->sum('total'), 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</th>
              </tr>
              <tr>
                <th colspan="5">Taxes</th>
                <th>{{ number_format($invoice->taxes, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</th>
              </tr>
              <tr>
                <th colspan="5">Freight</th>
                <th>{{ number_format($invoice->freight, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</th>
              </tr>
              <tr>
                <th colspan="5">Discount</th>
                <th>{{ number_format($invoice->discount, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</th>
              </tr>
              <tr>
                <th colspan="5">Grand Total</th>
                <th>{{ number_format($invoice->grand_total, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <br>

        {{-- <div class="table-responsive">
          <table class="w-full border-collapse border-spacing-0">
            <thead>
              <tr>
                <th>Freight<sub>({{ $setup->currency->symbol }})</sub></th>
                <th>Discount<sub>({{ $setup->currency->symbol }})</sub></th>
                <th>Grand Total<sub>({{ $setup->currency->symbol }})</sub></th>
                <th>Left<sub>({{ $setup->currency->symbol }})</sub></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ number_format($invoice->freight, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
                <td>{{ number_format($invoice->discount, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
                <td>{{ number_format($invoice->grand_total, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
                <td>{{ number_format($invoice->left, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <br> --}}
        <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
      </div>
    </div>
  </div>
</body>

</html>
