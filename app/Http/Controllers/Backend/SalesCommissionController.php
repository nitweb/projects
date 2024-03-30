<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\commissionDetail;
use App\Models\commissionTarget;
use App\Models\Employee;
use App\Models\SalesCommission;
use Carbon\Carbon;
use Illuminate\Http\Request;


class SalesCommissionController extends Controller
{
    public function AllCommission()
    {
        $title = 'Commission';
        $allCommission = SalesCommission::latest()->get();
        return view('admin.sales_commission.all_sales_commission', compact('allCommission', 'title'));
    }

    public function TargetCommission()
    {
        $title = 'Show Commission Target';
        $allCommissionTarget = commissionTarget::where('status', '1')->latest()->get();
        return view('admin.sales_commission.all_commission_target', compact('allCommissionTarget', 'title'));
    }
    public function AddCommission()
    {
        $allEmployee = Employee::OrderBy('name', 'asc')->get();
        $categories = Category::latest()->get();
        return view('admin.sales_commission.add_sales_commission', compact('allEmployee', 'categories'));
    }

    public function StoreCommission(Request $request)
    {

        $existingEmp = SalesCommission::where('employee_id', $request->employee_id)->first();
        if ($existingEmp != null) {
            for ($i = 0; $i < count($request->category_id); $i++) {
                $commissionTarget = new commissionTarget();
                $commissionTarget->employee_id = $request->employee_id;
                $commissionTarget->category_id = $request->category_id[$i];
                $commissionTarget->sales_target = $request->sales_target[$i];
                $commissionTarget->sales_commission = $request->sales_commission[$i];
                $commissionTarget->created_at = Carbon::now();

                $checkCategory = commissionTarget::where('employee_id', $commissionTarget->employee_id)
                    ->where('category_id', $commissionTarget->category_id)
                    ->where('status', '1')
                    ->get();

                if (count($checkCategory) >= 1) {
                    $notification = array(
                        'message' => 'Selected Category Already Exist on the selected employee',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                } else {
                    $commissionTarget->save();
                }
            }

            $notification = array(
                'message' => 'Employee Commission Added Successfully',
                'alert_type' => 'success'
            );
            return redirect()->route('target.commission')->with($notification);
        } else {
            $salesCommission = new SalesCommission();
            $salesCommission->employee_id = $request->employee_id;
            $salesCommission->total = '0';
            $salesCommission->created_at = Carbon::now();
            $salesCommission->save();

            // save data to commission target db
            for ($i = 0; $i < count($request->category_id); $i++) {
                $commissionTarget = new commissionTarget();
                $commissionTarget->employee_id = $request->employee_id;
                $commissionTarget->category_id = $request->category_id[$i];
                $commissionTarget->sales_target = $request->sales_target[$i];
                $commissionTarget->sales_commission = $request->sales_commission[$i];
                $commissionTarget->created_at = Carbon::now();

                $checkCategory = commissionTarget::where('employee_id', $request->employee_id)
                    ->where('category_id', $commissionTarget->category_id)
                    ->where('status', '1')
                    ->get();
                if (count($checkCategory) > 1) {
                    $notification = array(
                        'message' => 'Selected Category Already Exist on the selected employee',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                } else {
                    $commissionTarget->save();
                }
            }

            $notification = array(
                'message' => 'Employee Commission Added Successfully',
                'alert_type' => 'success'
            );
            return redirect()->route('target.commission')->with($notification);
        }
    }



    public function EditCommission($id)
    {
        $comissionInfo = commissionTarget::findOrFail($id);
        $title = 'Sales Comission Target Update';
        $allEmployee = Employee::OrderBy('name', 'asc')->get();
        $categories = Category::OrderBy('name', 'asc')->get();
        return view('admin.sales_commission.edit_sales_commission', compact('title', 'comissionInfo', 'categories', 'allEmployee'));
    }

    public function UpdateCommission(Request $request)
    {
        $commisionId = $request->id;
        $commissionTarget = commissionTarget::findOrFail($commisionId);
        $commissionTarget->employee_id = $request->employee_id;
        $commissionTarget->category_id = $request->category_id;
        $commissionTarget->sales_target = $request->sales_target;
        $commissionTarget->sales_commission = $request->sales_commission;
        $commissionTarget->update();

        $notification = array(
            'message' => 'Employee Commission Updated Successfully!',
            'alert_type' => 'success'
        );
        return redirect()->route('target.commission')->with($notification);
    }
    public function HistoryCommission($id)
    {
        $comissionHistory = commissionDetail::where('employee_id', $id)->get();
        $title = 'Sales Comission History';
        return view('admin.sales_commission.history_sales_commission', compact('title', 'comissionHistory'));
    }


    public function DeleteCommission($id)
    {
        $commisionTarget = commissionTarget::findORFail($id);
        commissionDetail::where('employee_id', $commisionTarget->employee_id)->delete();
        $commisionTarget->delete();

        $notification = array(
            'message' => 'Employee Comission Deleted Successfully!',
            'alert_type' => 'success'
        );
        return redirect()->route('target.commission')->with($notification);
    }


    public function DeactiveCommission($id)
    {
        $commissionTarget = commissionTarget::findORFail($id);
        $commissionTarget->status = '0';
        $commissionTarget->update();

        $notification = array(
            'message' => 'Employee Comission Deactivte Successfully!',
            'alert_type' => 'success'
        );
        return redirect()->route('target.commission')->with($notification);
    }
}
