@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ URL::previous() }}">
                    <button class="btn btn-info"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer Name</th>
                                    <th>Invoice No</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer Name</th>
                                    <th>Invoice No</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($salesReport as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td> {{ $item->invoice->customer->name }}</td>
                                        <td>
                                            <a
                                                href="{{ route('invoice.view', $item->invoice_id) }}">{{ $item->invoice->invoice_no }}</a>
                                        </td>
                                        <td>BDT {{ number_format($item->total_amount) }}</td>
                                        <td>BDT {{ number_format($item->paid_amount) }}</td>
                                        <td>BDT {{ number_format($item->due_amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    @endsection
