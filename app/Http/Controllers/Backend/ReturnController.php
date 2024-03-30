<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ReturnProduct;
use App\Models\Supplier;
use App\Models\SupplierReplaceHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ReturnController extends Controller
{
    public function CustomerAllReplace()
    {
        $title = 'Product Replace Customer List';
        $customers = Customer::get(['name', 'id']);
        return view('admin.return_product.all_customer_product_replace', compact('customers', 'title'));
    }

    public function CustomerReplaceHistory($id)
    {
        $replaceHistory = ReturnProduct::where('customer_id', $id)->get();
        $customer = Customer::findOrFail($id);
        return view('admin.return_product.customer_replace_product_history', compact('replaceHistory', 'customer'));
    }
    public function InHouseReplace()
    {
        $title = 'In House Replace Product List';
        $returnAll = ReturnProduct::where('status', '0')->get();
        return view('admin.return_product.all_house_product_list', compact('returnAll', 'title'));
    }
    public function AllReplaceList()
    {
        $title = 'In House Replace Product List';
        $returnAll = ReturnProduct::get();
        return view('admin.return_product.all_replace_product_list', compact('returnAll', 'title'));
    }

    public function AddReplaceProduct()
    {
        $customers = Customer::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $return_no = $this->UniqueNumber();
        return view('admin.return_product.add_return_product', compact('customers', 'return_no', 'products'));
    }

    public function StoreReplaceProduct(Request $request)
    {

        // save data to return product table
        $returnProduct = new ReturnProduct();
        $returnProduct->return_no = $request->return_no;
        $returnProduct->customer_id = $request->customer_id;
        $returnProduct->product_id = $request->product_id;
        $returnProduct->quantity = $request->quantity;
        $returnProduct->date = $request->date;
        $returnProduct->created_at = Carbon::now();
        $returnProduct->save();

        $notification = array(
            'message' => 'Replace Product Added Successfully!',
            'alert_type' => 'success',
        );
        return redirect()->route('in.house.replace.list')->with($notification);
    }

    public function EditReplaceProduct($id)
    {
        $customers = Customer::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $returnProduct = ReturnProduct::findOrFail($id);
        return view('admin.return_product.edit_return_product', compact('returnProduct', 'products', 'customers'));
    }

    public function UpdateReplaceProduct(Request $request)
    {
        $return_id = $request->id;
        $returnProduct = ReturnProduct::findOrFail($return_id);
        $returnProduct->return_no = $request->return_no;
        $returnProduct->customer_id = $request->customer_id;
        $returnProduct->product_id = $request->product_id;
        $returnProduct->quantity = $request->quantity;
        $returnProduct->date = $request->date;
        $returnProduct->update();

        $notification = array(
            'message' => 'Replace Product Updated Successfully!',
            'alert_type' => 'success',
        );
        return redirect()->route('in.house.replace.list')->with($notification);
    }


    public function DeleteReplaceProduct($id)
    {
        ReturnProduct::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Replace Product Deleted Successfully!',
            'alert_type' => 'success',
        );
        return redirect()->route('in.house.replace.list')->with($notification);
    }

    // grandted replaceed
    public function ReplaceGranted($id)
    {
        $supplierReplace = SupplierReplaceHistory::findOrFail($id);
        $replacedProduct = ReturnProduct::findOrFail($supplierReplace->replace_id);
        $replacedProduct->status = '2';
        $replacedProduct->replace_status = '1';
        $supplierReplace->status = '1';
        $replacedProduct->update();
        $supplierReplace->update();

        $notification = array(
            'message' => 'Replaced Product To Customer!',
            'alert_type' => 'success',
        );
        return redirect()->back()->with($notification);
    }


    public function UniqueNumber()
    {
        $returnProduct = ReturnProduct::latest()->first();
        if ($returnProduct) {
            $name = $returnProduct->return_no;
            $number = explode('_', $name);
            $return_no = 'RP_' . str_pad((int)$number[1] + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $return_no = 'RP_000001';
        }
        return $return_no;
    }


    // supplier return method
    public function SupplierAllReplace()
    {
        $title = 'Replace Product';
        $suppliers = Supplier::OrderBy('name', 'asc')->get();
        return view('admin.return_product.supplier.return_product_supplier', compact('suppliers', 'title'));
    }

    public function SupplierReplaceHistory($id)
    {
        $returnHistory = SupplierReplaceHistory::where('supplier_id', $id)->where('status', '0')->get();
        $supplier = Supplier::findOrFail($id);
        return view('admin.return_product.supplier.return_product_history', compact('returnHistory', 'supplier'));
    }

    public function StoreReplaceSupplier($id)
    {

        $replaceProduct = ReturnProduct::findOrFail($id);
        $supplier_id = Product::where('id', $replaceProduct->product_id)->first()['supplier_id'];


        $supplierReturn = new SupplierReplaceHistory();
        $supplierReturn->supplier_id = $supplier_id;
        $supplierReturn->replace_id = $replaceProduct->id;
        $supplierReturn->customer_id = $replaceProduct->customer_id;
        $supplierReturn->product_id = $replaceProduct->product_id;
        $supplierReturn->quantity = $replaceProduct->quantity;
        $supplierReturn->date = $replaceProduct->date;
        $supplierReturn->status = $replaceProduct->status;
        $supplierReturn->created_at = Carbon::now();
        $supplierReturn->save();

        $replaceProduct->status = '1';
        $replaceProduct->update();

        $notification = array(
            'message' => 'Replace Product Added To Supplier!',
            'alert_type' => 'success',
        );
        return redirect()->back()->with($notification);
    }


    public function ReplaceOverview()
    {

        $title = 'Replace Overview';
        $totalStock = ReturnProduct::where('replace_status', '0')->sum('quantity');
        $inHouseStock = ReturnProduct::where('status', '0')->sum('quantity');
        $supplierStock = SupplierReplaceHistory::where('status', '0')->sum('quantity');
        return view('admin.return_product.return_product_report', compact('totalStock', 'inHouseStock', 'supplierStock', 'title'));
    }
}
