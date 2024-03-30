@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!-- start page title -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Date Wise Net Profit Report</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Profit Report</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <form method="POST" action="{{ route('get.net.profit') }}">
                @csrf
                <div class="errorMsgContainer"></div>
                <div class="input-group mb-3">
                    <input type="date" class="form-control ml-2 date_picker" required name="start_date" id="start_date">
                    <input type="date" class="form-control ml-2 date_picker" required name="end_date" id="end_date">
                    <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <h4 class="text-muted text-center">Total Sales Profit: {{ $totalSales->sum('profit') }} TK. </h4> --}}
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Head</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Head</th>
                                    <th>Amount</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Purchase</td>
                                    <td>{{$purchase->sum('total_amount')}}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Sales</td>
                                    <td>{{$sales->sum('total_amount')}}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Expense</td>
                                    <td>{{$expense->sum('amount')}}</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Depreaciation</td>
                                    <td>0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
