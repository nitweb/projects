<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdvancedSalary;
use App\Models\Employee;
use App\Models\EmployeePayment;
use App\Models\EmployeePaymentDetails;
use App\Models\EmployeeSalaryLog;
use App\Models\OverTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeSalaryController extends Controller
{

    public function SalaryIncrement($id)
    {
        $allData = Employee::findOrFail($id);
        return view('admin.employee_page.salary.employee_salary_increment', compact('allData'));
    } //end method

    public function SalaryIncrementUpdate(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $previous_salary = $employee->salary;
        $present_salary = (float)$previous_salary + $request->increment_salary;
        $employee->salary = $present_salary;
        $employee->save();

        $salaryData = new EmployeeSalaryLog();
        $salaryData->emp_id = $id;
        $salaryData->previous_salary = $previous_salary;
        $salaryData->present_salary = $present_salary;
        $salaryData->increment_salary = $request->increment_salary;
        $salaryData->effected_salary = date('Y-m-d', strtotime($request->effected_salary));
        $salaryData->save();

        $notification = array(
            'message' => 'Employee Salary Increment Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('employee.salary.details', $id)->with($notification);
    } //end method

    public function SalaryDetails($id)
    {
        $employee = Employee::findOrFail($id);
        $salaryData = EmployeeSalaryLog::where('emp_id', $employee->id)->get();
        return view('admin.employee_page.salary.employee_salary_details', compact('salaryData', 'employee'));
    } //end method
}
