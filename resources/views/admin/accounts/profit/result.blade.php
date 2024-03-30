@extends('admin.admin_master')
@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <div class="card-header pb-3  d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Profit Data</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ url()->previous() }}">
                    <button class="btn btn-info">Back</button>
                </a>
            </h6>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-muted text-center">
                            Sale Profit from
                            {{ date('d-m-Y', strtotime(Request::post('start_date'))) }} to
                            {{ date('d-m-Y', strtotime(Request::post('end_date'))) }}</h4>
                        <h5 class="text-center text-muted mb-3">Total Profit: <strong>BDT
                                {{ number_format($totalSales->sum('profit')) }}</strong> </h5>
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Selling Qty</th>
                                    <th>Profit</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Selling Qty</th>
                                    <th>Profit</th>
                                    <th>Date</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($totalSales as $key => $sales)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $sales->product->name }}
                                        </td>
                                        <td>{{ number_format($sales->unit_price_purchase) }}</td>
                                        <td>{{ number_format($sales->unit_price_sales) }}</td>
                                        <td>{{ $sales->selling_qty }}</td>
                                        <td>
                                            {{ str_starts_With($sales->profit, '-') == true ? '(' . number_format(abs($sales->profit)) . ')' : number_format($sales->profit) }}
                                        </td>
                                        <td>{{ date('d-M,Y', strtotime($sales->date)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Page Content -->
@endsection
