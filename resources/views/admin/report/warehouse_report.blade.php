@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-3 bg-white">
                        <div class="row">
                            <div class="col-12 pt-3 pb-0 d-flex justify-content-center align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Warehouse Report</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Warehouse Name</th>
                                        <th>Quantity</th>
                                        <th>Grand Total</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Warehouse Name</th>
                                        <th>Quantity</th>
                                        <th>Grand Total</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($allWarehouse as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                @php
                                                    $qty = App\Models\WarehouseStock::where('warehouse_id', $item->id)->sum('quantity');
                                                @endphp
                                                {{ $qty }}
                                            </td>
                                            <td>
                                                @php
                                                    $total_price = App\Models\WarehouseStock::where('warehouse_id', $item->id)->sum('total_price');
                                                @endphp
                                                {{ $total_price }}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
@endsection
