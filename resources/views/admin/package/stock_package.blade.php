@extends('admin.admin_master')
@section('admin')
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <h3 class="text-center text-muted mb-3">All Packet Stock Report</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Product</th>
                                        <th>In House Master Stock</th>
                                        <th>In House Inner Stock</th>
                                        <th>Warehouse Master Stock</th>
                                        <th>Warehouse Inner Stock</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Product</th>
                                        <th>In House Master Stock</th>
                                        <th>In House Inner Stock</th>
                                        <th>Warehouse Master Stock</th>
                                        <th>Warehouse Inner Stock</th>
                                        <th>Total</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        @php
                                            $masterQuantity = App\Models\PackagingStore::where('product_id', $product->id)
                                                ->where('package_type', 'Master')
                                                ->get();
                                            $innerQuantity = App\Models\PackagingStore::where('product_id', $product->id)
                                                ->where('package_type', 'Inner')
                                                ->get();

                                            $warehouseMasterQuantity = App\Models\WarehouseStock::where('product_id', $product->id)
                                                ->where('package_type', 'Master')
                                                ->get();
                                            $warehouseInnerQuantity = App\Models\WarehouseStock::where('product_id', $product->id)
                                                ->where('package_type', 'Inner')
                                                ->get();
                                            $proudctQty = App\Models\PackagingStore::where('product_id', $product->id)->get();
                                            $total = 0;
                                            $innerTotal = 0;
                                            $masterTotal = 0;
                                            foreach ($proudctQty as $value) {
                                                if ($value->package_type == 'Master') {
                                                    $masterTotal += $value->quantity * $value->unit_price;
                                                }
                                                if ($value->package_type == 'Inner') {
                                                    $innerTotal += $value->quantity * $value->unit_price;
                                                }
                                                $amount = $value->quantity * $value->unit_price;
                                                $total += $amount;
                                            }

                                            $warehouseProudctQty = App\Models\WarehouseStock::where('product_id', $product->id)->get();
                                            $waretotal = 0;
                                            $wareInnerTotal = 0;
                                            $wareMasterTotal = 0;
                                            foreach ($warehouseProudctQty as $value) {
                                                if ($value->package_type == 'Master') {
                                                    $wareMasterTotal += $value->quantity * $value->unit_price;
                                                }
                                                if ($value->package_type == 'Inner') {
                                                    $wareInnerTotal += $value->quantity * $value->unit_price;
                                                }
                                                $wareAmount = $value->quantity * $value->unit_price;
                                                $waretotal += $wareAmount;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>
                                                Qty: {{ $masterQuantity->sum('quantity') }} {{ $product->unit->short_form }}
                                                <br>
                                                Amount: {{ $masterTotal }}
                                            </td>
                                            <td>
                                                Qty: {{ $innerQuantity->sum('quantity') }} {{ $product->unit->short_form }}
                                                <br>
                                                Amount: {{ $innerTotal }}
                                            </td>
                                            <td>
                                                Qty: {{ $warehouseMasterQuantity->sum('quantity') }}
                                                {{ $product->unit->short_form }}
                                                <br>
                                                Amount: {{ $wareMasterTotal }} TK
                                            </td>
                                            <td>
                                                Qty: {{ $warehouseInnerQuantity->sum('quantity') }}
                                                {{ $product->unit->short_form }}
                                                <br>
                                                Amount: {{ $wareInnerTotal }}
                                            </td>
                                            <td>
                                                Total Qty:
                                                {{ $proudctQty->sum('quantity') + $warehouseProudctQty->sum('quantity') }}
                                                <br>
                                                Total Amount: &#2547; {{ number_format($total + $waretotal) }}
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
        <!-- End Page Content -->
    @endsection
