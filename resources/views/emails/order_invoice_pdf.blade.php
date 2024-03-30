<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
    type="text/css" />
</head>
<body>
    <style>
        .row.invoice-wrapper.mb-5 {
            height: 100vh;
            position: relative;
        }

        .col-12.invoice_page {
            position: absolute;
            bottom: 5vh;
        }

        table.invoice_table tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-width: 1px !important;
            padding: 8px;
        }

        table.amount_section tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            padding: 2px;
        }

        table.invoice_table th,
        table.invoice_table td,
        table.amount_section th {
            font-weight: 500 !important;
            font-size: 14px;
        }

        .card.invoice-page {
            /* position: relative; */
            height: 100%;
        }

        td.in_word {
            text-align: left;
        }

        td.des {
            text-align: left !important;
        }

        td.qty {
            text-align: right !important;
        }

        tr.custom-border>td:first-child {
            border-color: transparent;
        }

        tr.custom-border>td:nth-child(2) {
            border-left-color: transparent;
            border-bottom-color: transparent;
        }
    </style>

    <div class="page-content">

        <div class="row invoice-wrapper mb-5">
            <div class="col-12">
                <div class="card invoice-page">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 pt-3">
                                <div class="invoice-title">
                                </div>
                                {{-- @php
                                    $payments = App\Models\Payment::where('invoice_id', $invoice->id)->first();
                                @endphp --}}
                                @php
                                    $invoice = App\Models\Invoice::with('invoice_details')->findOrFail($id);
                                    $invoiceDetails = App\Models\InvoiceDetail::where('invoice_id', $invoice->id)->get();
                                    // dd($invoiceDetails);
                                @endphp
                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>To</strong>
                                            <br>
                                            <h5 class="mb-0">{{ $invoice->payment->customer->name }}</h5>
                                            {{ $invoice->payment->customer->phone }}<br>
                                            {{ $invoice->payment->customer->address }}<br>
                                        </address>

                                        <br>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div>
                                    <div class="py-2 d-flex justify-content-between">
                                        <h3 class="font-size-16"><strong>Invoice No: {{ $invoice->invoice_no }}</strong>
                                        </h3>
                                        <h3 class="font-size-16"><strong>Date:
                                                {{ date('d-m-Y', strtotime($invoice->date)) }}</strong></h3>
                                    </div>
                                    <div class="">
                                        <table class="invoice_table text-center p-2" border="1" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Sl.No</th>
                                                    <th>Product Name</th>
                                                    <th>Qty</th>
                                                    <th width="15%">Rate</th>
                                                    <th width="10%">Amount</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $total_sum = '0';
                                                @endphp
                                                @foreach ($invoiceDetails as $key => $invoiceItem)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td class="text-capitalize">
                                                            {{ $invoiceItem->product->name }}
                                                        </td>
                                                        <td>
                                                            {{ $invoiceItem->selling_qty }}
                                                            {{ $invoiceItem->product->unit->short_form }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($invoiceItem->unit_price) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($invoiceItem->selling_price) }}/-
                                                        </td>
                                                    </tr>

                                                    @php
                                                        $total_sum += $invoiceItem->selling_price;
                                                    @endphp
                                                @endforeach
                                                {{-- <tr>
                                                    <td></td>
                                                    <td colspan="2" class="in_word">

                                                    </td>
                                                    <td>Sub Total</td>
                                                    <td class="text-center">
                                                        {{ number_format($total_sum) }}/-
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="2" class="in_word">

                                                    </td>
                                                    <td>Sub Total</td>
                                                    <td class="text-center">
                                                        {{ number_format($total_sum) }}/-
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="2" class="in_word">
                                                        @php
                                                            $in_word = numberTowords($invoice->payment->total_amount);
                                                        @endphp
                                                        <i><strong>In Word : {{ $in_word }}</strong> </i>
                                                    </td>
                                                    <td>Total</td>
                                                    <td class="text-center">
                                                        {{ number_format($invoice->payment->total_amount) }}/-
                                                    </td>
                                                </tr> --}}

                                                <tr class="custom-border">
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                    <td>Sub Total</td>
                                                    <td class="text-center"> {{ number_format($total_sum) }}/-
                                                    </td>
                                                </tr>
                                                @if ($invoice->payment->discount_amount != null)
                                                    <tr class="custom-border">
                                                        <td></td>
                                                        <td colspan="2"></td>
                                                        <td>
                                                            @if ($invoice->payment->discount_type != 'flat')
                                                                <span class="text-capitalize">Discount
                                                                    {{ $invoice->payment->discount_type }}%</span>
                                                            @else
                                                                <span
                                                                    class="text-capitalize">{{ $invoice->payment->discount_type }}
                                                                    Discount</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            {{ number_format($invoice->payment->discount_amount) }}/-
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($invoice->payment->delivery_charge != null)
                                                    <tr class="custom-border">
                                                        <td></td>
                                                        <td colspan="2"></td>
                                                        <td>Delivery Charge</td>
                                                        <td class="text-center">
                                                            {{ number_format($invoice->payment->delivery_charge) }}/-
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr class="custom-border">
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                    <td>Vat/Tax
                                                        {{ $invoice->payment->vat_tax == null ? '0.00%' : $invoice->payment->vat_tax . '%' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ number_format($invoice->payment->vat_amount) }}/-
                                                    </td>
                                                </tr>
                                                <tr class="custom-border">
                                                    <td></td>
                                                    <td colspan="2">
                                                        {{-- @php
                                                            $in_word = numberTowords($invoice->payment->total_amount);
                                                        @endphp
                                                        <i><strong>In Word : {{ $in_word }}</strong> </i> --}}
                                                    </td>

                                                    <td>Total Amount</td>
                                                    <td class="text-center">
                                                        {{ number_format($invoice->payment->total_amount) }}/-
                                                    </td>
                                                </tr>
                                                <tr class="custom-border">
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                    <td>Paid Amount</td>
                                                    <td class="text-center">{{ $invoice->payment->paid_amount }}/-
                                                    </td>
                                                </tr>
                                                @if ($invoice->payment->due_amount != '0')
                                                    <tr class="custom-border">
                                                        <td></td>
                                                        <td colspan="2"></td>
                                                        <td>Due Amount</td>
                                                        <td class="text-center">
                                                            {{ $invoice->payment->due_amount }}/-
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-12 invoice_page">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted"> Received By
                                        <strong>{{ $invoice->payment->customer->name }}</strong>
                                    </p>
                                    <div>
                                        <p class="mb-1 text-center text-dark fw-bold" style="border-top: 1px solid">
                                            Authorise Signature</p>
                                        <h5> <small class="fs-6">For</small> Xtream Energy Power Battery</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->

    </div>
    <!-- End Page-content -->
</body>
</html>
