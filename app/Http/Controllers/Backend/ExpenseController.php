<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Employee;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
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
        // dd($request->all());

        $bank_id = $request->bank_id;
        $bank = Bank::findOrFail($bank_id);
        $totalAmount = $request->amount + $request->charge;

        if ($totalAmount >= $bank->balance) {
            $notification = array(
                'message' => 'Error, Insufficient Balance for selected bank!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        } else {
            $expense = new Expense();
            if ($request->expense_head == 'Other') {
                $expense->head = $request->others;
            } else {
                $expense->head = $request->expense_head;
            }

            $expense->amount = $request->amount;
            $expense->charge = $request->charge;
            $expense->date = $request->date;
            $expense->description = $request->description;
            $expense->note = $request->note;
            $expense->bank_id = $bank_id;
            $expense->created_at = Carbon::now();
            $expense->save();


            $bank->balance -= (float) $totalAmount;
            $bank->update();

            $bankDetails = new BankDetail();
            $bankDetails->bank_id = $request->bank_id;
            $bankDetails->trans_type = 'expense';
            $bankDetails->trans_head = $request->description;
            $bankDetails->trans_id = 'ex-' . $expense->id;
            $bankDetails->balance = $totalAmount;
            $bankDetails->status = '0';
            $bankDetails->date = $request->date;
            $bankDetails->created_at = Carbon::now();
            $bankDetails->save();

            $notification = array(
                'message' => 'Expense Addedd Successfully',
                'alert_type' => 'success'
            );
            return redirect()->route('all.expense')->with($notification);
        }
    }


    public function EditExpense($id)
    {
        $expenseInfo = Expense::findOrFail($id);
        $banks = Bank::OrderBy('name', 'asc')->get();
        return view('admin.accounts.expense_page.edit_expense', compact('expenseInfo', 'banks'));
    }

    public function UpdateExpense(Request $request)
    {

        $bank_id = $request->bank_id;
        $bank = Bank::findOrFail($bank_id);
        $totalAmount = $request->amount + $request->charge;

        if ($totalAmount >= $bank->balance) {
            $notification = array(
                'message' => 'Error, Insufficient Balance for selected bank!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        } else {

            $expense = Expense::findOrFail($request->id);
            $current_paid = $expense->amount + $expense->charge;
            // $bankInfo->balance += $expense->amount;
            // $bankInfo->update();

            function bankUpdate($bank_id, $expense, $amount)
            {
                $bank = Bank::findOrFail($bank_id);
                $bankInfo = Bank::findOrFail($expense->bank_id);
                $total = $expense->amount + $expense->charge;
                if ($bank_id == $expense->bank_id) {
                    if ($amount > $total) {
                        $existAmount =  $amount - $total;
                        $bankInfo->balance  =  $bankInfo->balance + $existAmount;
                        $bankInfo->update();
                    } else {
                        $existAmount = $total - $amount;
                        $bankInfo->balance  =  $bankInfo->balance - $existAmount;
                        $bankInfo->update();
                    }
                } else {
                    // update previous transaction
                    $bankInfo->balance  =  $bankInfo->balance + (float) $amount;
                    $bankInfo->update();

                    // update new transaction
                    $bank->balance -= (float) $total;
                    $bank->update();
                }
            }


            if ($request->expense_head == 'Other') {
                $expense->head = $request->others;
            } else {
                $expense->head = $request->expense_head;
            }

            $expense->amount = $request->amount;
            $expense->charge = $request->charge;
            $expense->date = $request->date;
            $expense->description = $request->description;
            $expense->note = $request->note;


            bankUpdate($bank_id, $expense, $current_paid);



            $bankDetails = BankDetail::where('bank_id', $expense->bank_id)
                ->where('trans_id', 'ex-' . $expense->id)
                ->first();
            $bankDetails->bank_id = $request->bank_id;
            $bankDetails->balance = $totalAmount;
            $bankDetails->date = $request->date;
            $bankDetails->updated_at = Carbon::now();
            $bankDetails->update();
            $expense->bank_id = $bank_id;
            $expense->update();

            $notification = array(
                'message' => 'Expense updated Successfully',
                'alert_type' => 'success'
            );
            return redirect()->route('all.expense')->with($notification);
        }
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

        // dd($start_date,$end_date,$interest_subject);

        if ($start_date == null && $end_date == null) {
            $allExpense = Expense::paginate(2);
        }

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
}
