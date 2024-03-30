@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-3 bg-white">
                        <div class="row">
                            <div class="col-12 pt-3 pb-0 d-flex justify-content-center align-items-center">
                                <h6 class="m-0 fs-4 font-weight-bold text-primary">Balance Sheet</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>
                                            <h5>Financial Overview</h5>
                                        </th>
                                        <th>
                                            <h5>Assets & Liability </h5>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="overall mb-4">
                                                <p class="mb-0">
                                                    <span class="fs-5 fw-bold">Total Purchase:
                                                        {{ number_format($purchase->sum('total_amount')) }} TK.
                                                    </span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fs-5 fw-bold">Total Sales:
                                                        {{ number_format($accounts_receivable->sum('total_amount')) }} TK.
                                                    </span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fs-5 fw-bold">Total Expense:
                                                        {{ number_format($expense->sum('amount')) }} TK.
                                                    </span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fs-5 fw-bold">Total Depreciation:
                                                        {{ number_format($depreciation->sum('amount')) }}TK.
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="overall-in-month">
                                                <h5 class="mb-2">Financial Overview in Montth</h5>
                                                <p class="mb-0">
                                                    @php
                                                        $currentMonth = date('m');
                                                        $monthlyPurchase = App\Models\Purchase::whereMonth('date', $currentMonth)->sum('total_amount');
                                                        $monthlySales = App\Models\SalesProfit::whereMonth('date', $currentMonth);
                                                        $monthlyDepreciation = App\Models\AssetDepreciation::whereMonth('date', $currentMonth)->sum('amount');
                                                        $monthlyExpense = App\Models\Expense::whereMonth('date', $currentMonth)->sum('amount');
                                                    @endphp
                                                    <span class="fs-5 fw-bold">Total Purchase:
                                                        {{ number_format($monthlyPurchase) }}
                                                        TK.
                                                    </span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fs-5 fw-bold">Total Sales:
                                                        {{ number_format($monthlySales->sum('selling_qty') * $monthlySales->sum('unit_price_sales')) }}
                                                        TK.
                                                    </span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fs-5 fw-bold">Total Expense:
                                                        {{ number_format($monthlyExpense) }} TK.
                                                    </span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fs-5 fw-bold">Total Depreciation:
                                                        {{ number_format($monthlyDepreciation) }}TK.
                                                    </span>
                                                </p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div>
                                                    <h5 class="mb-2">Current Assets</h5>
                                                    @php
                                                        $totalCurrentAsset = 0;
                                                        $accounts_receivableAmount = $accounts_receivable->sum('due_amount');
                                                        $total_accounts_amount = $accounts_amount->sum('balance');
                                                    @endphp
                                                    @foreach ($assetCategory as $category)
                                                        @php
                                                            $currentAssetAmount = App\Models\Asset::where('cat_id', $category->id)
                                                                ->where('type', 'current')
                                                                ->sum('price');
                                                            $totalCurrentAsset += $currentAssetAmount;
                                                        @endphp
                                                        <p class="mb-0">
                                                            <span class="fs-5 fw-bold">{{ $category->name }}:
                                                                {{ number_format($currentAssetAmount) }} TK.</span>
                                                        </p>
                                                    @endforeach
                                                    <p class="mb-0">
                                                        <span class="fs-5 fw-bold">Accounts Receivable:

                                                            {{ number_format($accounts_receivableAmount) }} TK.
                                                        </span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <span class="fs-5 fw-bold">Accounts:
                                                            {{ number_format($total_accounts_amount) }} TK.
                                                        </span>
                                                    </p>
                                                    <hr>
                                                    <h6 class="fs-5 fw-bold">Total:
                                                        {{ number_format($totalCurrentAsset + $accounts_receivableAmount + $total_accounts_amount) }}
                                                        TK.
                                                    </h6>
                                                </div>
                                                <div>
                                                    <h5 class="mb-2">Fixed Assets</h5>
                                                    @php
                                                        $totalFixedAsset = 0;
                                                    @endphp
                                                    @foreach ($assetCategory as $category)
                                                        @php

                                                            $fixedAssetAmount = App\Models\Asset::where('cat_id', $category->id)
                                                                ->where('type', 'fixed')
                                                                ->sum('price');
                                                            $totalFixedAsset += $fixedAssetAmount;
                                                        @endphp
                                                        <p class="mb-0">
                                                            <span class="fs-5 fw-bold">{{ $category->name }}:
                                                                {{ number_format($fixedAssetAmount) }} TK.</span>
                                                        </p>
                                                    @endforeach
                                                    <hr>
                                                    <h6 class="fs-5 fw-bold">Total:
                                                        {{ number_format($totalFixedAsset) }} TK.
                                                    </h6>
                                                </div>
                                            </div>

                                            <hr style="height: 3px;">
                                            <h5 class="mb-2">Liabiliteis</h5>
                                            <p class="mb-0">
                                                <span class="fs-5 fw-bold">Accounts Payable:
                                                    @php
                                                        $totalPayable = $accounts_payable->sum('due_amount');
                                                        $totalliabilities = $liabilities->sum('amount');
                                                    @endphp
                                                    {{ number_format($totalPayable) }} TK.
                                                </span>
                                            </p>
                                            <p class="mb-0">
                                                @foreach ($liabilities as $liability)
                                                    <span class="fs-5 fw-bold">{{ $liability->head }}:
                                                        {{ number_format($liability->amount) }} TK.
                                                    </span>
                                                @endforeach
                                            </p>
                                            <hr>
                                            <h6 class="fs-5 fw-bold">Total:
                                                {{ number_format($totalPayable + $totalliabilities) }} TK.
                                            </h6>
                                        </td>
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>
                                            <h5>Financial Overview</h5>
                                        </th>
                                        <th>
                                            <h5>Assets & Liability </h5>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
