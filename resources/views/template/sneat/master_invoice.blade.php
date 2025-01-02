<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
            {{ ucwords(str_replace('_', ' ', $setup->app_name)) }} |
            {{ ucwords(str_replace(['.', '_', 'index'], [' ', ' ', ''], Route::currentRouteName())) }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <style>
            *,
            *::after,
            *::before{
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }

            :root{
                --blue-color: #0c2f54;
                --dark-color: #535b61;
                --white-color: #fff;
            }

            ul{
                list-style-type: none;
            }
            ul li{
                margin: 2px 0;
            }

            /* text colors */
            .text-dark{
                color: var(--dark-color);
            }
            .text-blue{
                color: var(--blue-color);
            }
            .text-end{
                text-align: right;
            }
            .text-center{
                text-align: center;
            }
            .text-start{
                text-align: left;
            }
            .text-bold{
                font-weight: 700;
            }
            /* hr line */
            .hr{
                height: 1px;
                background-color: rgba(0, 0, 0, 0.1);
            }
            /* border-bottom */
            .border-bottom{
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            body{
                font-family: 'Poppins', sans-serif;
                color: var(--dark-color);
                font-size: 14px;
            }
            .invoice-wrapper{
                min-height: 100vh;
                background-color: rgba(0, 0, 0, 0.1);
                padding-top: 20px;
                padding-bottom: 20px;
            }
            .invoice{
                max-width: 850px;
                margin-right: auto;
                margin-left: auto;
                background-color: var(--white-color);
                padding: 70px;
                border: 1px solid rgba(0, 0, 0, 0.2);
                border-radius: 5px;
                min-height: 920px;
            }
            .invoice-head-top-left img{
                width: 130px;
            }
            .invoice-head-top-right h3{
                font-weight: 500;
                font-size: 27px;
                color: var(--blue-color);
            }
            .invoice-head-middle, .invoice-head-bottom{
                padding: 16px 0;
            }
            .invoice-body{
                border: 1px solid rgba(0, 0, 0, 0.1);
                border-radius: 4px;
                overflow: hidden;
            }
            .invoice-body table{
                border-collapse: collapse;
                border-radius: 4px;
                width: 100%;
            }
            .invoice-body table td, .invoice-body table th{
                padding: 12px;
            }
            .invoice-body table tr{
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }
            .invoice-body table thead{
                background-color: rgba(0, 0, 0, 0.02);
            }
            .invoice-body-info-item{
                display: grid;
                grid-template-columns: 80% 20%;
            }
            .invoice-body-info-item .info-item-td{
                padding: 12px;
                background-color: rgba(0, 0, 0, 0.02);
            }
            .invoice-foot{
                padding: 30px 0;
            }
            .invoice-foot p{
                font-size: 12px;
            }
            .invoice-btns{
                margin-top: 20px;
                display: flex;
                justify-content: center;
            }
            .invoice-btn{
                padding: 3px 9px;
                color: var(--dark-color);
                font-family: inherit;
                border: 1px solid rgba(0, 0, 0, 0.1);
                cursor: pointer;
            }

            .invoice-head-top, .invoice-head-middle, .invoice-head-bottom{
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                padding-bottom: 10px;
            }

            @media screen and (max-width: 992px){
                .invoice{
                    padding: 40px;
                }
            }

            @media screen and (max-width: 576px){
                .invoice-head-top, .invoice-head-middle, .invoice-head-bottom{
                    grid-template-columns: repeat(1, 1fr);
                }
                .invoice-head-bottom-right{
                    margin-top: 12px;
                    margin-bottom: 12px;
                }
                .invoice *{
                    text-align: left;
                }
                .invoice{
                    padding: 28px;
                }
            }

            .overflow-view{
                overflow-x: scroll;
            }
            .invoice-body{
                min-width: 600px;
            }

            @media print{
                .print-area{
                    visibility: visible;
                    width: 100%;
                    position: absolute;
                    left: 0;
                    top: 0;
                    overflow: hidden;
                }

                .overflow-view{
                    overflow-x: hidden;
                }

                .invoice-btns{
                    display: none;
                }
            }
        </style>
        <div class = "invoice-wrapper" id = "print-area">
            <div class = "invoice">
                <div class = "invoice-container">
                    <div class = "invoice-head">
                        <div class = "invoice-head-top">
                            <div class = "invoice-head-top-left text-start">
                                <img src="{{ asset($setup->company_logo) }}">
                            </div>
                            <div class = "invoice-head-top-right text-end">
                                <h3>Invoice of {{ $invoice->invoice_category->name }}</h3>
                            </div>
                        </div>
                        <div class = "hr"></div>
                        <div class = "invoice-head-middle">
                            <div class = "invoice-head-middle-left text-start">
                                <p><span class = "text-bold">Date</span>: {{ date("d/m/Y", strtotime($invoice->created_at)) }}</p>
                            </div>
                            <div class = "invoice-head-middle-right text-end">
                                <p><spanf class = "text-bold">Invoice No : </span>{{ $invoice->id }}</p>
                            </div>
                        </div>
                        <div class = "hr"></div>
                        <div class = "invoice-head-bottom">
                            <div class = "invoice-head-bottom-left">
                                <ul>
                                    <li class = 'text-bold'>To:</li>
                                    <li>{{ $invoice->supplier->name ?? $invoice->customer->name }}</li>
                                    <li>{{ $invoice->supplier->address ?? $invoice->customer->address }}</li>
                                    <li>{{ $invoice->supplier->phone_number ?? $invoice->customer->phone_number }}</li>
                                </ul>
                            </div>
                            <div class = "invoice-head-bottom-right">
                                <ul class = "text-end">
                                    <li class = 'text-bold'>From:</li>
                                    <li>{{ $setup->company_name }}</li>
                                    <li>{{ $setup->company_address }}</li>
                                    <li>{{ $setup->company_phone }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class = "overflow-view">
                        <div class = "invoice-body">
                            <table>
                                <thead>
                                    <tr>
                                        <td class = "text-bold text-center">#</td>
                                        <td class = "text-bold text-center">Name</td>
                                        <td class = "text-bold text-center">Rate<sub>({{ $setup->currency->symbol }})</sub></td>
                                        <td class = "text-bold text-center">QTY</td>
                                        <td class = "text-bold text-center">Amount<sub>({{ $setup->currency->symbol }})</sub></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->invoice_detail as $detail)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $detail->material->name }}</td>
                                        <td class="text-center">{{ number_format($detail->price, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
                                        <td class="text-center">{{ $detail->qty }}</td>
                                        <td class="text-center">{{ number_format($detail->total, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th class = "text-bold text-center">Subtotal</th>
                                        <th class = "text-bold text-center">{{ number_format($invoice->subtotal, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th class = "text-bold text-center">Tax<sub>({{ $invoice->tax_rate->rate }}%)</sub></th>
                                        <th class = "text-bold text-center">{{ number_format($invoice->taxes, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th class = "text-bold text-center">Freight</th>
                                        <th class = "text-bold text-center">{{ number_format($invoice->freight, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th class = "text-bold text-center">Grand Total</th>
                                        <th class = "text-bold text-center">{{ number_format($invoice->grand_total, 2, $setup->currency->decimal_separator, $setup->currency->thousand_separator) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class = "invoice-foot text-center">
                        <p><span class = "text-bold text-center">NOTE:&nbsp;</span>This is computer generated receipt and does not require physical signature.</p>

                        <div class = "invoice-btns">
                            <button type = "button" class = "invoice-btn" onclick="printInvoice()">
                                <span>
                                    <i class="fa-solid fa-print"></i>
                                </span>
                                <span>Print</span>
                            </button>
                            <button type = "button" class = "invoice-btn">
                                <span>
                                    <i class="fa-solid fa-download"></i>
                                </span>
                                <span>Download</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function printInvoice(){
                window.print();
            }
        </script>
    </body>
</html>
