<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountDetail;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\OpeningBalance;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\SalesProfit;
use App\Models\AssetCategory;
use App\Models\SupplierAccount;
use App\Models\AssetDepreciation;
use App\Models\Liability;
use App\Models\Asset;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AccountController extends Controller
{

    /*######### start expense method  #################**/
    public function AllExpense()
    {
        $allExpense = Expense::latest()->get();
        return view('admin.accounts.expense_page.all_expense', compact('allExpense'));
    }
    public function AddExpense()
    {
        $employees = Employee::OrderBy('name', 'asc')->get();
        $banks = Bank::OrderBy('name', 'asc')->get();
        return view('admin.accounts.expense_page.add_expense', compact('employees', 'banks'));
    }

    public function StoreExpense(Request $request)
    {
        $expense = new Expense();
        if ($request->expense_head == 'Other') {
            $expense->head = $request->others;
        } else {
            $expense->head = $request->expense_head;
        }
        $expense->amount = $request->amount;
        $expense->date = $request->date;
        $expense->description = $request->description;
        $expense->created_at = Carbon::now();
        $expense->save();

        $notification = array(
            'message' => 'Expense Addedd Successfully',
            'alert_type' => 'success'
        );

        return redirect()->route('all.expense')->with($notification);
    }


    public function EditExpense($id)
    {
        $expenseInfo = Expense::findOrFail($id);
        return view('admin.accounts.expense_page.edit_expense', compact('expenseInfo'));
    }

    public function UpdateExpense(Request $request)
    {
        $expense = Expense::findOrFail($request->id);

        if ($request->expense_head == 'Other') {
            $expense->head = $request->others;
        } else {
            $expense->head = $request->expense_head;
        }


        $expense->description = $request->description;
        $expense->amount = $request->amount;
        $expense->date = $request->date;
        $expense->save();
        $notification = array(
            'message' => 'Expense updated Successfully',
            'alert_type' => 'success'
        );

        return redirect()->route('all.expense')->with($notification);
    }

    public function MonthlyExpense()
    {
        $current_month = date('m');
        $monthlyExpense = Expense::whereMonth('created_at', $current_month)->get();

        $totalMonthlyExpense = Expense::whereMonth('created_at', $current_month)->sum('amount');
        return view('admin.accounts.expense_page.monthly_expense', compact('monthlyExpense', 'totalMonthlyExpense'));
    }

    public function DailyExpense()
    {
        $today = date('Y-m-d');
        $todayExpense = Expense::whereDate('created_at', $today)->get();
        $totalDailyExpense = Expense::whereDate('created_at', $today)->sum('amount');
        return view('admin.accounts.expense_page.daily_expense', compact('todayExpense', 'totalDailyExpense'));
    }


    public function YearlyExpense()
    {
        $current_year = date('Y');
        $yearlyExpense = Expense::whereYear('created_at', $current_year)->get();
        $totalYearlyExpense = Expense::whereYear('created_at', $current_year)->sum('amount');
        return view('admin.accounts.expense_page.yearly_expense', compact('yearlyExpense', 'totalYearlyExpense'));
    }


    public function GetExpense(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;


        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allExpense = Expense::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.accounts.expense_page.search_expense_result', compact('allExpense', 'start_date', 'end_date',));
    }

    public function DeleteExpense($id)
    {
        Expense::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Expense Deleted Successfully',
            'alert_type' => 'success'
        );

        return redirect()->route('all.expense')->with($notification);
    }

    /*######### End All expense method  #################**/


    /*######### Start Prodile Calculate method  #################**/
    public function SalesProfit()
    {
        $totalSales = SalesProfit::latest()->get();
        return view('admin.accounts.profit.add_profit', compact('totalSales'));
    }

    //Product Wise Profit Calculate

    public function ProductWiseProfit()
    {
        $allProducts = Product::get();
        return view('admin.accounts.profit.product_wise_profit.add_product_profit', compact('allProducts'));
    }

    public function ProductWiseProfitResult(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();

            $allProducts = Product::get();
        }

        return view('admin.accounts.profit.product_wise_profit.product_profit_result', compact('allProducts', 'startDate', 'endDate', 'start_date', 'end_date'));
    }

    //Category Wise Profit Calculate

    public function CatWiseProfit()
    {
        $allCats = Category::get();

        return view('admin.accounts.profit.cat_wise_profit.add_cat_profit', compact('allCats'));
    }

    public function CatWiseProfitResult(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();

            $allCats = Category::get();
        }

        return view('admin.accounts.profit.cat_wise_profit.cat_profit_result', compact('allCats', 'startDate', 'endDate', 'start_date', 'end_date'));
    }




    public function SalesProfitResult(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $totalSales = SalesProfit::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.accounts.profit.result', compact('totalSales', 'startDate', 'endDate'));
    }


    public function GetAccountDetails(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $company_id = $request->company_id;

        if ($start_date == null && $end_date == null) {
            $billDetails = AccountDetail::all();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $billDetails = AccountDetail::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])->where('company_id', $request->company_id)
                ->get();
        }
        return view('admin.report.account_detials_report', compact('billDetails', 'start_date', 'end_date', 'company_id'));
    }


    // opening balance method
    public function AllOpeningBalance()
    {
        $allOpening = OpeningBalance::where('status', '1')->get();
        return view('admin.accounts.opening_balance.all_opening', compact('allOpening'));
    }
    public function AddOpeningBalance()
    {
        $banks = Bank::OrderBy('name', 'asc')->get(['name', 'id']);
        return view('admin.accounts.opening_balance.add_opening', compact('banks'));
    }

    public function StoreOpeningBalance(Request $request)
    {

        $bank = new OpeningBalance();
        $bank->bank_id = $request->bank_id;
        $bank->amount = $request->total_amount;
        $bank->date = $request->date;
        $bank->status = '1';
        $bank->created_at = Carbon::now();
        $bank->save();

        $notification = array(
            'message' => 'Opening Balance Added Successfully!',
            'alert_type' => 'success',
        );

        return redirect()->route('all.opening.balance')->with($notification);
    }


    public function EditOpeningBalance($id)
    {
        $bankInfo = OpeningBalance::findOrFail($id);
        $banks = Bank::all();
        return view('admin.accounts.opening_balance.edit_opening', compact('bankInfo', 'banks'));
    }

    public function UpdateOpeningBalance(Request $request)
    {
        $bank = OpeningBalance::findOrFail($request->bank_id);
        $bank->bank_id = $request->bank_id;
        $bank->amount = $request->total_amount;
        $bank->date = $request->date;
        $bank->status = '1';
        $bank->update();

        $notification = array(
            'message' => 'Bank Opening Updated Successfully',
            'alert_type' => 'success'
        );

        return redirect()->route('all.opening.balance')->with($notification);
    }


    public function DeleteOpeningBalance($id)
    {
        OpeningBalance::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Opeining Balance Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.opening.balance')->with($notification);
    }



    // balance sheet
    public function BalanceSheet()
    {
        $accounts_amount = Bank::get();
        $accounts_receivable = Payment::get('due_amount', 'total_amount');
        $accounts_payable = SupplierAccount::get('due_amount');



        $assets = Asset::get(['type', 'name', 'price']);
        $assetCategory = AssetCategory::get();
        $liabilities = Liability::get(['head', 'amount']);
        $depreciation = AssetDepreciation::whereDate('date', '<=', date('Y-m-d'))->get(['amount']);

        $purchase = Purchase::get('total_amount');
        $expense = Expense::get('amount');
        return view('admin.report.balance_sheet', compact('accounts_amount', 'accounts_receivable', 'depreciation', 'accounts_payable', 'assets', 'liabilities', 'purchase', 'expense', 'assetCategory'));
    }


    // financial statement
    public function MonthlyStatement()
    {
        $sales = Payment::get();
        $purchase = Purchase::get();
        $expense = Expense::get();
        return view('admin.report.monthly_stament', compact('sales', 'expense', 'purchase'));
    }


    public function GetMonthlyStatement(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $sales = Payment::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
            $purchase = Purchase::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
            $expense = Expense::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();

            return view('admin.report.monthly_stament_report_result', compact('sales', 'purchase', 'expense', 'start_date', 'end_date'));
        }
    }


    // net profit
    public function NetProfit()
    {

        $expense = Expense::sum('amount');
        $salesProfitData = SalesProfit::get();
        $sales_amount = Payment::sum('total_amount');
        $purchaseAmount = 0;
        $sellingAmount = 0;
        $sellingDiscount = 0;
        foreach ($salesProfitData as $value) {
            $perProduct = $value->unit_price_purchase + $value->labour_cost;
            $purchaseAmount +=  $perProduct * $value->selling_qty;
            $sellingAmount += $value->unit_price_sales * $value->selling_qty;
            $sellingDiscount += $value->discount_per_unit * $value->selling_qty;
        }
        $salesProfit = $salesProfitData->sum('profit');
        $profit =  $salesProfit - $expense;
        return view('admin.accounts.profit.net_profit', compact('profit', 'expense', 'sales_amount', 'purchaseAmount', 'sellingAmount', 'salesProfit', 'sellingDiscount'));
    }


    public function NetSalesProfitResult(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;


        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $expense = Expense::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
            $salesProfit = SalesProfit::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }
        $profit =  $salesProfit->sum('profit') - $expense->sum('amount');

        return response()->json($profit);

        // return view('admin.accounts.profit.net_profit', compact('profit', 'startDate', 'endDate'));
        // return view('admin.accounts.profit.net_profit_result', compact('profit', 'startDate', 'endDate'));
    }
}
