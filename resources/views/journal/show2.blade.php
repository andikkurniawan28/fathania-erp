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
                            <p class="whitespace-nowrap font-bold text-main text-right">{{ date('d-m-Y', strtotime($journal->created_at)) }}</p>
                          </div>
                        </td>
                        <td class="pl-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Journal #</p>
                            <p class="whitespace-nowrap font-bold text-main text-right">{{ $journal->id }}</p>
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
              {{-- <td class="w-1/2 align-top text-right">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">Customer Company</p>
                  <p>Number: 123456789</p>
                  <p>VAT: 23456789</p>
                  <p>9552 Vandervort Spurs</p>
                  <p>Paradise, 43325</p>
                  <p>United States</p>
                </div>
              </td> --}}
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        <table class="w-full border-collapse border-spacing-0">
          <thead>
            <tr>
              <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main text-center">#</td>
              <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main text-center">Timestamp</td>
              <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main text-center">Account ID</td>
              <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main text-center">Account Name</td>
              <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main text-center">Description</td>
              <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main text-center">Debit<sub>({{ $setup->currency->symbol }})</sub></td>
              <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main text-center">Credit<sub>({{ $setup->currency->symbol }})</sub></td>
            </tr>
          </thead>
          <tbody>
            @foreach($journal->journal_detail as $detail)
                <tr>
                    <td class="border-b py-3 pl-3 text-center">{{ $loop->iteration }}</td>
                    <td class="border-b py-3 pl-3 text-center">{{ $detail->created_at }}</td>
                    <td class="border-b py-3 pl-3 text-center">{{ $detail->account->id }}</td>
                    <td class="border-b py-3 pl-3 text-center">{{ $detail->account->name }}</td>
                    <td class="border-b py-3 pl-3 text-center">{{ $detail->description }}</td>
                    <td class="border-b py-3 pl-3 text-center">
                        {{ $detail->debit != 0 && $detail->debit !== null
                            ? number_format($detail->debit, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator)
                            : '' }}
                    </td>
                    <td class="border-b py-3 pl-3 text-center">
                        {{ $detail->credit != 0 && $detail->credit !== null
                            ? number_format($detail->credit, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator)
                            : '' }}
                    </td>
                </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
                <td colspan="4" class="border-b-2 border-main pb-3 pl-1 font-bold text-main text-right"></td>
                <td class="border-b-2 border-main pb-3 pl-1 font-bold text-main text-center"><br>TOTAL</td>
                <td class="border-b-2 border-main pb-3 pl-1 font-bold text-main text-center"><br>{{ number_format($journal->journal_detail->sum('debit'), 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
                <td class="border-b-2 border-main pb-3 pl-1 font-bold text-main text-center"><br>{{ number_format($journal->journal_detail->sum('credit'), 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
          </tfoot>
        </table>
      </div>

      {{-- <div class="px-14 text-sm text-neutral-700">
        <p class="text-main font-bold">PAYMENT DETAILS</p>
        <p>Banks of Banks</p>
        <p>Bank/Sort Code: 1234567</p>
        <p>Account Number: 123456678</p>
        <p>Payment Reference: BRA-00335</p>
      </div> --}}

      <div class="px-14 py-10 text-sm text-neutral-700">
        <p class="text-main font-bold">Notes</p>
        <p class="italic">Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries
          for previewing layouts and visual mockups.</p>
        </dvi>

        {{-- <footer class="fixed bottom-0 left-0 bg-slate-100 w-full text-neutral-600 text-center text-xs py-3">
          Supplier Company
          <span class="text-slate-300 px-2">|</span>
          info@company.com
          <span class="text-slate-300 px-2">|</span>
          +1-202-555-0106
        </footer> --}}
      </div>
    </div>
</body>

</html>
