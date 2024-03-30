<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\PurchaseMeta;
use App\Models\Liability;
use App\Models\Product;
use App\Models\SupplierAccount;
use App\Models\Asset;
use App\Models\Bank;
use App\Models\Employee;
use App\Models\salesComissionMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function EmployeeLedger()
    {
        $employees = Employee::OrderBy('name', 'asc')->get();
        $title = 'Employee Sales Ledger';
        return view('admin.employee_page.employee_ledger', compact('employees', 'title'));
    }
    public function EmployeeSalesReport($id)
    {
        $title = 'Employee Sales Report';
        $salesReport = salesComissionMeta::where('sales_comission_metas.employee_id', $id)
            ->join('payments', 'sales_comission_metas.invoice_id', '=', 'payments.invoice_id')
            ->get(['payments.*']);
        return view('admin.employee_page.employee_sales_report', compact('salesReport', 'title'));
    }
    public function CategoryReport()
    {
        $categories = Category::all();
        $invoice = Invoice::all();
        return view('admin.report.category_wise_report', compact('categories', 'invoice'));
    }


    public function GetCategoryReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $category_id = $request->category_id;
        $categories = Category::all();
        $report_type = $request->report_type;
        // dd($report_type);
        if ($report_type == 'sales') {
            if ($start_date && $end_date && $category_id) {
                $startDate = Carbon::parse($start_date)->toDateTimeString();
                $endDate = Carbon::parse($end_date)->toDateTimeString();
                $allSearchResult = InvoiceDetail::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                    ->where('category_id', $category_id)
                    ->get();
            }
        } elseif ($report_type == 'purchase') {
            if ($start_date && $end_date && $category_id) {
                $startDate = Carbon::parse($start_date)->toDateTimeString();
                $endDate = Carbon::parse($end_date)->toDateTimeString();
                $allSearchResult = PurchaseMeta::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                    ->where('category_id', $category_id)
                    ->get();
            }
        }
        // dd($allSearchResult);

        return view('admin.report.category_wise_report_result', compact('categories', 'category_id', 'allSearchResult', 'start_date', 'end_date', 'report_type'));
    }

    public function GetCategoryReportSummary()
    {
        $categories = Category::all();
        return view('admin.report.category_wise_report_summary', compact('categories'));
    }

    public function PrintCategorySummary(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $categories = Category::all();
        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allSearchResult = InvoiceDetail::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.report.category_wise_report_summary_print', compact('categories', 'allSearchResult', 'start_date', 'end_date',));
    }


    public function PurchaseReport()
    {
        $allPurchase = Purchase::latest()->get();
        return view('admin.report.purchase_report', compact('allPurchase'));
    }

    public function GetPurchaseReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // dd($start_date,$end_date,$interest_subject);
        $current_month = date('m');
        $current_year = date('Y');

        if ($start_date == null && $end_date == null) {
            $allPurchase = Purchase::latest()->get();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allPurchase = Purchase::whereBetween('date', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.report.search_purchase_report_result', compact('allPurchase', 'start_date', 'end_date'));
    }

    public function ExpenseReport()
    {
        $allExpense = Expense::latest()->get();
        return view('admin.report.expense_report', compact('allExpense'));
    }

    public function GetExpenseReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // dd($start_date,$end_date,$interest_subject);
        $current_month = date('m');
        $current_year = date('Y');

        if ($start_date == null && $end_date == null) {
            $allPurchase = Expense::latest()->get();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allExpense = Expense::whereBetween('date', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.report.search_expense_report_result', compact('allExpense', 'start_date', 'end_date'));
    }


    public function ProductWiseSalesReport()
    {
        $allProductDetails = InvoiceDetail::latest()->get();
        return view('admin.report.product_wise_sales_report', compact('allProductDetails'));
    }

    public function GetProductWiseSalesReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // dd($start_date,$end_date,$interest_subject);
        $current_month = date('m');
        $current_year = date('Y');

        if ($start_date == null && $end_date == null) {
            $allPurchase = InvoiceDetail::latest()->get();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allProductDetails = InvoiceDetail::whereBetween('date', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.report.search_product_wise_sales_report_result', compact('allProductDetails', 'start_date', 'end_date'));
    }

    public function CustomerDueReport()
    {
        $allCustomerDue = Payment::whereIn('paid_status', ['partial_paid', 'full_due'])->where('due_amount', '!=', '0')->latest()->get();

        return view('admin.report.customer_due_report', compact('allCustomerDue'));
    }

    public function GetCustomerDueReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // dd($start_date,$end_date,$interest_subject);
        $current_month = date('m');
        $current_year = date('Y');

        if ($start_date == null && $end_date == null) {
            $allCustomerDue = Payment::latest()->get();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allCustomerDue = Payment::whereIn('paid_status', ['partial_paid', 'full_due'])->where('due_amount', '!=', '0')->whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.report.search_customer_due_report_result', compact('allCustomerDue', 'start_date', 'end_date'));
    }

    public function WarehouseStockReport()
    {
        $allWarehouseStock = Payment::whereIn('paid_status', ['partial_paid', 'full_due'])->where('due_amount', '!=', '0')->latest()->get();

        return view('admin.report.customer_due_report', compact('allWarehouseStock'));
    }

    public function GetWarehouseStockReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // dd($start_date,$end_date,$interest_subject);
        $current_month = date('m');
        $current_year = date('Y');

        if ($start_date == null && $end_date == null) {
            $allCustomerDue = Payment::latest()->get();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allCustomerDue = Payment::whereIn('paid_status', ['partial_paid', 'full_due'])->where('due_amount', '!=', '0')->whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.report.search_customer_due_report_result', compact('allCustomerDue', 'start_date', 'end_date'));
    }

    public function CustomerReport()
    {
        $allCustomer = Customer::latest()->get();

        return view('admin.report.customer_report', compact('allCustomer'));
    }

    public function WarehouseReport()
    {
        $allWarehouse = Warehouse::latest()->get();

        return view('admin.report.warehouse_report', compact('allWarehouse'));
    }


    public function NetProfitReport()
    {



        // $products = SalesProfit::select("*")
        //     ->whereMonth('date', '02')
        //     ->whereYear('date', '2023')
        //     ->get();

        // dd($products);
        $sales = Payment::get();
        $purchase = Purchase::get();
        $expense = Expense::get();
        return view('admin.report.net_profit', compact('sales', 'expense', 'purchase'));
    }

    public function GetProfitReport(Request $request)
    {
    }
}
