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

                    <div class="card-body">
                        <h4 class="text-muted text-center">
                            Sale Profit from
                            {{ date('d-m-Y', strtotime(Request::post('start_date'))) }} to
                            {{ date('d-m-Y', strtotime(Request::post('end_date'))) }}</h4>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product</th>
                                    <th>Selling Quantity</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Profit</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product</th>
                                    <th>Selling Quantity</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Profit</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @php
                                    $totalProfit = 0;
                                @endphp
                                @foreach ($allCats as $key => $cat)
                                    @php
                                        $products = App\Models\Product::where('category_id', $cat->id)->first();

                                        $profits = App\Models\SalesProfit::where('product_id', $products->id)
                                            ->whereBetween('created_at', [$start_date, Carbon\Carbon::parse($end_date)->endOfDay()])
                                            ->get();

                                        $selling_qty = $profits->sum('selling_qty');
                                        $purchase_price = $profits->sum('unit_price_purchase');
                                        $selling_price = $profits->sum('unit_price_sales');
                                        $profit = $profits->sum('profit_per_unit');
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="text-capitalize">
                                            {{ $cat->name }}
                                        </td>
                                        <td>

                                            {{ $selling_qty }}
                                        </td>
                                        <td>
                                            BDT {{ number_format($purchase_price * $selling_qty) }}
                                        </td>
                                        <td>
                                            BDT {{ number_format($selling_price * $selling_qty) }}
                                        </td>
                                        <td>
                                            @if (str_starts_With($profit, '-') == true)
                                                ({{ number_format($profit * $selling_qty) }})
                                            @else
                                                BDT {{ number_format($profit * $selling_qty) }}
                                            @endif
                                        </td>
                                        @php
                                            $totalProfit += $profit * $selling_qty;
                                        @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                            <h5 class="text-center text-muted mb-5">Total Profit: <strong>BDT
                                    {{ number_format($totalProfit) }}</strong> </h5>
                        </table>

                    </div>

                </div>

            </div>
        </div>

    </div>
    <!-- End Page Content -->
@endsection
