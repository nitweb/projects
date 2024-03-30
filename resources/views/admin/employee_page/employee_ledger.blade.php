@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.employee') }}">
                    <button class="btn btn-info">Add Employee</button>
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
                                    <th>Name</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount</th>
                                    <th>Total Order</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount</th>
                                    <th>Total Order</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($employees as $key => $item)
                                    @php
                                           $salesReport = App\Models\salesComissionMeta::where('sales_comission_metas.employee_id', $item->id)
                                                ->join('payments', 'sales_comission_metas.invoice_id', '=', 'payments.invoice_id')
                                                ->get(['payments.*']);
                                            // dd($salesReport);
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>BDT {{ number_format($salesReport->sum('total_amount')) }}</td>
                                        <td>BDT {{ number_format($salesReport->sum('paid_amount')) }}</td>
                                        <td>BDT {{ number_format($salesReport->sum('due_amount')) }}</td>
                                        <td><span class="badge bg-info">{{ count($salesReport) }}</span></td>
                                        <td>
                                            <a href="{{ route('employee.sales.report',$item->id)}}" class="btn btn-info">
                                                <i class="fa fa-eye" aria-hidden="true"></i> View Sales</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    @endsection
