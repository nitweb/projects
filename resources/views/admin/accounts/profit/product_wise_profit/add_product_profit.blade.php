@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!-- start page title -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Product Wise Profit Report</h4>
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
            <form method="POST" action="{{ route('product.profit.filter') }}">
                @csrf
                <div class="errorMsgContainer"></div>
                <div class="input-group mb-3">
                    <input type="date" class="form-control ml-2 date_picker" required name="start_date" id="start_date">
                    <input type="date" class="form-control ml-2 date_picker" required name="end_date" id="end_date">
                    <button class="btn btn-info submit_btn ml-2" type="submit">Search</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product</th>
                                    <th>Selling Quantity</th>
                                    <th>Total Purchase</th>
                                    <th>Total Sales</th>
                                    <th>Discount</th>
                                    <th>Total Profit</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product</th>
                                    <th>Selling Quantity</th>
                                    <th>Total Purchase</th>
                                    <th>Total Sales</th>
                                    <th>Discount</th>
                                    <th>Total Profit</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($allProducts as $key => $product)
                                    @php
                                        $profits = App\Models\SalesProfit::where('product_id', $product->id)->get();
                                        $totalPurchase = 0;
                                        $totalSales = 0;
                                        foreach ($profits as $sale) {
                                            $totalPurchase += $sale->selling_qty * $sale->unit_price_purchase;
                                            $totalSales += $sale->selling_qty * $sale->unit_price_sales;
                                        }

                                        $selling_qty = $profits->sum('selling_qty');
                                        $discount = $profits->sum('discount_per_unit');
                                        $totalProfit = $profits->sum('profit');
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="text-capitalize">
                                            {{ $product->name }}
                                        </td>
                                        <td>
                                            {{ $selling_qty }}
                                        </td>
                                        <td>
                                            BDT {{ number_format($totalPurchase) }}
                                        </td>
                                        <td>
                                            BDT {{ number_format($totalSales) }}
                                        </td>
                                        <td>
                                            BDT {{ number_format($discount) }}
                                        </td>
                                        <td>
                                            BDT {{ number_format($totalProfit) }}
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


    {{-- js --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
@endsection
