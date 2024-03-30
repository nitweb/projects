<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Advanced;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Employee;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdvancedController extends Controller
{
    public function AllAdvancedSalary()
    {
        $allAdvanced = Advanced::all();
        return view('admin.salary.advanced_salary.all_advanced', compact('allAdvanced'));
    }

    public function AddAdvancedSalary()
    {
        $accounts = Bank::OrderBy('name', 'asc')->get();
        $employees = Employee::orderBy('name', 'desc')->get();
        $years = [];
        $currentYear = date('Y');
        for ($year = 1900; $year <= $currentYear; $year++) {
            $years[$year] = $year;
        }
        return view('admin.salary.advanced_salary.add_advanced', compact('employees', 'accounts', 'years'));
    }

    public function EditAdvancedSalary($id)
    {
        $employees = Employee::all();
        $advancedSalary = Advanced::findOrFail($id);
        $years = [];
        for ($year = 1900; $year <= 2015; $year++) {
            $years[$year] = $year;
        }
        return view('admin.salary.advanced_salary.edit_advanced', compact('advancedSalary', 'employees', 'years'));
    }

    public function StoreAdvancedSalary(Request $request)
    {
        // dd($request->all());

        $date = Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($request->date)));
        $monthName = $date->format('F');
        $year = $request->year;

        $advancedSalary = Advanced::where('month', $request->month)
            ->where('year', $request->year)
            ->where('employee_id', $request->employee_id)
            ->first();

        $employee = Employee::findOrFail($request->employee_id);
        $bank_id = $request->bank_id;
        $bank = Bank::findOrFail($bank_id);
        if ($advancedSalary === NULL) {
            if ($bank->balance >=  $request->advanced_amount) {
                $advanced = new Advanced();
                $advanced->advance_amount = $request->advanced_amount;
                $advanced->employee_id = $request->employee_id;
                $advanced->date = $request->date;
                $advanced->month = $request->month;
                $advanced->year = $year;
                $advanced->created_at = Carbon::now();
                $advanced->save();

                $bank->balance += (float) $request->advanced_amount;
                $bank->update();

                $bankDetails = new BankDetail();
                $bankDetails->bank_id = $bank_id;
                $bankDetails->trans_type = 'expense';
                $bankDetails->trans_head = 'Salary Advance';
                $bankDetails->trans_id = 'Adv-' . $request->employee_id;
                $bankDetails->balance = $request->advanced_amount;
                $bankDetails->status = '0';
                $bankDetails->date = $request->date;
                $bankDetails->created_at = Carbon::now();
                $bankDetails->save();

                // save data to expense
                $expense = new Expense();
                $expense->head = 'Advance Salary';
                $expense->description = 'Advance Salary Paid to' . $employee->name;
                $expense->date = $request->date;
                $expense->bank_id = $bank_id;
                $expense->amount = $request->advanced_amount;
                $expense->status = '0';
                $expense->created_at = Carbon::now();
                $expense->save();

                $notification = array(
                    'message' => 'Advanced Salary Added Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('all.advanced.salary')->with($notification);
            } else {
                $notification = array(
                    'message' => 'Insufficient Amount on selected Account!',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        } else {
            $notification = array(
                'message' => 'Advanced Salary Already Paid!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }



    public function UpdateAdvancedSalary(Request $request)
    {



        $advanced_id = $request->id;

        $date = Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($request->date)));
        $year = $date->format('Y');

        Advanced::findOrFail($advanced_id)->update([
            'advance_amount' => $request->advanced_amount,
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'year' => $year,
            'date' => $request->date,
        ]);

        $notification = array(
            'message' => 'Advanced Updated Successfully',
            'alert-type' => 'success',
        );
        return redirect()->route('all.advanced.salary')->with($notification);
    }

    public function DeleteAdvancedSalary($id)
    {
        Advanced::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Advanced Salary Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.advanced.salary')->with($notification);
    }
}
