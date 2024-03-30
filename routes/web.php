<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\SalaryController;
use App\Http\Controllers\Backend\AccountController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\EmployeeSalaryController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\UnitController;
use App\Http\Controllers\Backend\AdvancedController;
use App\Http\Controllers\Backend\AssetCategoryController;
use App\Http\Controllers\Backend\AssetController;
use App\Http\Controllers\Backend\AssetSubCategoryController;
use App\Http\Controllers\Backend\BankController;
use App\Http\Controllers\Backend\BatteryController;
use App\Http\Controllers\Backend\DefaultController;
use App\Http\Controllers\Backend\ExpenseController;
use App\Http\Controllers\Backend\LiabilityController;
use App\Http\Controllers\Backend\PackaingController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductionController;
use App\Http\Controllers\Backend\ReturnController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SalesCommission;
use App\Http\Controllers\Backend\SalesCommissionController;
use App\Http\Controllers\Backend\SiteSettingController;
use App\Http\Controllers\Backend\TaxController;
use App\Http\Controllers\Backend\WarehouseController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WastesSaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/dashboard', function () {
    return view('users.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/logout', [AdminController::class, 'UserLogout'])->name('user.logout');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/', [AdminController::class, 'RedirectDashboard']);
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'ChangeAdminPassword'])->name('change.admin.password');
    Route::post('/update/change/password', [AdminController::class, 'UpdateAdminPassword'])->name('update.admin.password');



    Route::controller(CustomerController::class)->group(function () {
        // compnay all route
        Route::get('/customer/all', 'CustomerAll')->name('all.customer');
        Route::get('/customer/add', 'CustomerAdd')->name('add.customer');
        Route::post('/customer/store', 'CustomerStore')->name('store.customer');
        Route::get('/customer/edit/{id}', 'CustomerEdit')->name('edit.customer');
        Route::post('/customer/update', 'CustomerUpdate')->name('update.customer');
        Route::get('/customer/delete/{id}', 'CustomerDelete')->name('delete.customer');

        Route::get('/customer/bill/{id}', 'CustomerBill')->name('customer.bill');
        Route::get('/customer/bill/delete/{id}', 'CustomerBillDelete')->name('customer.bill.delete');
        //credit company
        Route::get('/credit/customer/all', 'CreditCustomer')->name('credit.customer');
        Route::get('/credit/customer/invoice/{invoice_id}', 'EditCreditCustomerInvoice')->name('edit.credit.customer');
        Route::post('/credit/customer/update/invoice/{invoice_id}', 'UpdateCustomerInvoice')->name('customer.update.invoice');
        Route::get('customer/invoice/details/{invoice_id}', 'CustomerInvoiceDetails')->name('customer.invoice.details');
    });


    // employee all route
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/emplopyee/all', 'EmployeeAll')->name('all.employee');
        Route::get('/emplopyee/add', 'EmployeeAdd')->name('add.employee');
        Route::post('/emplopyee/store', 'EmployeeStore')->name('store.employee');
        Route::get('/emplopyee/edit/{id}', 'EmployeeEdit')->name('edit.employee');
        Route::post('/emplopyee/update', 'EmployeeUpdate')->name('update.employee');
        Route::get('/emplopyee/delete/{id}', 'EmployeeDelete')->name('delete.employee');
        Route::get('/emplopyee/view/{id}', 'EmployeeView')->name('employee.view');
    });


    //salary all route
    Route::controller(EmployeeSalaryController::class)->group(function () {
        Route::get('/emplopyee/salary/increment/{id}', 'SalaryIncrement')->name('employee.salary.increment');
        Route::post('/emplopyee/salary/store/{id}', 'SalaryIncrementUpdate')->name('update.employee.salary');
        Route::get('/emplopyee/salary/details/{id}', 'SalaryDetails')->name('employee.salary.details');
    });


    // Category All Route
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category/all', 'categoryAll')->name('category.all');
        Route::get('/category/add', 'categoryAdd')->name('category.add');
        Route::post('/category/store', 'categoryStore')->name('category.store');
        Route::get('/category/edit/{id}', 'categoryEdit')->name('category.edit');
        Route::post('/category/update', 'categoryUpdate')->name('category.update');
        Route::get('/category/delete/{id}', 'categoryDelete')->name('category.delete');
    });

    // Supplier All Route
    Route::controller(SupplierController::class)->group(function () {
        // Route::get('/supplier/all/product', 'SupplierAllProduct')->name('supplier.all.product');
        // Route::get('/supplier/all/package', 'SupplierAllPackage')->name('supplier.all.package');
        Route::get('/supplier/all', 'SupplierAll')->name('supplier.all');
        Route::get('/supplier/add', 'SupplierAdd')->name('supplier.add');
        Route::post('/supplier/store', 'SupplierStore')->name('supplier.store');
        Route::get('/supplier/edit/{id}', 'SupplierEdit')->name('supplier.edit');
        Route::post('/supplier/update', 'SupplierUpdate')->name('supplier.update');
        Route::get('/supplier/delete/{id}', 'SupplierDelete')->name('supplier.delete');

        Route::get('/supplier/purcahse/{id}', 'SupplierBill')->name('supplier.all.purchase');
        Route::get('/supplier/account/{id}', 'SupplierAccountDetails')->name('supplier.account.details');
        Route::post('/supplier/account-report', 'SupplierAccountDetailReport')->name('get.supplier.account.detail');
    });

    // Product All Route
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product/all', 'ProductAll')->name('product.all');
        Route::get('/product/add', 'ProductAdd')->name('product.add');
        Route::post('/product/store', 'ProductStore')->name('product.store');
        Route::get('/product/edit/{id}', 'ProductEdit')->name('product.edit');
        Route::post('/product/update', 'ProductUpdate')->name('product.update');
        Route::get('/product/delete/{id}', 'ProductDelete')->name('product.delete');
        Route::get('/product/stock/{id}', 'GetProductStock')->name('get.product.stock');
        Route::get('/get/product/{id}', 'GetProductForSales')->name('get.product.sales');
        Route::get('/product/stock', 'ProductStockAll')->name('product.stock');
    });

    // Ingredient All Route
    Route::controller(IngredientController::class)->group(function () {
        Route::get('/ingredient/list', 'IngredientAll')->name('ingredient.all');
        Route::get('/ingredient/add', 'IngredientAdd')->name('ingredient.add');
        Route::post('/ingredient/store', 'IngredientStore')->name('ingredient.store');
        Route::get('/ingredient/edit/{id}', 'IngredientEdit')->name('ingredient.edit');
        Route::post('/ingredient/update', 'IngredientUpdate')->name('ingredient.update');
        Route::get('/ingredient/delete/{id}', 'IngredientDelete')->name('ingredient.delete');
        Route::get('/ingredient/purchase', 'IngredientPurchase')->name('ingredient.purchase');
        Route::post('/ingredient/store/purchase', 'StorePurchase')->name('ingredient.store.purchase');
    });

    // Battery All Route
    Route::controller(BatteryController::class)->group(function () {
        Route::get('/battery/list', 'BatteryAll')->name('battery.all');
        Route::get('/battery/add', 'BatteryAdd')->name('battery.add');
        Route::get('/battery/view/{id}', 'BatteryView')->name('view.battery');
        Route::post('/battery/store', 'BatteryStore')->name('battery.store');
        Route::post('/get/product/weight', 'GetProductWeight')->name('get.product.weight');
        Route::post('/get/wastage', 'GetWastage')->name('get.wastage');
    });

    // Production All Route
    Route::controller(ProductionController::class)->group(function () {
        Route::get('/production/list', 'ProductionAll')->name('production.all');
        Route::get('/production/add', 'ProductionAdd')->name('production.add');
        Route::get('/ingredient/stock/{id}', 'GetIngredientStock')->name('get.ingredient.stock');
        Route::post('get/ingredient/list', 'GetIngredientList')->name('get.ingredient.list');
    });


    // Unit All Route
    Route::controller(UnitController::class)->group(function () {
        Route::get('/unit/all', 'unitAll')->name('unit.all');
        Route::get('/unit/add', 'unitAdd')->name('unit.add');
        Route::post('/unit/store', 'unitStore')->name('unit.store');
        Route::get('/unit/edit/{id}', 'unitEdit')->name('unit.edit');
        Route::post('/unit/update', 'unitUpdate')->name('unit.update');
        Route::get('/unit/delete/{id}', 'unitDelete')->name('unit.delete');
    });


    // Default All Route
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice/all', 'InvoiceAll')->name('invoice.all');
        Route::get('/invoice/add', 'InvoiceAdd')->name('invoice.add');
        Route::post('/invoice/store', 'InvoiceStore')->name('invoice.store');
        Route::get('/invoice/edit/{id}', 'InvoiceEdit')->name('invoice.edit');
        Route::post('/invoice/update', 'InvoiceUpdate')->name('invoice.update');
        Route::get('/invoice/print/{id}', 'Invoiceprint')->name('invoice.print');
        Route::get('/invoice/delete/{id}', 'InvoiceDelete')->name('invoice.delete');
        Route::get('/invoice/view/{id}', 'InvoiceView')->name('invoice.view');

        Route::get('/invoice/send/{id}', 'InvoiceSendPdf')->name('invoice.send.mail');
    });


    // purchase all route
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('/all/purchase', 'AllPurchase')->name('all.purchase');
        Route::get('/add/purchase', 'AddPurchase')->name('add.purchase');
        Route::post('/store/purchase', 'StorePurchase')->name('store.purchase');
        Route::get('/edit/purchase/{id}', 'EditPurchase')->name('edit.purchase');
        Route::get('/view/purchase/{id}', 'ViewPurchase')->name('view.purchase');
        Route::post('/update/purchase', 'UpdatePurchase')->name('update.purchase');
        Route::get('/purchase/print/{id}', 'PurchasePrint')->name('purchase.print');
        Route::get('/delete/purchase/{id}', 'DeletePurchase')->name('delete.purchase');
        Route::post('/get/purchase', 'GetPurchase')->name('get.purchase');
        Route::post('/get/product', 'GetProduct')->name('get.product');
        Route::get('/purchase/history/{id}', 'PurchaseHistory')->name('purchase.history');
        // due payment by purchase id
        Route::get('/due-payment/purchase/{id}', 'PurchaseDuePayment')->name('purchase.due.payment');
        Route::post('/store/due-purchase', 'PurchaseDuePaymentStore')->name('store.purchase.due');
        Route::get('/print/purchase/{id}', 'PurchasePrint')->name('print.purchase');
    });



    // package all route
    Route::controller(WarehouseController::class)->group(function () {
        Route::get('/all/warehouse', 'AllWarehouse')->name('all.warehouse');
        Route::get('/add/warehouse', 'AddWarehouse')->name('add.warehouse');
        Route::post('/store/warehouse', 'StoreWarehouse')->name('store.warehouse');
        Route::get('/edit/warehouse/{id}', 'EditWarehouse')->name('edit.warehouse');
        Route::post('/update/warehouse', 'UpdateWarehouse')->name('update.warehouse');
        Route::get('/delete/warehouse/{id}', 'DeleteWarehouse')->name('delete.warehouse');
        Route::get('/warehouse/stock/history/{id}', 'WarehouseStockHistory')->name('warehouse.stock.history');



        Route::post('/get/packet/price', 'getPacketPrice')->name('get.packet.price');
        Route::post('/get/packet/stock', 'getPacketStock')->name('packet.stock');
    });


    // package all route
    Route::controller(PackaingController::class)->group(function () {
        Route::get('/all/package', 'AllPackage')->name('all.package');
        Route::get('/all/package/stock', 'AllPackageStock')->name('all.package.stock');
        Route::get('/add/package', 'AddPackage')->name('add.package');
        Route::post('/store/package', 'StorePackage')->name('store.package');
        Route::get('/edit/package/{id}', 'EditPackage')->name('edit.package');
        Route::get('/view/package/{id}', 'ViewPackage')->name('view.package');
        Route::post('/update/package', 'UpdatePackage')->name('update.package');
        Route::get('/delete/package/{id}', 'DeletePackage')->name('delete.package');
        Route::get('/print/package/{id}', 'PrintPackage')->name('print.package');


        // stock out package
        Route::get('/add/stock-out/package', 'AddStockOutPackage')->name('add.stock.out');
        Route::get('/package/stock-out/list', 'ListStockOutPackage')->name('package.stock.out.list');
        Route::post('/store/stock-out', 'StoreStockOutPackage')->name('store.stock.out');
        Route::get('/get/package/{id}', 'GetPackage')->name('get.package.product');
        Route::get('/get/package-stock/{id}', 'GetPackageStock')->name('get.package.stock');
    });


    // replace product all route
    Route::controller(ReturnController::class)->group(function () {
        Route::get('/all/customer/replace', 'CustomerAllReplace')->name('all.customer.replace');
        Route::get('/replace/product/list', 'AllReplaceList')->name('all.replace.list');
        Route::get('/in/house/list', 'InHouseReplace')->name('in.house.replace.list');
        Route::get('/customer/replace/history/{id}', 'CustomerReplaceHistory')->name('customer.replace.history');
        Route::get('/add/replace/product', 'AddReplaceProduct')->name('add.replace.product');
        Route::post('/store/replace/product', 'StoreReplaceProduct')->name('store.replace.product');
        Route::get('/edit/replace/product/{id}', 'EditReplaceProduct')->name('edit.replace.product');
        Route::post('/update/replace/product', 'UpdateReplaceProduct')->name('update.replace.product');
        Route::get('/delete/replace/product/{id}', 'DeleteReplaceProduct')->name('delete.replace.product');
        // send to customer after replace granted
        Route::get('/store/replace-granted/{id}', 'ReplaceGranted')->name('store.replace.granted');

        // supplier replace method
        Route::get('/all/replace/supplier', 'SupplierAllReplace')->name('all.replace.supplier');
        Route::get('/supplier/replace/history/{id}', 'SupplierReplaceHistory')->name('supplier.replace.history');
        Route::get('/store/replace/supplier/{id}', 'StoreReplaceSupplier')->name('store.replace.supplier');
        Route::get('/replace/overview', 'ReplaceOverview')->name('replace.overview');
    });


    // expense all route
    Route::controller(ExpenseController::class)->group(function () {
        Route::get('/all/expense', 'AllExpense')->name('all.expense');
        Route::get('/add/expense', 'AddExpense')->name('add.expense');
        Route::post('/store/expense', 'StoreExpense')->name('store.expense');
        Route::get('/edit/expense/{id}', 'EditExpense')->name('edit.expense');
        Route::post('/update/expense}', 'UpdateExpense')->name('update.expense');
        Route::get('/delete/expense/{id}', 'DeleteExpense')->name('delete.expense');
        Route::get('/daily/expense', 'DailyExpense')->name('daily.expense');
        Route::get('/monthly/expense', 'MonthlyExpense')->name('monthly.expense');
        Route::get('/yearly/expense', 'YearlyExpense')->name('yearly.expense');
        Route::post('/get/expense', 'GetExpense')->name('get.expense');
    });


    // Sales Commsion all route
    Route::controller(SalesCommissionController::class)->group(function () {
        Route::get('/all/commission', 'AllCommission')->name('all.commission');
        Route::get('/add/commission', 'AddCommission')->name('add.commission');
        Route::post('/store/commission', 'StoreCommission')->name('store.commission');
        Route::get('/edit/commission/{id}', 'EditCommission')->name('edit.commission');
        Route::post('/update/commission}', 'UpdateCommission')->name('update.commission');
        Route::get('/delete/commission/{id}', 'DeleteCommission')->name('delete.commission');
        Route::get('/target/commission', 'TargetCommission')->name('target.commission');
        Route::get('/history/commission/{id}', 'HistoryCommission')->name('history.commission');
        Route::get('/deactive/commission/{id}', 'DeactiveCommission')->name('deactive.commission');
    });



    // Tax all route
    Route::controller(TaxController::class)->group(function () {
        Route::get('/all/vat', 'AllTax')->name('all.tax');
        Route::get('/add/vat', 'AddTax')->name('add.tax');
        Route::post('/store/vat', 'StoreTax')->name('store.tax');
        Route::get('/edit/vat/{id}', 'EditTax')->name('edit.tax');
        Route::post('/update/vat}', 'UpdateTax')->name('update.tax');
        Route::get('/delete/tax/{id}', 'DeleteTax')->name('delete.tax');
        Route::get('/deactive/vat/{id}', 'DeactiveTax')->name('deactive.tax');
        Route::get('/active/vat/{id}', 'ActiveTax')->name('active.tax');
        Route::get('/get/vat/{id}', 'GetTaxPercentage')->name('get.tax.percentage');
    });





    // account all route
    Route::controller(AccountController::class)->group(function () {
        // account details filtering method
        Route::post('/get/account/details', 'GetAccountDetails')->name('get.account.detail');

        //profit calculation
        Route::get('/sales/profit', 'SalesProfit')->name('sales.profit');
        Route::post('/sales/profit/filter', 'SalesProfitResult')->name('sales.profit.filter');

        //product wise profit calculation
        Route::get('/product/wise/profit', 'ProductWiseProfit')->name('product.wise.profit');
        Route::post('/product/wise/profit/filter', 'ProductWiseProfitResult')->name('product.profit.filter');

        //product wise profit calculation
        Route::get('/cat/wise/profit', 'CatWiseProfit')->name('cat.wise.profit');
        Route::post('/cat/wise/profit/filter', 'CatWiseProfitResult')->name('cat.profit.filter');

        // balance report
        Route::get('/balance/sheet', 'BalanceSheet')->name('balance.sheet');


        //financial Statement
        Route::get('/monthly/statement', 'MonthlyStatement')->name('monthly.statement');
        Route::post('/get/monthly/statement', 'GetMonthlyStatement')->name('get.month.statement');


        //net profit calculation
        Route::get('/net/profit', 'NetProfit')->name('net.profit');
        Route::post('/net/sales/profit', 'NetSalesProfitResult')->name('get.net.sales.profit');


        // opening balance
        Route::get('all/opening/balance', 'AllOpeningBalance')->name('all.opening.balance');
        Route::get('add/opening/balance', 'AddOpeningBalance')->name('add.opening.balance');
        Route::post('store/opening/balance', 'StoreOpeningBalance')->name('store.opening.balance');
        Route::get('edit/opening/balance/{id}', 'EditOpeningBalance')->name('edit.opening.balance');
        Route::get('delete/opening/balance/{id}', 'DeleteOpeningBalance')->name('delete.opening.balance');
        Route::post('update/opening/balance', 'UpdateOpeningBalance')->name('update.opening.balance');
    });


    // payment module all route
    Route::controller(PaymentController::class)->group(function () {
        // account details filtering method
        Route::get('/customer/ledger/list', 'CustomerLedgerList')->name('customer.ledger.list');
        Route::get('/customer/payment/history/{id}', 'CustomerPaymentHistory')->name('customer.payment.history');
        Route::post('/customer/due/payment', 'CustomerDuePayment')->name('customer.due.payment');
        Route::get('/supplier/ledger/list', 'SupplierLedgerList')->name('supplier.ledger.list');
        Route::post('/supplier/due/payment', 'SupplierDuePayment')->name('supplier.due.payment');
        Route::get('/supplier/payment/history/{id}', 'SupplierPaymentHistory')->name('supplier.payment.history');

    });

    // Bank all route
    Route::controller(BankController::class)->group(function () {
        Route::get('/account/list', 'AllAccount')->name('all.account');
        Route::get('/add/account', 'AddAccount')->name('add.account');
        Route::post('/store/account', 'StoreAccount')->name('store.account');
        Route::get('/edit/account/{id}', 'EditAccount')->name('edit.account');
        Route::post('/update/account', 'UpdateAccount')->name('update.account');
        Route::get('delete/account/{id}', 'DeleteAccount')->name('delete.account');


        Route::post('/deposit/amount', 'DepositAmount')->name('deposit.amount');
        Route::get('/new/transfer', 'TransferAmount')->name('transfer.amount');
        Route::post('/store/transfer', 'StoreTransferAmount')->name('transfer.amount.store');
        Route::get('/transfer/list', 'TransferList')->name('transfer.list');
    });


    // Asset All Route
    Route::controller(AssetController::class)->group(function () {
        Route::get('/asset/list', 'AssetAll')->name('asset.all');
        Route::get('/asset/add', 'AssetAdd')->name('asset.add');
        Route::post('/asset/store', 'AssetStore')->name('asset.store');
        Route::get('/asset/edit/{id}', 'AssetEdit')->name('asset.edit');
        Route::post('/asset/update', 'AssetUpdate')->name('asset.update');
        Route::get('/asset/delete/{id}', 'AssetDelete')->name('asset.delete');

        Route::get('/depreciation/expense', 'DepreciationExpense')->name('depreciation.expense');
        Route::get('/depreciation/list', 'DepreciationList')->name('depreciation.list');
        Route::post('/get/depreciation', 'GetDepreciation')->name('get.depreciation');
    });

    // Liability All Route
    Route::controller(LiabilityController::class)->group(function () {
        Route::get('/liabilities/list', 'LiabilityAll')->name('liability.all');
        Route::get('/liabilities/add', 'LiabilityAdd')->name('liability.add');
        Route::post('/liabilities/store', 'LiabilityStore')->name('liability.store');
        Route::get('/liabilities/edit/{id}', 'LiabilityEdit')->name('liability.edit');
        Route::post('/liabilities/update', 'LiabilityUpdate')->name('liability.update');
        Route::get('/liabilities/delete/{id}', 'LiabilityDelete')->name('liability.delete');
        Route::post('/liabilities/payment', 'LiabilityPayment')->name('liability.payment');
    });

    // Asset Category All Route
    Route::controller(AssetCategoryController::class)->group(function () {
        Route::get('/asset/category/list', 'CategoryAll')->name('asset.category.all');
        Route::get('/asset/category/add', 'CategoryAdd')->name('asset.category.add');
        Route::post('/asset/category/store', 'CategoryStore')->name('asset.category.store');
        Route::get('/asset/category/edit/{id}', 'CategoryEdit')->name('asset.category.edit');
        Route::post('/asset/category/update', 'CategoryUpdate')->name('asset.category.update');
        Route::get('/asset/category/delete/{id}', 'CategoryDelete')->name('asset.category.delete');
    });

    // Asset sub category
    Route::controller(AssetSubCategoryController::class)->group(function () {
        Route::get('asset/all/sub-category', 'AllSubCategory')->name('asset.all.sub.category');
        Route::get('asset/add/sub-category', 'AddSubCategory')->name('asset.add.sub.category');
        Route::post('asset/store/sub-category', 'StoreSubCategory')->name('asset.store.sub.category');
        Route::post('asset/update/sub-category', 'UpdateSubCategory')->name('asset.update.sub.category');
        Route::get('asset/edit/sub-category/{id}', 'EditSubCategory')->name('asset.edit.sub.category');
        Route::get('asset/delete/sub-category/{id}', 'DeleteSubCategory')->name('asset.delete.sub.category');
        Route::get('asset/get/sub-category', 'GetSubCategory')->name('asset.get.sub.category');
    });


    // advance all route
    Route::controller(AdvancedController::class)->group(function () {
        Route::get('/all/advanced/salary', 'AllAdvancedSalary')->name('all.advanced.salary');
        Route::get('/add/advanced/salary', 'AddAdvancedSalary')->name('add.advanced.salary');
        Route::post('/store/advanced/salary', 'StoreAdvancedSalary')->name('store.advanced.salary');
        Route::get('/edit/advanced/{id}', 'EditAdvancedSalary')->name('edit.advanced.salary');
        Route::post('/update/salary', 'UpdateAdvancedSalary')->name('update.advanced.salary');
        Route::get('delete/advanced-salary/{id}', 'DeleteAdvancedSalary')->name('delete.advanced.salary');
    });

    // pay salary all route
    Route::controller(SalaryController::class)->group(function () {
        Route::get('/pay/salary', 'PaySalary')->name('pay.salary');
        Route::get('/pay/salary/{id}', 'PaySalaryNow')->name('pay.salary.now');
        Route::post('/store/salary', 'StorePaySalary')->name('pay.salary.store');

        // add slary
        Route::get('/add/salary', 'AddSalary')->name('add.salary');
        // Route::get('/store/salary', 'StoreSalary')->name('store.salary');

        // overtimes all routes
        Route::get('/all/overtime', 'AllOvertime')->name('all.overtime');
        Route::get('/add/overtime', 'AddOvertime')->name('add.overtime');
        Route::post('/store/overtime', 'StoreOvertime')->name('store.overtime');
        Route::get('/edit/overtime/{id}', 'EditOvertime')->name('edit.overtime');
        Route::post('/update/overtime', 'UpdateOvertime')->name('update.overtime');
        Route::get('/delete/overtime/{id}', 'DeleteOvertime')->name('delete.overtime');


        // overtimes all routes
        Route::get('/all/bonus', 'AllBonus')->name('all.bonus');
        Route::get('/add/bonus', 'AddBonus')->name('add.bonus');
        Route::post('/store/bonus', 'StoreBonus')->name('store.bonus');
        Route::get('/edit/bonus/{id}', 'EditBonus')->name('edit.bonus');
        Route::post('/update/bonus', 'UpdateBonus')->name('update.bonus');
        Route::get('/delete/bonus/{id}', 'DeleteBonus')->name('delete.bonus');


        // payment details
        Route::get('/payment/details/{id}', 'EmployeePaymentDetails')->name('employee.payment.details');
    });

    // report report method
    Route::controller(ReportController::class)->group(function () {
        Route::get('/employee/ledger', 'EmployeeLedger')->name('employee.ledger');
        Route::get('/employee/sales/report/{id}', 'EmployeeSalesReport')->name('employee.sales.report');
        Route::get('/category/report', 'CategoryReport')->name('category.report');
        Route::post('/get/category-report', 'GetCategoryReport')->name('get.cat.report');
        Route::get('/category-report/summary', 'GetCategoryReportSummary')->name('get.cat.report.summary');
        Route::post('/print/category/summary', 'PrintCategorySummary')->name('get.cat.report.summary.print');

        // invoice report
        Route::get('/daily/invoice/report', 'DailyInvoiceReport')->name('daily.invoice.report');
        Route::get('/daily/invoice/pdf', 'DailyInvoiceReportPdf')->name('daily.invoice.pdf');


        // purchase report
        Route::get('/purchase/report', 'PurchaseReport')->name('purchase.report');
        Route::post('/get/purchase/report', 'GetPurchaseReport')->name('get.purchase.report');

        // expense report
        Route::get('/expense/report', 'ExpenseReport')->name('expense.report');
        Route::post('/get/expense/report', 'GetExpenseReport')->name('get.expense.report');

        // product wise sales report
        Route::get('product/wise/sales/report', 'ProductWiseSalesReport')->name('product.wise.sales.report');
        Route::post('/get/product/wise/sales/report', 'GetProductWiseSalesReport')->name('get.product.wise.sales.report');

        // customer due report
        Route::get('/customer/due/report', 'CustomerDueReport')->name('customer.due.report');
        Route::post('/get/customer/due/report', 'GetCustomerDueReport')->name('get.customer.due.report');

        // customer report
        Route::get('/warehouse/stock/report', 'WarehouseStockReport')->name('warehouse.stock.report');
        Route::post('/get/warehouse/stock/report', 'GetWarehouseStockReport')->name('get.warehouse.stock.report');

        // customer report
        Route::get('/customer/report', 'CustomerReport')->name('customer.report');

        // warehouse report
        Route::get('/warehouse/report', 'WarehouseReport')->name('warehouse.report');

        // net profit
        Route::get('/net/profit/report', 'NetProfitReport')->name('net.profit.report');
        Route::post('/get/profit/report', 'GetProfitReport')->name('get.net.profit');
    });


    // category report method
    Route::controller(DefaultController::class)->group(function () {
        Route::get('get/salary', 'GetEmployeeSalary')->name('get.employee.salary');
        Route::get('get/advance', 'GetEmployeeAdvance')->name('get.employee.advance');
        // Route::get('/get-product', 'GetProduct')->name('get.product');
    });


    // permission all route
    Route::controller(RoleController::class)->group(function () {
        Route::get('/all/permission', 'AllPermission')->name('all.permission');
        Route::get('/add/permission', 'AddPermission')->name('add.permission');
        Route::post('/store/permission', 'StorePermission')->name('store.permission');
        Route::post('/update/permission', 'UpdatePermission')->name('update.permission');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission');
    });

    // role  all route
    Route::controller(RoleController::class)->group(function () {
        Route::get('/all/role', 'AllRole')->name('all.role');
        Route::get('/add/role', 'AddRole')->name('add.role');
        Route::post('/store/role', 'StoreRole')->name('store.role');
        Route::post('/update/role', 'UpdateRole')->name('update.role');
        Route::get('/edit/role/{id}', 'EditRole')->name('edit.role');
        Route::get('/delete/role/{id}', 'DeleteRole')->name('delete.role');

        // role in permission
        Route::get('/add/role/permission', 'AddRolepermission')->name('add.role.permission');
        Route::get('/all/role/permission', 'AllRolepermission')->name('all.role.permission');
        Route::post('/store/role/permission', 'StoreRolepermission')->name('role.permission.store');
        Route::get('admin/edit/role/{id}', 'AdminEditRole')->name('admin.edit.role');
        Route::post('/admin/role/update/{id}', 'AdminUpdateRole')->name('admin.role.update');
        Route::get('admin/delete/role/{id}', 'AdminDeleteRole')->name('admin.delete.role');
    });
    // end role all route

    // admin all route
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/all', 'AdminAll')->name('all.admin');
        Route::get('/admin/add', 'AdminAdd')->name('add.admin');
        Route::post('/admin/store', 'AdminStore')->name('store.admin');
        Route::get('/edit/admin/role/{id}', 'EditAdminRole')->name('edit.admin.role');
        Route::post('/update/admin/role', 'UpdateAdminRole')->name('update.admin.role');
        Route::get('/delete/admin/role/{id}', 'DeleteAdminRole')->name('delete.admin.role');
    });


    // site setting all route
    Route::controller(SiteSettingController::class)->group(function () {
        Route::get('site/setting', 'SiteSetting')->name('site.setting');
        Route::post('site/setting/update', 'SiteSettingUpdate')->name('site.setting.update');
    });
});


require __DIR__ . '/auth.php';
