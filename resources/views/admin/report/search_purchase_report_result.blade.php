@extends('admin.admin_master')
@section('admin')
    <style>
        .table>:not(caption)>*>* {
            padding: 5px !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-muted ">Filtering Purchase Report Result</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">
                                <div class="d-print-none">
                                    <div class="float-end">
                                        <a class="btn btn-info" href="{{ url()->previous() }}">Go Back</a>
                                        <a href="javascript:window.print()"
                                            class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i>
                                            Print
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <!-- DataTales Example -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive" id="printContent">
                    <h4 class="text-muted text-center">Expense of
                        {{ date('d-m-Y', strtotime(Request::post('start_date'))) }} to
                        {{ date('d-m-Y', strtotime(Request::post('end_date'))) }}</h4>

                    <table class="table table-bordered" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Purchase No</th>
                                <th>Amount</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                $paid_total = 0;
                                $due_total = 0;
                            @endphp
                            @foreach ($allPurchase as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $item->purchase_no }}
                                    </td>
                                    <td>
                                        @php
                                            $total += $item->total_amount;
                                        @endphp

                                        {{ number_format($item->total_amount) }}
                                    </td>
                                    <td>
                                        @php
                                            $paid_total += $item->paid_amount;
                                        @endphp

                                        {{ number_format($item->paid_amount) }}
                                    </td>
                                    <td>
                                        @php
                                            $due_total += $item->due_amount;
                                        @endphp

                                        {{ number_format($item->due_amount) }}
                                    </td>
                                    <td>
                                        {{ $item->date }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <h5 class="text-center text-muted">Total Amount: <strong>BDT {{ $total }}</strong> </h5>
                        <h5 class="text-center text-muted">Total Paid: <strong>BDT {{ $paid_total }}</strong> </h5>
                        <h5 class="text-center text-muted mb-3">Total Due: <strong>BDT {{ $due_total }}</strong> </h5>
                    </table>

                </div>
            </div>

        </div>

    </div>
    <!-- End Page Content -->


    <script>
        function printDiv(printContent) {
            let printContents = document.getElementById(printContent).innerHTML;
            let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
