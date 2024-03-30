<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Advanced;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Bonus;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Overtime;
use App\Models\PaySalary;
use App\Models\PaySalaryDetail;
use App\Models\salesComissionMeta;
use App\Models\salesComissionPayment;
use App\Models\SalesCommission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberToWords\Legacy\Numbers\Words\Locale\Id;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class SalaryController extends Controller
{
    public function PaySalary()
    {
        // check request year
        $year = $this->checkRequestYear();
        $employees = Employee::latest()->get();
        return view('admin.salary.pay_salary.pay_salary', compact('employees', 'year'));
    }

    public function PaySalaryNow($id)
    {
        // check request year
        $year = $this->checkRequestYear();
        $employee = Employee::findOrFail($id);

        $employeeSalesCommission = SalesCommission::where('employee_id', $id)->first();

        $advanced = Advanced::where('employee_id', $id)
            ->where('month', date('F', strtotime('-1 month')))
            ->where('year', $year)
            ->first();
        $accounts = Bank::OrderBy('name', 'asc')->get();


        if ($advanced == null) {
            $advanced_amount = 0;
        } else {
            $advanced_amount = $advanced->advance_amount;
        }

        if ($employeeSalesCommission == null) {
            $salesCommission = 0;
        } else {
            $salesCommission = $employeeSalesCommission->total;
        }

        $overtime = Overtime::where('employee_id', $employee->id)
            ->where('month', date('F', strtotime('-1 month')))
            ->where('year', $year)
            ->get();


        $bonus = Bonus::where('employee_id', $employee->id)
            ->where('month', date('F', strtotime('-1 month')))
            ->where('year', $year)
            ->first();

        $pay_salary = PaySalaryDetail::where('employee_id', $employee->id)
            ->where('paid_month', date('F', strtotime('-1 month')))
            ->where('paid_year', $year)
            ->get();


        if ($pay_salary->isEmpty()) {
            if ($employee != null && !$overtime->isEmpty() && $bonus != null) {
                $total_salary = $employee->salary + $overtime->sum('ot_amount') + $bonus->bonus_amount;
            } elseif ($employee != null && $overtime->isEmpty() && $bonus == null) {
                $total_salary = $employee->salary;
            } elseif ($employee != null && $overtime->isEmpty() && $bonus != null) {
                $total_salary = $employee->salary + $bonus->bonus_amount;
            } elseif ($employee != null && !$overtime->isEmpty() && $bonus == null) {
                $total_salary = $employee->salary + $overtime->sum('ot_amount');
            }
        } else {
            if ($employee != null && !$overtime->isEmpty() && $bonus != null) {
                $total_salary = $employee->salary + $overtime->sum('ot_amount') +  $bonus->bonus_amount - $pay_salary->sum('paid_amount');
            } elseif ($employee != null && !$overtime->isEmpty() && $bonus == null) {
                $total_salary = $employee->salary + $overtime->sum('ot_amount') - $pay_salary->sum('paid_amount');
            } elseif ($employee != null &&  $overtime->isEmpty()  && $bonus != null) {
                $total_salary = $employee->salary + $bonus->bonus_amount - $pay_salary->sum('paid_amount');
            } elseif ($employee != null &&  $overtime->isEmpty()  && $bonus == null) {
                $total_salary = $employee->salary - $pay_salary->sum('paid_amount');
            }
        }


        $total_salary = $total_salary - $advanced_amount;
        // dd($total_salary);

        return view('admin.salary.pay_salary.pay_salary_add', compact('employee', 'total_salary', 'salesCommission', 'advanced_amount', 'accounts'));
    }

    public function StorePaySalary(Request $request)
    {

        // dd($request->all());

        // check request year
        $year = $this->checkRequestYear();
        $employee_id = $request->employee_id;
        $bank_id = $request->bank_id;
        $employee = Employee::findOrFail($employee_id);

        $pay_salary_table = PaySalary::where('employee_id', $employee_id)
            ->where('paid_month', date('F', strtotime('-1 month')))
            ->where('paid_year', $year)
            ->get();

        $bank = Bank::findOrFail($bank_id);
        if ($request->sales_commission != null) {
            $total = $request->grand_total;
        } else {
            $total = $request->total_salary;
        }
        // dd($total);
        if ($request->paid_amount > $total) {
            $notification = array(
                'message' => 'Paid Amount must be less than or equal to  Salary',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        } else {

            if ($request->paid_amount > $bank->balance) {
                $notification = array(
                    'message' => 'Insufficient Amount on selected Account!',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            } else {


                $paid_salary_details = new PaySalaryDetail();
                $paid_salary_details->employee_id = $employee_id;
                $paid_salary_details->bank_id = $bank_id;

                $paid_salary_details->paid_month = date('F', strtotime('-1 month'));
                $paid_salary_details->paid_year = $year;
                $paid_salary_details->note = $request->note;
                $paid_salary_details->paid_date = $request->date;


                // save data to expense
                $expense = new Expense();
                $expense->head = 'Salary';
                $expense->description = 'Paid to ' . $employee->name;
                $expense->date = $request->date;
                $expense->bank_id = $bank_id;
                $expense->status = '0';
                $expense->created_at = Carbon::now();


                $commisionAmount = 0;
                if ($request->paid_amount > $request->total_salary) {
                    $paid_salary_details->paid_amount = $request->total_salary;
                    $new_amount = $request->paid_amount - $request->total_salary;
                    $commisionAmount += $new_amount;
                    $expense->amount = $request->total_salary;
                } else {
                    $paid_salary_details->paid_amount = $request->paid_amount;
                    $expense->amount = $request->paid_amount;
                }

                $paid_salary_details->save();
                $expense->save();

                if ($commisionAmount > 0) {
                    $salesCommission = SalesCommission::where('employee_id', $employee_id)->first();
                    $salesCommission->total -= $commisionAmount;
                    $salesCommission->update();

                    $salesCommissionPayment = new salesComissionPayment();
                    $salesCommissionPayment->employee_id = $employee_id;
                    $salesCommissionPayment->amount = $commisionAmount;
                    $salesCommissionPayment->date = $request->date;
                    $salesCommissionPayment->created_at = Carbon::now();
                    $salesCommissionPayment->save();

                    $salesCommissionMeta = salesComissionMeta::where('employee_id', $employee_id)->get();

                    $extraAmount = $commisionAmount; //1000
                    foreach ($salesCommissionMeta as $commision) {
                        if ($extraAmount >= $commision->amount) {
                            $commision->amount = 0;
                            $commision->update();
                            $extraAmount = $extraAmount - $commision->amount;
                        } else {
                            $commision->amount -= $extraAmount;
                            $commision->update();
                            break;
                        }
                    }

                    // save data to expense
                    $expenseComission = new Expense();
                    $expenseComission->head = 'Sales Commission';
                    $expenseComission->description = 'Paid to ' . $employee->name;
                    $expenseComission->date = $request->date;
                    $expenseComission->bank_id = $bank_id;
                    $expenseComission->status = '0';
                    $expenseComission->amount = $commisionAmount;
                    $expenseComission->created_at = Carbon::now();
                    $expenseComission->save();
                }



                $bank->balance += (float) $request->paid_amount;
                $bank->update();

                $bankDetails = new BankDetail();
                $bankDetails->bank_id = $bank_id;
                $bankDetails->trans_type = 'expense';
                $bankDetails->trans_head = 'Salary';
                $bankDetails->trans_id = 'Salary-' . $employee_id;
                $bankDetails->balance = $request->paid_amount;
                $bankDetails->status = '0';
                $bankDetails->date = $request->date;
                $bankDetails->created_at = Carbon::now();
                $bankDetails->save();




                if ($pay_salary_table->isEmpty()) {
                    $paid_salary = new PaySalary();
                    $paid_salary->employee_id = $employee_id;
                    $paid_salary->paid_month = date('F', strtotime('-1 month'));
                    $paid_salary->paid_year = $year;
                    $paid_salary->created_at = Carbon::now();
                    $paid_salary->save();
                }

                $notification = array(
                    'message' => 'Paid Amount Added Successfully!',
                    'alert-type' => 'success'
                );
            }
        }

        return redirect()->route('pay.salary')->with($notification);
    }


    // add salary by employee
    public function AddSalary()
    {
        $employees = Employee::latest()->get();
        return view('admin.salary.pay_salary.add_salary', compact('employees'));
    }

    // all overtime method
    public function AllOvertime()
    {
        $allOvertime = Overtime::all();
        return view('admin.salary.overtime.all_overtime', compact('allOvertime'));
    }

    public function AddOvertime()
    {
        $years = [];
        $currentYear = date('Y');
        for ($year = 1900; $year <= $currentYear; $year++) {
            $years[$year] = $year;
        }
        $employees = Employee::orderBy('name', 'desc')->get();
        return view('admin.salary.overtime.add_overtime', compact('employees', 'years'));
    }
    public function StoreOvertime(Request $request)
    {
        $employeeSalary = Employee::where('id', $request->employee_id)->first()['salary'];

        $ot_hour_amount =  ($employeeSalary / 30) / 9;
        $ot_amount = round($ot_hour_amount * $request->ot_hour);
        $overtime = new Overtime();
        $overtime->employee_id = $request->employee_id;
        $overtime->ot_hour = $request->ot_hour;
        $overtime->ot_amount = $ot_amount;
        $overtime->month = $request->month;
        $overtime->year = $request->year;
        $overtime->date = $request->date;
        $overtime->created_at = Carbon::now();
        $overtime->save();


        $notification = array(
            'message' => 'Overtime Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.overtime')->with($notification);
    }

    public function UpdateOvertime(Request $request)
    {


        $overtime_id = $request->id;

        $date = Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($request->date)));
        $monthName = $date->format('F');
        $year = $date->format('Y');
        $employeeSalary = Employee::where('id', $request->employee_id)->first()['salary'];

        $ot_hour_amount =  ($employeeSalary / 30) / 9;
        $ot_amount = round($ot_hour_amount * $request->ot_hour);


        Overtime::findOrFail($overtime_id)->update([
            'employee_id' => $request->employee_id,
            'ot_hour' => $request->ot_hour,
            'ot_amount' => $ot_amount,
            'month' => $monthName,
            'year' => $year,
        ]);

        $notification = array(
            'message' => 'Overtime Updated Successfully',
            'alert-type' => 'success',
        );
        return redirect()->route('all.overtime')->with($notification);
    }
    public function EditOvertime($id)
    {
        $overtimeInfo = Overtime::findOrFail($id);
        $employees = Employee::orderBy('name', 'desc')->get();
        return view('admin.salary.overtime.edit_overtime', compact('overtimeInfo', 'employees'));
    }

    public function DeleteOvertime($id)
    {
        Overtime::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Overtime Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.overtime')->with($notification);
    }


    // all bonud method
    public function AllBonus()
    {
        $allBonus = Bonus::all();
        return view('admin.salary.bonus.all_bonus', compact('allBonus'));
    }

    public function AddBonus()
    {
        $years = $this->getYears();
        $employees = Employee::orderBy('name', 'desc')->get();
        return view('admin.salary.bonus.add_bonus', compact('employees', 'years'));
    }
    public function StoreBonus(Request $request)
    {
        $date = Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($request->date)));
        $monthName = $date->format('F');
        $year = $date->format('Y');

        $bonusSalary = Bonus::where('month', $request->month)->where('employee_id', $request->employee_id)->first();

        if ($bonusSalary === NULL) {
            $bonus = new Bonus();
            $bonus->employee_id = $request->employee_id;
            $bonus->bonus_amount = $request->bonus_amount;
            $bonus->month = $request->month;
            $bonus->year = $request->year;
            $bonus->date = $request->date;
            $bonus->created_at = Carbon::now();
            $bonus->save();


            $notification = array(
                'message' => 'Bonus Added Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.bonus')->with($notification);
        } else {
            $notification = array(
                'message' => 'Bonus Salary Already Added!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function UpdateBonus(Request $request)
    {
        $bonus_id = $request->id;

        $date = Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($request->date)));
        $monthName = $date->format('F');
        $year = $date->format('Y');
        $employeeSalary = Employee::where('id', $request->employee_id)->first()['salary'];



        Bonus::findOrFail($bonus_id)->update([
            'employee_id' => $request->employee_id,
            'bonus_amount' => $request->bonus_amount,
            'month' => $monthName,
            'year' => $year,
        ]);

        $notification = array(
            'message' => 'Bonus Updated Successfully',
            'alert-type' => 'success',
        );
        return redirect()->route('all.bonus')->with($notification);
    }
    public function EditBonus($id)
    {
        $bonusInfo = Bonus::findOrFail($id);
        $employees = Employee::orderBy('name', 'desc')->get();
        return view('admin.salary.bonus.edit_bonus', compact('bonusInfo', 'employees'));
    }

    public function DeleteBonus($id)
    {
        Bonus::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Bonus Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.bonus')->with($notification);
    }



    // payment details method
    public function EmployeePaymentDetails($id)
    {
        $employee = Employee::findOrFail($id);
        $year = $this->checkRequestYear();
        $payment_salary = PaySalary::where('employee_id', $id)
            ->get();
        return view('admin.salary.pay_salary.payment_details', compact('employee', 'payment_salary', 'year'));
    }


    public function checkRequestYear()
    {
        $reqetstMonth = date('F', strtotime('-1 month'));
        if ($reqetstMonth == 'December') {
            $year = date('Y', strtotime('-1 year'));
        } else {
            $year = date('Y');
        }
        return $year;
    }

    public function getYears()
    {
        $years = [];
        $currentYear = date('Y');
        for ($year = 1900; $year <= $currentYear; $year++) {
            $years[$year] = $year;
        }
        return $years;
    }
}
