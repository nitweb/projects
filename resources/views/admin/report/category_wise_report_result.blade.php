@extends('admin.admin_master')
@section('admin')
    <style>
        .table>:not(caption)>*>* {
            padding: 0 !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><span class="text-capitalize">{{ $report_type }}</span> Category Report</h4>

                <div class="d-print-none">
                    <div class="float-end">
                        <a class="btn btn-info" href="{{ url()->previous() }}">Go Back</a>
                        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">

            <div class="card-body">
                <div class="table-responsive py-3">
                    <div class="text-center">
                        <h4>
                            <span class="text-capitalize">{{ $report_type }}</span> from
                            {{ date('d-m-Y', strtotime($start_date)) }} to
                            {{ date('d-m-Y', strtotime($end_date)) }}
                        </h4>
                        <h4>Total <span class="text-capitalize">{{ $report_type }}</span>:

                            @if ($report_type == 'sales')
                                {{ number_format($allSearchResult->sum('selling_price')) }}
                            @else
                                @php
                                    $totalPurchase = 0;
                                    foreach ($allSearchResult as $item) {
                                        $amount = $item->unit_price * $item->quantity;
                                        $totalPurchase += $amount;
                                    }
                                @endphp
                                {{ number_format($totalPurchase) }}
                            @endif
                        </h4>
                    </div>
                    <table id="" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Category</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($allSearchResult as $key => $info)
                                <tr class="text-center">
                                    <td>
                                        {{ $report_type == 'sales' ? $info->date : date('d-m-Y', strtotime($info->created_at)) }}
                                    <td>
                                        @if ($info->invoice_id != null && $report_type == 'sales')
                                            <a target="_blank" href="{{ route('invoice.view', $info->invoice_id) }}">
                                                {{ $info->invoice->invoice_no }}</a>
                                        @else
                                            <a target="_blank" href="{{ route('view.purchase', $info->purchase_id) }}">
                                                {{ $info->purchase->purchase_no }}</a>
                                        @endif
                                    </td>
                                    <td> {{ $info['category']['name'] }} </td>
                                    <td>
                                        {{ $report_type == 'sales' ? number_format($info->selling_price) : number_format($info->unit_price * $info->quantity) }}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- End Page Content -->
@endsection
