<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Packaging;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    // public function SupplierAllProduct()
    // {
    //     $supplierAll = Supplier::where('status', '1')->latest()->get();
    //     $title = 'Product';
    //     return view('admin.supplier.supplier_all', compact('supplierAll', 'title'));
    // } //end method
    // public function SupplierAllPackage()
    // {
    //     $supplierAll = Supplier::where('status', '0')->latest()->get();
    //     $title = 'Package';
    //     return view('admin.supplier.supplier_all', compact('supplierAll', 'title'));
    // } //end method

    public function SupplierAll()
    {
        $supplierAll = Supplier::latest()->get();
        $title = 'Suppliers';
        return view('admin.supplier.supplier_all', compact('supplierAll', 'title'));
    }

    public function SupplierAdd()
    {
        return view('admin.supplier.supplier_add');
    } //end method

    public function SupplierStore(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->mobile_no = $request->mobile_no;
        $supplier->address = $request->address;
        $supplier->status = $request->supplier_type;
        $supplier->created_by = Auth::user()->id;
        $supplier->created_at = Carbon::now();
        $supplier->save();

        $notification = array(
            'message' => 'Supplier Added Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('supplier.all')->with($notification);
    }

    public function SupplierEdit($id)
    {
        $supplierInfo = Supplier::findOrFail($id);
        return view('admin.supplier.supplier_edit', compact('supplierInfo'));
    }

    public function SupplierUpdate(Request $request)
    {
        $supplierId  =  $request->id;
        $supplier = Supplier::findOrFail($supplierId);
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->mobile_no = $request->mobile_no;
        $supplier->address = $request->address;
        $supplier->status = $request->supplier_type;
        $supplier->updated_by = Auth::user()->id;
        $supplier->updated_at = Carbon::now();
        $supplier->update();


        $notification = array(
            'message' => 'Supplier Updated Successfully',
            'alert-type' => 'success',
        );

        if ($request->supplier_type == 1) {
            return redirect()->route('supplier.all.product')->with($notification);
        } else {
            return redirect()->route('supplier.all.package')->with($notification);
        }
    } //end method

    public function SupplierDelete($id)
    {

        Supplier::findOrFail($id)->delete();
        Purchase::where('supplier_id', $id)->delete();


        $notification = array(
            'message' => 'Supplier Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }




    // supplier purchase
    public function SupplierBill($id)
    {
        $supplier = Supplier::findOrFail($id);
        if ($supplier->status == '0') {
            $purchaseData = Packaging::orderBy('date', 'desc')->orderBy('package_no', 'desc')->where('supplier_id', $id)->get();
        } else {
            $purchaseData = Purchase::orderBy('date', 'desc')->orderBy('purchase_no', 'desc')->where('supplier_id', $id)->get();
        }
        return view('admin.supplier.supplier_invoice', compact('purchaseData', 'id'));
    }

    public function SupplierAccountDetails($id)
    {
        $accountDetails = SupplierAccount::orderBy('date', 'asc')->where('supplier_id', $id)->get();
        $supplierInfo = Supplier::where('id', $id)->first();
        return view('admin.supplier.supplier_account_details', compact('accountDetails', 'supplierInfo'));
    }

    public function SupplierAccountDetailReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $supplier_id = $request->supplier_id;

        if ($start_date == null && $end_date == null) {
            $billDetails = SupplierAccount::all();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $billDetails = SupplierAccount::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])->where('supplier_id', $request->supplier_id)
                ->get();
        }
        return view('admin.report.supplier_account_detials_report', compact('billDetails', 'start_date', 'end_date', 'supplier_id'));
    }
}
