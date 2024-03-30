@extends('admin.admin_master')
@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-between align-items-top">
                        <div>
                            <h6 class="m-0 font-weight-bold text-dark">{{ $warehouseInfo->name }} Stock History</h6>
                            <p class="text-dark m-0">{{ $warehouseInfo->phone }}</p>
                            <p class="text-dark m-0">{{ $warehouseInfo->email }}</p>
                            <p class="text-dark m-0">{{ $warehouseInfo->address }}</p>

                        </div>
                        <h6 class="m-0 font-weight-bold text-primary">
                            <a href="{{ url()->previous() }}">
                                <button class="btn btn-info"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                                    Prevoius</button>
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Packet Type</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Packet Type</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($warehouseStockInfo as $key => $stockHistory)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $stockHistory->package_type }}</td>
                                    <td>{{ $stockHistory->product->name }}</td>
                                    <td>{{ $stockHistory->quantity }} {{ $stockHistory->product->unit->short_form }}</td>
                                    <td>{{ number_format($stockHistory->unit_price ) }}</td>
                                    <td>{{ date('d-M,Y', strtotime($stockHistory->created_at)) }}</td>

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
