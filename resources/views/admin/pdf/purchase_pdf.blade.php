@extends('admin.admin_master')
@section('admin')
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
    </style>

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Purchase</h4>

                        <div class="d-print-none">
                            <div class="float-end">
                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i
                                        class="fa fa-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

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
                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>To</strong>
                                            <br>
                                            <h5 class="mb-0">{{ $purchase->supplier->name }}</h5>
                                            {{ $purchase->supplier->name }}<br>
                                            {{ $purchase->supplier->mobile_no }}<br>
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
                                        <h3 class="font-size-16"><strong>Purchase Bill:
                                                {{ $purchase->purchase_no }}</strong></h3>
                                        <h3 class="font-size-16"><strong>Date:
                                                {{ date('d-m-Y', strtotime($purchase->date)) }}</strong></h3>
                                    </div>
                                    <div class="">
                                        <table class="invoice_table text-center p-2" border="1" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Sl.No</th>
                                                    <th>Product Name</th>
                                                    <th>Qty</th>
                                                    <th width="15%">Unit Price</th>
                                                    <th width="10%">Amount</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $total_sum = '0';
                                                @endphp
                                                @foreach ($purchaseMeta as $key => $purchaseItem)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            <span class="text-capitalize">
                                                                {{ $purchaseItem->ingredient->name }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                           <span class="text-capitalize"> {{ $purchaseItem->quantity }}
                                                            {{ $purchaseItem->ingredient->unitIngredient->short_form }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            {{ number_format($purchaseItem->unit_price) }}/-
                                                        </td>
                                                        <td class="text-end">
                                                            @php
                                                                $total = $purchaseItem->unit_price * $purchaseItem->quantity;
                                                                $total_sum += $total;
                                                            @endphp
                                                            {{ number_format($total) }}/-
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr class="custom-border text-end">
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                    <td>Sub Total</td>
                                                    <td> {{ number_format($total_sum) }}/-
                                                    </td>
                                                </tr>
                                                @if ($purchase->discount_amount != null)
                                                    <tr class="custom-border text-end">
                                                        <td></td>
                                                        <td colspan="2"></td>
                                                        <td>
                                                            <span class="text-capitalize">Discount Amount</span>
                                                        </td>
                                                        <td>
                                                            {{ number_format($purchase->discount_amount) }}/-
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr class="text-end">
                                                    <td></td>
                                                    <td colspan="2" class="in_word">
                                                        @php
                                                            $in_word = numberTowords($purchase->total_amount);
                                                        @endphp
                                                        <i><strong>In Word : </strong> {{ $in_word }}</i>
                                                    </td>
                                                    <td>Total</td>
                                                    <td>
                                                        {{ number_format($purchase->total_amount) }}/-
                                                    </td>
                                                </tr>
                                                <tr class="custom-border text-end">
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                    <td>Paid Amount</td>
                                                    <td>{{ $purchase->paid_amount }}/-
                                                    </td>
                                                </tr>
                                                @if ($purchase->due_amount != '0')
                                                    <tr class="custom-border text-end">
                                                        <td></td>
                                                        <td colspan="2"></td>
                                                        <td>Due Amount</td>
                                                        <td>
                                                            {{ $purchase->due_amount }}/-
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
                                    <p class="text-muted"> Received By <strong>{{ $purchase->supplier->name }}</strong>
                                    </p>
                                    <h5><small class="fs-6">For</small> XTream Green Power</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->

    </div>
    <!-- End Page-content -->
@endsection
