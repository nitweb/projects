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
                        <h4 class="mb-sm-0">Package</h4>

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
                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>To</strong>
                                            <br>
                                            <h5 class="mb-0">{{ $package->supplier->name }}</h5>
                                            {{ $package->supplier->name }}<br>
                                            {{ $package->supplier->mobile_no }}<br>
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
                                        <h3 class="font-size-16"><strong>Package No: {{ $package->package_no }}</strong>
                                        </h3>
                                        <h3 class="font-size-16"><strong>Date:
                                                {{ date('d-m-Y', strtotime($package->date)) }}</strong></h3>
                                    </div>
                                    <div class="">
                                        <table class="invoice_table text-center p-2" border="1" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Sl.No</th>
                                                    <th>Packet Type</th>
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
                                                @foreach ($packageMeta as $key => $packageItem)
                                                    <tr class="text-capitalize">
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            {{ $packageItem->package_type }} Packet
                                                        </td>
                                                        <td>
                                                            {{ $packageItem->product->name }}
                                                        </td>
                                                        <td>
                                                            {{ $packageItem->quantity }}
                                                        </td>
                                                        <td>
                                                            {{ $packageItem->unit_price }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($packageItem->unit_price * $packageItem->quantity) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td colspan="3" class="in_word">
                                                        @php
                                                            $in_word = numberTowords($package->total_amount);
                                                        @endphp
                                                        <i><strong>In Word : </strong> {{ $in_word }}</i>
                                                    </td>
                                                    <td>Total</td>
                                                    <td class="text-center">
                                                        {{ number_format($package->total_amount) }}/-
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-12 invoice_page">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted"> Received By <strong>{{ $package->supplier->name }}</strong>
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
