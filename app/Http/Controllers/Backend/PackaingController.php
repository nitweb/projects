<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Expense;
use App\Models\Packaging;
use App\Models\PackagingMeta;
use App\Models\PackagingStore;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Models\SupplierPaymentDetail;
use App\Models\Warehouse;
use App\Models\WarehouseMeta;
use App\Models\WarehouseStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PackaingController extends Controller
{
    public function AllPackageStock()
    {
        $title = 'Stock Package';
        $products = Product::OrderBy('name', 'asc')->get();
        return view('admin.package.stock_package', compact('title', 'products'));
    }

    public function AllPackage()
    {
        $allPackage = Packaging::latest()->get();
        $title = 'All Package';
        return view('admin.package.all_package', compact('title', 'allPackage'));
    }

    public function AddPackage()
    {
        $title = 'Add Package';
        $products = Product::OrderBy('name', 'asc')->get();
        $suppliers = Supplier::where('status', '0')->OrderBy('name', 'asc')->get();
        $accounts = Bank::OrderBy('name', 'asc')->get();
        $package_no = $this->UniqueNumber();
        return view('admin.package.add_package', compact('package_no', 'products', 'title', 'accounts', 'suppliers'));
    }

    public function StorePackage(Request $request)
    {
        $GLOBALS['status'] = '1';
        $bank_id = $request->bank_id;
        $checkAccount = $request->bank_id;


        if ($request->paid_status != 'full_due' && $checkAccount == null) {
            $notification = array(
                'message' => "Sorry, you don't select any payment account",
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }

        if ($request->paid_amount > $request->estimated_total) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

            $bank_id = $request->bank_id;
            $request_paid_amount = 0;
            if ($bank_id != NULL) {
                $bank = Bank::findOrFail($bank_id);
                if ($request->paid_status == 'full_paid') {
                    $request_paid_amount += $request->estimated_total;
                } elseif ($request->paid_status == 'full_due') {
                    $request_paid_amount += 0;
                } elseif ($request->paid_status == 'partial_paid') {
                    $request_paid_amount += $request->paid_amount;
                }
            }

            if ($bank_id == null) {
                $bank_amount = 0;
            } else {
                $bank_amount = $bank->balance;
            }


            if ($bank_amount >= $request_paid_amount) {
                function bankUpdate($bank, $paid_amount, $package)
                {

                    $bank->balance -= $paid_amount;
                    $bank->update();

                    $bankDetails = new BankDetail();
                    $bankDetails->bank_id = $bank->id;
                    $bankDetails->trans_type = 'asset';
                    $bankDetails->trans_head = 'Packet Purchase';
                    $bankDetails->trans_id = $package->package_no;
                    $bankDetails->balance = $paid_amount;
                    $bankDetails->status = '0';
                    $bankDetails->date = $package->date;
                    $bankDetails->created_at = Carbon::now();
                    $bankDetails->save();
                }

                $package = new Packaging();
                $package->package_no = $request->package_no;
                $package->supplier_id = $request->supplier_id;
                $package->date = $request->date;
                $package->total_amount = (float) $request->estimated_total;
                $package->created_by = Auth::user()->id;
                $package->created_at = Carbon::now();



                // save sata to supplier account
                $supplier_info = Supplier::findOrFail($request->supplier_id);
                $account_details = new SupplierAccount();
                $account_details->supplier_id = $request->supplier_id;
                $account_details->total_amount = $request->estimated_total;
                $account_details->paid_status = $request->paid_status;
                $account_details->paid_source = $request->paid_source;
                $account_details->bank_name = $bank_id;
                $account_details->note = $request->note;
                $account_details->date = date('Y-m-d', strtotime($request->date));
                $account_details->created_at = Carbon::now();

                // save data to supplier purchase details
                $payment_details = new SupplierPaymentDetail();
                $payment_details->supplier_id = $request->supplier_id;
                $payment_details->bank_id = $bank_id;
                $payment_details->note = $request->note;
                $payment_details->date = date('Y-m-d', strtotime($request->date));
                $payment_details->created_at = Carbon::now();


                if ($request->paid_status == 'full_paid') {
                    $paid_amount = $request->estimated_total;
                    bankUpdate($bank, $paid_amount, $package);

                    $package->paid_amount = $paid_amount;
                    $package->due_amount = '0';

                    // save data to accounts
                    $account_details->paid_amount = $paid_amount;
                    $account_details->due_amount = '0';
                    $supplier_info->total_due += $paid_amount;
                    $supplier_info->update();
                    $payment_details->paid_amount = $request->estimated_total;
                } elseif ($request->paid_status == 'full_due') {
                    $package->paid_amount =  '0';
                    $package->due_amount = $request->estimated_total - $request->discount_amount;

                    // save data to accounts
                    $account_details->paid_amount = '0';
                    $account_details->due_amount = $request->estimated_total - $request->discount_amount;

                    $supplier_info->total_due += $request->estimated_total;
                    $supplier_info->update();
                } elseif ($request->paid_status == 'partial_paid') {
                    bankUpdate($bank, $request->paid_amount, $package);
                    $package->paid_amount = $request->paid_amount;
                    $package->due_amount = (float) $request->estimated_total - (float)$request->paid_amount;

                    // save data to accounts
                    $account_details->paid_amount = $request->paid_amount;
                    $account_details->due_amount = (float)$request->estimated_total - (float)$request->paid_amount;
                    $supplier_info->total_due += $request->paid_amount;
                    $supplier_info->update();
                    $payment_details->paid_amount = $request->paid_amount;
                }

                $package->save();
                $account_details->packet_id = $package->id;
                $payment_details->purchase_id = $package->id;
                $account_details->save();
                $payment_details->save();


                for ($i = 0; $i < count($request->package_type); $i++) {
                    $packagingMeta = new PackagingMeta();
                    $packagingMeta->packaging_id = $package->id;
                    $packagingMeta->package_type = $request->package_type[$i];
                    $packagingMeta->product_id = $request->product_id[$i];
                    $packagingMeta->quantity = $request->quantity[$i];
                    $packagingMeta->unit_price = $request->unit_price[$i];
                    $packagingMeta->total_price = $request->total_amount[$i];
                    $packagingMeta->created_at = Carbon::now();
                    $packagingMeta->save();


                    // save to purchse Store
                    $packagingStore = new PackagingStore();
                    $product_name = Product::where('id', $packagingMeta->product_id)->first()['name'];
                    $packagingStore->product_id = $packagingMeta->product_id;
                    $packagingStore->product_name = $product_name;
                    $packagingStore->package_type = $packagingMeta->package_type;
                    $packagingStore->packaging_id =  $package->id;
                    $packagingStore->quantity  = $packagingMeta->quantity;
                    $packagingStore->unit_price = $packagingMeta->unit_price;
                    $packagingStore->created_at = Carbon::now();
                    $packagingStore->save();
                }

                $notification = array(
                    'message' => 'Package Added Successfully',
                    'alert_type' => 'success'
                );
                return redirect()->route('all.package')->with($notification);
            } else {
                $notification = array(
                    'message' => 'Insufficient Balance on selected Bank Account',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        }
    }



    public function ViewPackage($id)
    {
        $package = Packaging::findOrFail($id);
        return view('admin.package.package_view', compact('package'));
    }


    public function PrintPackage($id)
    {
        $package = Packaging::with('packageMeta')->findOrFail($id);
        $packageMeta = PackagingMeta::where('packaging_id', $package->id)->get();
        return view('admin.pdf.packet_purchase_pdf', compact('package', 'packageMeta'));
    }



    public function EditPackage($id)
    {
        $package = Packaging::findOrFail($id);
        $suppliers = Supplier::where('status', '0')->OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $accounts = Bank::OrderBy('name', 'asc')->get();
        return view('admin.package.edit_package', compact('package', 'suppliers', 'products', 'accounts'));
    }

    public function UpdatePackage(Request $request)
    {
        // dd($request->all());
        $package_id = $request->id;
        $package = Packaging::findOrFail($package_id);
        $bank_id = $request->bank_id;
        $GLOBALS['status'] = '1';


        if ($bank_id == null) {
            $notification = array(
                'message' => "Sorry, you don't select any payment account",
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }

        if ($request->paid_amount > $request->estimated_total) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {


            // delete exit package
            function deleteExistingPackage($package)
            {
                foreach ($package->packageMeta as $item) {
                    $item->delete();
                }
                PackagingStore::where('packaging_id', $package->id)->delete();
            }

            // end delete exit package


            function bankUpdate($bank_id, $package, $currentPaid)
            {
                $exits_bankId = SupplierAccount::where('packet_id', $package->id)->first()['bank_name'];
                $bank = Bank::findOrFail($bank_id);
                $bankInfo = Bank::findOrFail($exits_bankId);
                $expense = Expense::where('description', $package->package_no)->first();
                $bankDetails = BankDetail::where('trans_id', $package->package_no)->first();
                $bankDetails->bank_id = $bank_id;
                $bankDetails->balance = $currentPaid;
                $total = $package->paid_amount;
                if ($currentPaid <= $bank->balance) {
                    if ($bank_id == $exits_bankId) {
                        if ($currentPaid > $total) {
                            $existAmount =  $currentPaid - $total;
                            $bankInfo->balance  =  $bankInfo->balance - $existAmount;
                            $bankInfo->update();
                        } else {
                            $existAmount = $total - $currentPaid;
                            $bankInfo->balance  =  $bankInfo->balance + $existAmount;
                            $bankInfo->update();
                        }
                    } else {
                        // update previous transaction
                        $bankInfo->balance  =  $bankInfo->balance + (float) $total;
                        $bankInfo->update();

                        // update new transaction
                        $bank->balance -= (float) $currentPaid;
                        $bank->update();
                    }
                    $expense->amount = $currentPaid;
                    $expense->update();
                    $bankDetails->update();
                    deleteExistingPackage($package);
                } else {
                    $GLOBALS['status'] = '0';
                    $notification = array(
                        'message' => 'Sorry, Insufficient balance on selected Account',
                        'alert-type' => 'error',
                    );
                    return redirect()->back()->with($notification);
                }
            }



            // save sata to supplier account
            $account_details = SupplierAccount::where('purchase_id', $package_id)->first();




            if ($request->paid_status == 'full_paid') {
                $paid_amount = $request->estimated_total;
                bankUpdate($bank_id, $package, $paid_amount);
                $package->paid_amount = $paid_amount;
                $package->due_amount = '0';


                // save data to accounts
                $account_details->paid_amount = $paid_amount;
                $account_details->due_amount = '0';
            } elseif ($request->paid_status == 'partial_paid') {
                $paid_amount = $request->paid_amount;
                bankUpdate($bank_id, $package, $paid_amount);
                $package->paid_amount = $request->paid_amount;
                $package->paid_amount = (float) $request->estimated_total - (float) $paid_amount;


                // save data to accounts
                $account_details->paid_amount = $paid_amount;
                $account_details->due_amount =  (float) $request->estimated_total - (float)$paid_amount;
            }



            $package->date =  $request->date;
            $package->total_amount =  $request->estimated_total;

            $account_details->supplier_id = $request->supplier_id;
            $account_details->total_amount = $request->estimated_total;
            $account_details->paid_status = $request->paid_status;
            $account_details->paid_source = $request->paid_source;
            $account_details->bank_name = $bank_id;
            $account_details->note = $request->note;
            $account_details->date = date('Y-m-d', strtotime($request->date));

            if ($GLOBALS['status'] != '0') {
                for ($i = 0; $i < count($request->package_type); $i++) {
                    $packagingMeta = new PackagingMeta();
                    $packagingMeta->packaging_id = $package->id;
                    $packagingMeta->package_type = $request->package_type[$i];
                    $packagingMeta->product_id = $request->product_id[$i];
                    $packagingMeta->quantity = $request->quantity[$i];
                    $packagingMeta->unit_price = $request->unit_price[$i];
                    $packagingMeta->total_price = $request->total_amount[$i];
                    $packagingMeta->created_at = Carbon::now();
                    $packagingMeta->save();



                    // save to purchse Store
                    $packagingStore = new PackagingStore();
                    $product_name = Product::where('id', $packagingMeta->product_id)->first()['name'];
                    $packagingStore->product_id = $packagingMeta->product_id;
                    $packagingStore->product_name = $product_name;
                    $packagingStore->package_type = $packagingMeta->package_type;
                    $packagingStore->packaging_id =  $package->id;
                    $packagingStore->quantity  = $packagingMeta->quantity;
                    $packagingStore->unit_price = $packagingMeta->unit_price;
                    $packagingStore->created_at = Carbon::now();
                    $packagingStore->save();
                }
            }

            if ($GLOBALS['status'] == '0') {
                $notification = array(
                    'message' => 'Sorry, Insufficient balance on selected Account',
                    'alert-type' => 'error',
                );
                return redirect()->back()->with($notification);
            } else {

                $package->supplier_id =  $request->supplier_id;
                $package->update();
                $account_details->update();
                $package->update();

                $notification = array(
                    'message' => 'Packaging  Updated Successfully',
                    'alert_type' => 'success'
                );
                return redirect()->route('all.package')->with($notification);
            }
        }
    }



    public function DeletePackage($id)
    {
        $package = Packaging::findOrFail($id);

        foreach ($package->packageMeta as $item) {
            $item->delete();
        }
        PackagingStore::where('packaging_id', $package->id)->delete();
        SupplierAccount::where('purchase_id', $package->id)->delete();
        $package->delete();


        $notification = array(
            'message' => 'Purchase Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }


    // packing stock out
    public function ListStockOutPackage()
    {
        $title = 'Stock Out';
        $allWarehouse = Warehouse::OrderBy('name', 'asc')->get();
        return view('admin.warehouse.stock_out_warehouse_list', compact('title', 'allWarehouse'));
    }
    public function AddStockOutPackage()
    {
        $allWarehouse = Warehouse::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $warehouse_no = $this->UniqueStockOutNumber();
        return view('admin.package.stock_out_package', compact('allWarehouse', 'products', 'warehouse_no'));
    }

    public function StoreStockOutPackage(Request $request)
    {

        $warehouse = new WarehouseMeta();
        $warehouse->warehouse_id = $request->warehouse_id;
        $warehouse->warehouse_no = $request->warehouse_no;
        $warehouse->date = $request->date;
        $warehouse->created_by = Auth::user()->id;
        $warehouse->created_at = Carbon::now();
        $warehouse->status = '0';
        $warehouse->save();


        DB::transaction(function () use ($request, $warehouse) {
            if ($warehouse->save()) {
                $count_package_type = count($request->package_type);
                for ($i = 0; $i < $count_package_type; $i++) {
                    $packagingStore = PackagingStore::where('product_id', $request->product_id[$i])
                        ->where('package_type', $request->package_type[$i])
                        ->where('quantity', '!=', 0)
                        ->get();

                    $packagingStock = ((float) $request->selling_qty[$i]); //10
                    foreach ($packagingStore as  $packagingStoreInfo) {
                        $warehouseStock = new WarehouseStock();
                        $warehouseStock->warehouse_id = $warehouse->warehouse_id;
                        $warehouseStock->package_type = $request->package_type[$i];
                        $warehouseStock->product_id = $request->product_id[$i];

                        if ((float) $packagingStock > (float)$packagingStoreInfo->quantity) {

                            $packagingStock = abs($packagingStock - (float) $packagingStoreInfo->quantity); //10
                            $warehouseStock->quantity = (float) $packagingStoreInfo->quantity;
                            $warehouseStock->unit_price = (float) $packagingStoreInfo->unit_price;
                            $warehouseStock->total_price = $warehouseStock->quantity * (float) $packagingStoreInfo->unit_price;
                            $warehouseStock->save();

                            $packagingStoreInfo->update([
                                'quantity' => 0,
                            ]);
                        } else {
                            $packagingStoreInfo->update([
                                'quantity' => (float) $packagingStoreInfo->quantity - $packagingStock,
                            ]);
                            $warehouseStock->quantity = $packagingStock; //10
                            $warehouseStock->unit_price = (float) $packagingStoreInfo->unit_price;
                            $warehouseStock->total_price = $warehouseStock->quantity * (float) $packagingStoreInfo->unit_price;
                            $warehouseStock->created_at = Carbon::now();
                            $warehouseStock->save();
                            break;
                        }
                    }
                    // dd($packagingStore);
                }
                $warehouse->status = '1';
                $warehouse->save();
            }
        });
        $notification = array(
            'message' => 'Package Addedd Successfully',
            'alert_type' => 'success'
        );
        return redirect()->route('package.stock.out.list')->with($notification);
    }


    public function UniqueNumber()
    {
        $package = Packaging::latest()->first();
        if ($package) {
            $name = $package->package_no;
            $number = explode('_', $name);
            $package_no = 'PK_' . str_pad((int)$number[1] + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $package_no = 'PK_000001';
        }
        return $package_no;
    }

    public function UniqueStockOutNumber()
    {
        $warehouseStockNo = WarehouseMeta::latest()->first();
        if ($warehouseStockNo) {
            $name = $warehouseStockNo->warehouse_no;
            $number = explode('_', $name);
            $warehouse_no = 'WH_' . str_pad((int)$number[1] + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $warehouse_no = 'WH_000001';
        }
        return $warehouse_no;
    }


    public function GetPackage(Request $request)
    {
        $id =  $request->id;
        // $products = PackagingStore::where('package_type', $id)->get();
        $products = PackagingStore::where('package_type', $id)->distinct()->get(['product_name', 'product_id']);
        // dd($products);
        return response()->json($products);
    }

    public function GetPackageStock(Request $request, $id)
    {
        $productStock = PackagingStore::where('product_id', $id)->where('package_type', $request->product_type)->get();
        return response()->json($productStock);
    }
}
