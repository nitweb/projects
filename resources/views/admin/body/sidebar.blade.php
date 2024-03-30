<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->


        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-file-user-line"></i>
                        <span>Customer Manage</span>
                    </a>

                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('all.customer') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>All Customer</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-user-received-line"></i>
                        <span>Supplier Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{ route('supplier.all') }}"><i class="ri-arrow-right-line"></i>
                                Supplier List</a>
                        </li>
                        <li><a href="{{ route('supplier.add') }}"><i class="ri-arrow-right-line"></i>
                                Add Supplier</a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-list-check"></i>
                        <span>Product Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('category.all') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Product Category</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('product.all') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>All Product</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-list-check"></i>
                        <span>Ingredient Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('ingredient.all') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Ingredient List</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('ingredient.add') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Add Ingredient</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('ingredient.purchase') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Add Purchase</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('all.purchase') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i> <span>Purchase List</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-list-check"></i>
                        <span>Battery Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('battery.all') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Battery List</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('battery.add') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Battery Add</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-list-check"></i>
                        <span>Production Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="#" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Production List</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('production.add') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Production Add</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shopping-cart-2-fill"></i>
                        <span>Sales Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li>
                            <a href="{{ route('invoice.all') }}">
                                <i class="ri-arrow-right-line"></i>
                                <span>All Sales</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-stock-line"></i>
                        <span>Sotck Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Product Stock</span>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('product.stock') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Packet Stock</span>
                            </a>
                        </li> --}}
                    </ul>
                </li>
                <hr>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-percent-fill"></i>
                        <span>Sales Commission</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li>
                            <a href="{{ route('all.commission') }}">
                                <i class="ri-arrow-right-line"></i>
                                <span>All Commission</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('target.commission') }}">
                                <i class="ri-arrow-right-line"></i>
                                <span>Commission Target</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-database-2-fill"></i>
                        <span>Warehouse Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('all.warehouse') }}"><i class="ri-arrow-right-line"></i>All Warehouse
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-user-2-fill"></i>
                        <span>Employee Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{ route('all.employee') }}"><i class="ri-arrow-right-line"></i>All
                                Employee</a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-hand-coin-line"></i>
                        <span>Salary Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('all.advanced.salary') }}">
                                <i class="ri-arrow-right-line"></i>All Advanced
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pay.salary') }}">
                                <i class="ri-arrow-right-line"></i>Pay Salary
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('add.salary') }}">
                                <i class="ri-arrow-right-line"></i>Add Salary
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('all.overtime') }}">
                                <i class="ri-arrow-right-line"></i>All Overtime
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('all.bonus') }}">
                                <i class="ri-arrow-right-line"></i>All Bonus
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-find-replace-fill"></i>
                        <span>Replace Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('replace.overview') }}">
                                <i class="ri-arrow-right-line"></i>Replace Overview
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('all.replace.list') }}">
                                <i class="ri-arrow-right-line"></i>Replace Product List
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('in.house.replace.list') }}">
                                <i class="ri-arrow-right-line"></i>In House List
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('all.customer.replace') }}">
                                <i class="ri-arrow-right-line"></i>Customer List
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('all.replace.supplier') }}">
                                <i class="ri-arrow-right-line"></i>Supplier List
                            </a>
                        </li>
                    </ul>
                </li>

                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-briefcase-4-fill"></i>
                        <span>Account Setup</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('all.account') }}"><i class="ri-arrow-right-line"></i> Accounts
                                List</a>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"><i
                                    class="ri-arrow-right-line"></i>Payment</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li>
                                    <a href="{{ route('customer.ledger.list') }}"><i class="ri-arrow-right-line"></i>
                                        Customer Ledger</a>
                                </li>
                                <li>
                                    <a href="{{ route('supplier.ledger.list') }}"><i class="ri-arrow-right-line"></i>
                                        Supplier Ledger</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"><i
                                    class="ri-arrow-right-line"></i>Assets</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li>
                                    <a href="{{ route('asset.all') }}"><i class="ri-arrow-right-line"></i>
                                        Assets List</a>
                                </li>
                                <li>
                                    <a href="{{ route('depreciation.list') }}"><i class="ri-arrow-right-line"></i>
                                        Depreciation List</a>
                                </li>
                                <li>
                                    <a href="{{ route('asset.category.all') }}"><i class="ri-arrow-right-line"></i>
                                        Category List</a>
                                </li>
                                <li>
                                    <a href="{{ route('asset.all.sub.category') }}"><i
                                            class="ri-arrow-right-line"></i>
                                        SubCategory List</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('liability.all') }}">
                                <i class="ri-arrow-right-line"></i> Liabilities
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('all.expense') }}"><i class="ri-arrow-right-line"></i> All Expense </a>
                        </li>
                        <li>
                            <a href="{{ route('transfer.amount') }}">
                                <i class="ri-arrow-right-line"></i> Bank Transfter
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('transfer.list') }}">
                                <i class="ri-arrow-right-line"></i> Transaction list
                            </a>
                        </li>

                        <li>
                        <li>
                            <a href="{{ route('sales.profit') }}"><i class="ri-arrow-right-line"></i>Sales Profit</a>
                        </li>
                        <li>
                            <a href="{{ route('product.wise.profit') }}"><i class="ri-arrow-right-line"></i>Product
                                Wise Profit</a>
                        </li>
                        <li>
                            <a href="{{ route('cat.wise.profit') }}"><i class="ri-arrow-right-line"></i>Category Wise
                                Profit</a>
                        </li>
                        <li>
                            <a href="{{ route('net.profit') }}"><i class="ri-arrow-right-line"></i>Net Profit</a>
                        </li>
                        <li>
                            <a href="{{ route('monthly.statement') }}"><i class="ri-arrow-right-line"></i>Monthly
                                Statement</a>
                        </li>
                        <li>
                            <a href="{{ route('balance.sheet') }}"><i class="ri-arrow-right-line"></i> Balance
                                Sheet</a>
                        </li>

                        <li>
                            <a href="{{ route('all.opening.balance') }}"><i class="ri-arrow-right-line"></i> Opening
                                Balance</a>
                        </li>
                    </ul>
                </li>
                <hr>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-file-text-fill"></i>
                        <span>Report Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('employee.ledger') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Employee Ledger</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('category.report') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Category Report</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('get.cat.report.summary') }}">
                                <i class="ri-arrow-right-line"></i>Category Summery</a>
                        </li>
                        <li>
                            <a href="{{ route('purchase.report') }}">
                                <i class="ri-arrow-right-line"></i>Purchase Report</a>
                        </li>
                        <li>
                            <a href="{{ route('expense.report') }}">
                                <i class="ri-arrow-right-line"></i>Expense Report</a>
                        </li>
                        <li>
                            <a href="{{ route('product.wise.sales.report') }}">
                                <i class="ri-arrow-right-line"></i>Sales Report</a>
                        </li>
                        <li>
                            <a href="{{ route('customer.due.report') }}">
                                <i class="ri-arrow-right-line"></i>Customer Due Report</a>
                        </li>
                        <li>
                            <a href="{{ route('customer.report') }}">
                                <i class="ri-arrow-right-line"></i>Customer Report</a>
                        </li>
                        <li>
                            <a href="{{ route('warehouse.report') }}">
                                <i class="ri-arrow-right-line"></i>Warehouse Report</a>
                        </li>
                    </ul>
                </li>
                <hr>

                @if (Auth::user()->can('role.permission.menu'))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-layout-3-line"></i>
                            <span>Role & Permission</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            @if (Auth::user()->can('all.permission'))
                                <li>
                                    <a href="{{ route('all.permission') }}" class="waves-effect">
                                        <i class="ri-layout-3-line"></i>
                                        <span>All Permission</span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->can('all.role'))
                                <li>
                                    <a href="{{ route('all.role') }}">
                                        <i class="ri-arrow-right-line"></i>All Role</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('all.role.permission'))
                                <li>
                                    <a href="{{ route('all.role.permission') }}">
                                        <i class="ri-arrow-right-line"></i>All Role Permission</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <hr>
                @endif
                @if (Auth::user()->can('admin.menu'))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-layout-3-line"></i>
                            <span>Admin Manage</span>
                        </a>
                        <ul>
                            @if (Auth::user()->can('admin.list'))
                                <li>
                                    <a href="{{ route('all.admin') }}">
                                        <i class="ri-arrow-right-line"></i>All Admin
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-settings-2-line"></i>
                        <span>Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('unit.all') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Unit List</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('all.tax') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Vat list</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('site.setting') }}" class="waves-effect">
                                <i class="ri-arrow-right-line"></i>
                                <span>Site Setting</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
