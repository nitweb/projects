@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All {{ $title }} List</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.stock.out') }}">
                    <button class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"> Add {{ $title }}</i>
                    </button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Warehouse Name</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Warehouse Name</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($allWarehouse as $key => $warehouse)
                                        @php
                                            $quantity = App\Models\WarehouseStock::where('warehouse_id', $warehouse->id)->sum('quantity');
                                            $amount = App\Models\WarehouseStock::where('warehouse_id', $warehouse->id)->sum('total_price');
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $warehouse->name }}
                                            </td>
                                            <td>
                                                {{ $quantity }} Pics
                                            </td>
                                            <td>
                                                {{ number_format($amount) }}/-
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a style="margin-left: 5px;"
                                                        href="{{ route('warehouse.stock.history', $warehouse->id) }}"
                                                        class="btn btn-info text-white" title="Stock History">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                        Stock History
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
@endsection
