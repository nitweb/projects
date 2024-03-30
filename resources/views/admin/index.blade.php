@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.dashboard') }}">{{ siteInformation()->title }}</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-truncate font-size-14 mb-2">Total Sales</p>
                                    <h4 class="mb-2">BDT {{ number_format(round($runningMonthSale)) }}</h4>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-primary rounded-3">
                                        <i class="ri-shopping-cart-2-line font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                            <p>
                                <a href="{{ route('invoice.all') }}">View Sales</a>
                            </p>
                        </div><!-- end cardbody -->
                    </div><!-- end card -->
                </div><!-- end col -->
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-truncate font-size-14 mb-2">Total Purchase</p>
                                    <h4 class="mb-2">BDT {{ number_format(round($purchase->sum('total_amount'))) }}</h4>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-success rounded-3">
                                        <i class="<i ri-bank-card-fill font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                            <p>
                                <a class="text-success" href="{{ route('all.purchase') }}">View Purchase</a>
                            </p>
                        </div><!-- end cardbody -->
                    </div><!-- end card -->
                </div><!-- end col -->
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-truncate font-size-14 mb-2">Total Expense</p>
                                    <h4 class="mb-2">BDT {{ number_format(round($expense->sum('amount'))) }}</h4>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-primary rounded-3">
                                        <i class="ri-wallet-line font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                            <p>
                                <a class="text-primary" href="{{ route('all.expense') }}">View Expense</a>
                            </p>
                        </div><!-- end cardbody -->
                    </div><!-- end card -->
                </div><!-- end col -->
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-truncate font-size-14 mb-2">Total Due</p>
                                    <h4 class="mb-2">BDT {{ number_format(round($dueAmount)) }}</h4>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-success rounded-3">
                                        <i class="ri-exchange-dollar-line font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                            <p>
                                <a class="text-success" href="{{ route('credit.customer') }}">View Due</a>
                            </p>
                        </div><!-- end cardbody -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-truncate font-size-14 mb-2">Total Product</p>
                                    <h4 class="mb-2">{{ number_format(count($products)) }}</h4>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-success rounded-3">
                                        <i class="ri-list-check font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                            <p>
                                <a class="text-success" href="{{ route('product.all') }}">View Product</a>
                            </p>
                        </div><!-- end cardbody -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-truncate font-size-14 mb-2">Total Bank</p>
                                    <h4 class="mb-2">{{ number_format(count($bank)) }}</h4>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-primary rounded-3">
                                        <i class="ri-bank-line font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                            <p>
                                <a class="text-primary" href="{{ route('all.account') }}">View Bank</a>
                            </p>
                        </div><!-- end cardbody -->
                    </div><!-- end card -->
                </div><!-- end col -->
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-truncate font-size-14 mb-2">Supplier Payable</p>
                                    <h4 class="mb-2">{{ number_format($supplierAccount->sum('due_amount')) }}</h4>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-primary rounded-3">
                                        <i class="ri-bank-line font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                            <p>
                                <a class="text-primary" href="{{ route('all.account') }}">View Bank</a>
                            </p>
                        </div><!-- end cardbody -->
                    </div><!-- end card -->
                </div><!-- end col -->


            </div><!-- end row -->
        </div>

    </div>
    <!-- End Page-content -->
@endsection
