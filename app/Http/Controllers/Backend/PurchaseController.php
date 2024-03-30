<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountDetail;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Category;
use App\Models\Expense;
use App\Models\PackagingStore;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseMeta;
use App\Models\PurchaseStore;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Models\SupplierPaymentDetail;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{

    public function AllPurchase()
    {
        $allPurchase = Purchase::latest()->get();
        return view('admin.purchase_page.all_purchase', compact('allPurchase'));
    }

    public function AddPurchase()
    {
        $suppliers = Supplier::where('status', '1')->OrderBy('name', 'asc')->get();
        $allWarehouse = Warehouse::OrderBy('name', 'asc')->get();
        $categories = Category::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $accounts = Bank::OrderBy('name', 'asc')->get();
        $purchase_no = $this->UniqueNumber();
        return view('admin.purchase_page.add_purchase', compact('purchase_no', 'suppliers', 'accounts', 'categories', 'products', 'allWarehouse'));
    }

    public function StorePurchase(Request $request)
    {
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

        if ($request->paid_amount > $request->estimated_total) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

            if ($bank_id == null) {
                $bank_amount = 0;
            } else {
                $bank_amount = $bank->balance;
            }



            if ($bank_amount >= $request_paid_amount) {
                $purchase = new Purchase();
                $purchase->purchase_no = $request->purchase_no;
                $purchase->date = $request->date;
                $purchase->supplier_id = $request->supplier_id;
                $purchase->total_amount = $request->estimated_total;
                $purchase->discount_amount = $request->discount_amount;
                $purchase->created_by = Auth::user()->id;
                $purchase->created_at = Carbon::now();
                $purchase->save();


                //save data to supplier account
                $supplier_info = Supplier::findOrFail($request->supplier_id);
                $account_details = new SupplierAccount();
                $account_details->supplier_id = $request->supplier_id;
                $account_details->paid_status = $request->paid_status;
                $account_details->paid_source = $request->paid_source;
                $account_details->total_amount = $request->estimated_total;
                $account_details->bank_name = $bank_id;
                $account_details->note = $request->note;
                $account_details->date = date('Y-m-d', strtotime($request->date));
                $account_details->created_at = Carbon::now();

                // save data to supplier purchase details
                $payment_details = new SupplierPaymentDetail();
                $payment_details->supplier_id = $request->supplier_id;
                $payment_details->purchase_id = $purchase->id;
                $payment_details->bank_id = $bank_id;
                $payment_details->note = $request->note;
                $payment_details->date = date('Y-m-d', strtotime($request->date));
                $payment_details->created_at = Carbon::now();




                for ($i = 0; $i < count($request->category_id); $i++) {
                    $purchaseMeta = new PurchaseMeta();
                    $purchaseMeta->purchase_id = $purchase->id;
                    $purchaseMeta->category_id = $request->category_id[$i];
                    $purchaseMeta->product_id = $request->product_id[$i];
                    $purchaseMeta->quantity = $request->quantity[$i];
                    $purchaseMeta->unit_price = $request->unit_price[$i];
                    $purchaseMeta->created_at = Carbon::now();
                    $purchaseMeta->save();


                    if ($request->warehouse_id[$i] != '0') {
                        $warehouseStockMaster = WarehouseStock::where('warehouse_id', $request->warehouse_id[$i])
                            ->where('product_id', $purchaseMeta->product_id)
                            ->where('package_type', 'Master')
                            ->where('quantity', '!=', 0)->get();

                        $warehouseStockInner = WarehouseStock::where('warehouse_id', $request->warehouse_id[$i])
                            ->where('product_id', $purchaseMeta->product_id)
                            ->where('package_type', 'Inner')
                            ->where('quantity', '!=', 0)->get();


                        if ($warehouseStockInner->sum('quantity') >=  $request->inner_qty[$i] && $warehouseStockMaster->sum('quantity') >=  $request->master_qty[$i]) {

                            $innerStockRequest =  $request->inner_qty[$i];
                            foreach ($warehouseStockInner as $innerStock) {
                                if ((float) $innerStockRequest > (float) $innerStock->quantity) {
                                    $innerStockRequest = abs($innerStockRequest - (float) $innerStock->quantity);

                                    $purchaseStore = new PurchaseStore();
                                    $purchaseStore->product_id = $purchaseMeta->product_id;
                                    $purchaseStore->purchase_id =  $purchase->id;
                                    $purchaseStore->quantity  = $innerStock->quantity;
                                    $purchaseStore->unit_price = (float) $request->unit_price[$i] +  $innerStock->unit_price;
                                    $purchaseStore->created_at = Carbon::now();
                                    $purchaseStore->save();

                                    $innerStock->update([
                                        'quantity' => 0,
                                    ]);
                                } else {
                                    $innerStock->update([
                                        'quantity' => (float) $innerStock->quantity - $innerStockRequest,
                                    ]);

                                    $purchaseStore = new PurchaseStore();
                                    $purchaseStore->product_id = $purchaseMeta->product_id;
                                    $purchaseStore->category_id = $purchaseMeta->category_id;
                                    $purchaseStore->purchase_id =  $purchase->id;
                                    $purchaseStore->quantity  = $innerStockRequest;
                                    $purchaseStore->unit_price = (float) $request->unit_price[$i] +  $innerStock->unit_price;
                                    $purchaseStore->created_at = Carbon::now();
                                    $purchaseStore->save();
                                    break;
                                }
                            }


                            $masterStockRequest =  $request->master_qty[$i];
                            foreach ($warehouseStockMaster as $masterStock) {
                                if ((float) $masterStockRequest > (float)$masterStock->quantity) {
                                    $masterStockRequest = abs($masterStockRequest - (float) $masterStock->quantity);
                                    $purchaseMeta->unit_price = $masterStock->unit_price;
                                    $masterStock->update([
                                        'quantity' => 0,
                                    ]);
                                } else {
                                    $masterStock->update([
                                        'quantity' => $masterStock->quantity -  $masterStockRequest,
                                    ]);

                                    $purchaseStore = PurchaseStore::where('product_id', $purchaseMeta->product_id)->where('purchase_id', $purchaseMeta->purchase_id)->get();
                                    foreach ($purchaseStore as   $value) {
                                        $masterCartonPrice =  (float)$masterStock->unit_price /  $request->quantity[$i];
                                        $unit_price = round((float) $value->unit_price + (float) $masterCartonPrice, 2);
                                        if ($request->discount_type != null) {
                                            $discount_per_qty = $request->discount_amount / $request->total_quantity;
                                            $new_unit_price = $unit_price - $discount_per_qty;
                                        } else {
                                            $new_unit_price  = $unit_price;
                                        }
                                    }
                                }
                            }
                        } else {
                            Purchase::findOrFail($purchase->id)->delete();
                            $notification = array(
                                'message' => 'Sorry,Request Product Cartoon Not available!',
                                'alert-type' => 'error'
                            );
                            return redirect()->back()->with($notification);
                        }
                    } elseif ($request->warehouse_id[$i] == '0') {
                        $inHouseStockMaster = PackagingStore::where('product_id', $purchaseMeta->product_id)
                            ->where('package_type', 'Master')
                            ->where('quantity', '!=', 0)->get();

                        $inHouseStockInner = PackagingStore::where('product_id', $purchaseMeta->product_id)
                            ->where('package_type', 'Inner')
                            ->where('quantity', '!=', 0)->get();

                        if ($inHouseStockInner->sum('quantity') >=  $request->inner_qty[$i] && $inHouseStockMaster->sum('quantity') >=  $request->master_qty[$i]) {

                            $innerStockRequest =  $request->inner_qty[$i];
                            foreach ($inHouseStockInner as $innerStock) {
                                if ((float) $innerStockRequest > (float) $innerStock->quantity) {
                                    $innerStockRequest = abs($innerStockRequest - (float) $innerStock->quantity);

                                    $purchaseStore = new PurchaseStore();
                                    $purchaseStore->product_id = $purchaseMeta->product_id;
                                    $purchaseStore->category_id = $purchaseMeta->category_id;
                                    $purchaseStore->purchase_id =  $purchase->id;
                                    $purchaseStore->quantity  = $innerStock->quantity;
                                    $purchaseStore->unit_price = (float) $request->unit_price[$i] +  $innerStock->unit_price;
                                    $purchaseStore->created_at = Carbon::now();
                                    $purchaseStore->save();

                                    $innerStock->update([
                                        'quantity' => 0,
                                    ]);
                                } else {
                                    $innerStock->update([
                                        'quantity' => (float) $innerStock->quantity - $innerStockRequest,
                                    ]);

                                    $purchaseStore = new PurchaseStore();
                                    $purchaseStore->product_id = $purchaseMeta->product_id;
                                    $purchaseStore->category_id = $purchaseMeta->category_id;
                                    $purchaseStore->purchase_id =  $purchase->id;
                                    $purchaseStore->quantity  = $innerStockRequest;
                                    $purchaseStore->unit_price = (float) $request->unit_price[$i] +  $innerStock->unit_price;
                                    $purchaseStore->created_at = Carbon::now();
                                    $purchaseStore->save();
                                    break;
                                }
                            }


                            $masterStockRequest =  $request->master_qty[$i];
                            foreach ($inHouseStockMaster as $masterStock) {
                                if ((float) $masterStockRequest > (float)$masterStock->quantity) {
                                    $masterStockRequest = abs($masterStockRequest - (float) $masterStock->quantity);
                                    $masterStock->update([
                                        'quantity' => 0,
                                    ]);
                                } else {
                                    $masterStock->update([
                                        'quantity' => $masterStock->quantity -  $masterStockRequest,
                                    ]);

                                    $purchaseStore = PurchaseStore::where('product_id', $purchaseMeta->product_id)->where('purchase_id', $purchaseMeta->purchase_id)->get();
                                    foreach ($purchaseStore as   $value) {
                                        $masterCartonPrice =  (float) $masterStock->unit_price /  $request->quantity[$i];
                                        $unit_price = round((float) $value->unit_price + (float) $masterCartonPrice, 2);


                                        if ($request->discount_type != null) {
                                            $discount_per_qty = $request->discount_amount / $request->total_quantity;
                                            $new_unit_price = $unit_price - $discount_per_qty;
                                        } else {
                                            $new_unit_price  = $unit_price;
                                        }

                                        $value->update(
                                            [
                                                'unit_price' => $new_unit_price,
                                            ]
                                        );
                                    }
                                }
                            }
                        } else {
                            Purchase::findOrFail($purchase->id)->delete();
                            $notification = array(
                                'message' => 'Sorry,Request Product Cartoon Not available!',
                                'alert-type' => 'error'
                            );
                            return redirect()->back()->with($notification);
                        }
                    }
                }


                $bank_details = new BankDetail();
                if ($bank_id != null) {
                    $bank_details->bank_id = $bank_id;
                }
                $bank_details->trans_type = 'asset';
                $bank_details->trans_head = 'Product Purchase';
                $bank_details->trans_id = $request->purchase_no;
                $bank_details->date = date('Y-m-d', strtotime($request->date));
                $bank_details->status = '0';




                if ($request->paid_status == 'full_paid') {

                    $purchase->paid_amount = $request->estimated_total;
                    $purchase->due_amount = '0';

                    // save data to accounts
                    $account_details->paid_amount = $request->estimated_total;
                    $account_details->due_amount = '0';
                    $payment_details->paid_amount = $request->estimated_total;

                    // save data expense and bank
                    $bank_details->balance = $request->estimated_total;
                    $bank->balance -= $request->estimated_total;
                } elseif ($request->paid_status == 'full_due') {
                    $purchase->paid_amount =  '0';
                    $purchase->due_amount = $request->estimated_total;

                    // save data to accounts
                    $account_details->paid_amount = '0';
                    $account_details->due_amount = $request->estimated_total;

                    $supplier_info->total_due += $request->estimated_total;
                    $supplier_info->update();
                } elseif ($request->paid_status == 'partial_paid') {
                    $purchase->paid_amount = $request->paid_amount;
                    $purchase->due_amount = $request->estimated_total - $request->paid_amount;

                    // save data to accounts
                    $account_details->paid_amount = $request->paid_amount;
                    $account_details->due_amount = $request->estimated_total - $request->paid_amount;
                    $payment_details->paid_amount = $request->paid_amount;

                    $bank_details->balance = $request->paid_amount;
                    $bank->balance -= $request->paid_amount;

                    $supplier_info->total_due += $account_details->due_amount;
                    $supplier_info->update();
                }

                $purchase->save();

                $account_details->purchase_id = $purchase->id;
                $account_details->save();
                if ($bank_id != null) {
                    $bank->update();
                    $bank_details->save();
                    $payment_details->save();
                }

                $notification = array(
                    'message' => 'Purchase Added Successfully',
                    'alert_type' => 'success'
                );
                return redirect()->route('all.purchase')->with($notification);
            } else {
                $notification = array(
                    'message' => 'Insufficient Balance on selected Bank Account',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        }
    }


    public function EditPurchase($id)
    {
        $purchase = Purchase::findOrFail($id);
        $suppliers = Supplier::OrderBy('name', 'asc')->get();
        $categories = Category::all();
        $products = Product::all();
        return view('admin.purchase_page.edit_purchase', compact('purchase', 'suppliers', 'categories', 'products'));
    }

    public function UpdatePurchase(Request $request)
    {
        $purchase_id = $request->id;
        $bank_id = $request->bank_id;
        $purchase = Purchase::findOrFail($purchase_id);



        // delete exit package
        function deleteExistingPurchase($purchase)
        {
            foreach ($purchase->purchaseMeta as $item) {
                $item->delete();
            }
            PurchaseStore::where('purchase_id', $purchase->id)->delete();
        }
        // end delete exit purchase


        function bankUpdate($bank_id, $purchase, $currentPaid)
        {
            $exits_bankId = SupplierAccount::where('purchase_id', $purchase->id)->first()['bank_name'];
            $bank = Bank::findOrFail($bank_id);
            $bankInfo = Bank::findOrFail($exits_bankId);
            $total = $purchase->paid_amount;
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
                deleteExistingPackage($purchase);
            } else {
                $GLOBALS['status'] = '0';
                $notification = array(
                    'message' => 'Sorry, Insufficient balance on selected Account',
                    'alert-type' => 'error',
                );
                return redirect()->back()->with($notification);
            }
        }





        if ($request->paid_amount > $request->estimated_total) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

            $purchase->date = $request->date;
            $purchase->supplier_id = $request->supplier_id;
            $purchase->date = $request->date;


            if ($bank_id != NULL) {
                $bank = Bank::findOrFail($bank_id);
            }



            // save data to supplier account
            $account_details = SupplierAccount::where('purchase_id', $purchase_id)->first();
            $account_details->supplier_id = $request->supplier_id;
            $account_details->paid_status = $request->paid_status;
            $account_details->paid_status = $request->paid_source;
            $account_details->bank_name = $bank_id;
            $account_details->date = date('Y-m-d', strtotime($request->date));


            for ($i = 0; $i < count($request->category_id); $i++) {
                $purchaseMeta = new PurchaseMeta();
                $purchaseMeta->purchase_id = $purchase->id;
                $purchaseMeta->category_id = $request->category_id[$i];
                $purchaseMeta->product_id = $request->product_id[$i];
                $purchaseMeta->quantity = $request->quantity[$i];
                $purchaseMeta->created_at = Carbon::now();
                $purchaseMeta->save();


                if ($request->warehouse_id[$i] != '0') {
                    $warehouseStockMaster = WarehouseStock::where('warehouse_id', $request->warehouse_id[$i])
                        ->where('product_id', $purchaseMeta->product_id)
                        ->where('package_type', 'Master')
                        ->where('quantity', '!=', 0)->get();

                    $warehouseStockInner = WarehouseStock::where('warehouse_id', $request->warehouse_id[$i])
                        ->where('product_id', $purchaseMeta->product_id)
                        ->where('package_type', 'Inner')
                        ->where('quantity', '!=', 0)->get();


                    if ($warehouseStockInner->sum('quantity') >=  $request->inner_qty[$i] && $warehouseStockMaster->sum('quantity') >=  $request->master_qty[$i]) {

                        $innerStockRequest =  $request->inner_qty[$i];
                        foreach ($warehouseStockInner as $innerStock) {
                            if ((float) $innerStockRequest > (float) $innerStock->quantity) {
                                $innerStockRequest = abs($innerStockRequest - (float) $innerStock->quantity);

                                $purchaseStore = new PurchaseStore();
                                $purchaseStore->product_id = $purchaseMeta->product_id;
                                $purchaseStore->purchase_id =  $purchase->id;
                                $purchaseStore->quantity  = $innerStock->quantity;
                                $purchaseStore->unit_price = (float) $request->unit_price[$i] +  $innerStock->unit_price;
                                $purchaseStore->created_at = Carbon::now();
                                $purchaseStore->save();

                                $innerStock->update([
                                    'quantity' => 0,
                                ]);
                            } else {
                                $innerStock->update([
                                    'quantity' => (float) $innerStock->quantity - $innerStockRequest,
                                ]);

                                $purchaseStore = new PurchaseStore();
                                $purchaseStore->product_id = $purchaseMeta->product_id;
                                $purchaseStore->category_id = $purchaseMeta->category_id;
                                $purchaseStore->purchase_id =  $purchase->id;
                                $purchaseStore->quantity  = $innerStockRequest;
                                $purchaseStore->unit_price = (float) $request->unit_price[$i] +  $innerStock->unit_price;
                                $purchaseStore->created_at = Carbon::now();
                                $purchaseStore->save();
                                break;
                            }
                        }


                        $masterStockRequest =  $request->master_qty[$i];
                        foreach ($warehouseStockMaster as $masterStock) {
                            if ((float) $masterStockRequest > (float)$masterStock->quantity) {
                                $masterStockRequest = abs($masterStockRequest - (float) $masterStock->quantity);
                                $purchaseMeta->unit_price = $masterStock->unit_price;
                                $masterStock->update([
                                    'quantity' => 0,
                                ]);
                            } else {
                                $masterStock->update([
                                    'quantity' => $masterStock->quantity -  $masterStockRequest,
                                ]);

                                $purchaseStore = PurchaseStore::where('product_id', $purchaseMeta->product_id)->where('purchase_id', $purchaseMeta->purchase_id)->get();
                                foreach ($purchaseStore as   $value) {
                                    $masterCartonPrice =  (float)$masterStock->unit_price /  $request->quantity[$i];
                                    $unit_price = round((float) $value->unit_price + (float) $masterCartonPrice, 2);
                                    $value->update(
                                        [
                                            'unit_price' => $unit_price,
                                        ]
                                    );
                                }
                            }
                        }
                    } else {
                        Purchase::findOrFail($purchase->id)->delete();
                        $notification = array(
                            'message' => 'Sorry,Request Product Not Available!',
                            'alert-type' => 'error'
                        );
                        return redirect()->back()->with($notification);
                    }
                } elseif ($request->warehouse_id[$i] == '0') {
                    $inHouseStockMaster = PackagingStore::where('product_id', $purchaseMeta->product_id)
                        ->where('package_type', 'Master')
                        ->where('quantity', '!=', 0)->get();

                    $inHouseStockInner = PackagingStore::where('product_id', $purchaseMeta->product_id)
                        ->where('package_type', 'Inner')
                        ->where('quantity', '!=', 0)->get();

                    if ($inHouseStockInner->sum('quantity') >=  $request->inner_qty[$i] && $inHouseStockMaster->sum('quantity') >=  $request->master_qty[$i]) {

                        $innerStockRequest =  $request->inner_qty[$i];
                        foreach ($inHouseStockInner as $innerStock) {
                            if ((float) $innerStockRequest > (float) $innerStock->quantity) {
                                $innerStockRequest = abs($innerStockRequest - (float) $innerStock->quantity);

                                $purchaseStore = new PurchaseStore();
                                $purchaseStore->product_id = $purchaseMeta->product_id;
                                $purchaseStore->category_id = $purchaseMeta->category_id;
                                $purchaseStore->purchase_id =  $purchase->id;
                                $purchaseStore->quantity  = $innerStock->quantity;
                                $purchaseStore->unit_price = (float) $request->unit_price[$i] +  $innerStock->unit_price;
                                $purchaseStore->created_at = Carbon::now();
                                $purchaseStore->save();

                                $innerStock->update([
                                    'quantity' => 0,
                                ]);
                            } else {
                                $innerStock->update([
                                    'quantity' => (float) $innerStock->quantity - $innerStockRequest,
                                ]);

                                $purchaseStore = new PurchaseStore();
                                $purchaseStore->product_id = $purchaseMeta->product_id;
                                $purchaseStore->category_id = $purchaseMeta->category_id;
                                $purchaseStore->purchase_id =  $purchase->id;
                                $purchaseStore->quantity  = $innerStockRequest;
                                $purchaseStore->unit_price = (float) $request->unit_price[$i] +  $innerStock->unit_price;
                                $purchaseStore->created_at = Carbon::now();
                                $purchaseStore->save();
                                break;
                            }
                        }


                        $masterStockRequest =  $request->master_qty[$i];
                        foreach ($inHouseStockMaster as $masterStock) {
                            if ((float) $masterStockRequest > (float)$masterStock->quantity) {
                                $masterStockRequest = abs($masterStockRequest - (float) $masterStock->quantity);
                                $purchaseMeta->unit_price = $masterStock->unit_price;
                                $masterStock->update([
                                    'quantity' => 0,
                                ]);
                            } else {
                                $masterStock->update([
                                    'quantity' => $masterStock->quantity -  $masterStockRequest,
                                ]);

                                $purchaseStore = PurchaseStore::where('product_id', $purchaseMeta->product_id)->where('purchase_id', $purchaseMeta->purchase_id)->get();
                                foreach ($purchaseStore as   $value) {
                                    $masterCartonPrice =  (float) $masterStock->unit_price /  $request->quantity[$i];
                                    $unit_price = round((float) $value->unit_price + (float) $masterCartonPrice, 2);
                                    $value->update(
                                        [
                                            'unit_price' => $unit_price,
                                        ]
                                    );
                                }
                            }
                        }
                    } else {
                        Purchase::findOrFail($purchase->id)->delete();
                        $notification = array(
                            'message' => 'Sorry,Request Product Not Available!',
                            'alert-type' => 'error'
                        );
                        return redirect()->back()->with($notification);
                    }
                }
            }



            $purchaseTotal = PurchaseStore::where('purchase_id', $purchase->id)->get();
            $totalAmount = 0;
            foreach ($purchaseTotal as $key => $value) {
                $totalAmount += $value->quantity * $value->unit_price;
            }



            $bank_details = new BankDetail();
            $bank_details->bank_id = $bank_id;
            $bank_details->trans_type = 'expense';
            $bank_details->trans_head = 'Product Purchase';
            $bank_details->trans_id = $request->purchase_no;
            $bank_details->date = date('Y-m-d', strtotime($request->date));
            $bank_details->status = '0';



            $purchase->total_amount = round($totalAmount);
            $account_details->total_amount = round($totalAmount);
            $expense = Expense::where('description', $purchase->purchase_no)->first();
            if ($request->paid_status == 'full_paid') {

                $purchase->paid_amount = $request->estimated_total;;
                if ($bank->balance >= $purchase->paid_amount) {
                    $purchase->due_amount = '0';

                    // save data to accounts
                    $account_details->paid_amount = $request->estimated_total;;
                    $account_details->due_amount = '0';
                    // expense data
                    $expense->amount = $request->estimated_total;;
                    $bank_details->balance = $request->estimated_total;;
                    $bank->balance -= round((float) $totalAmount - $request->discount_amount);
                    $expense->amount = round((float) $totalAmount - $request->discount_amount);
                } else {
                    Purchase::findOrFail($purchase->id)->delete();
                    $notification = array(
                        'message' => 'Sorry, Paid amount is not avialabe in selected bank',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }

                //save data to bank

            } elseif ($request->paid_status == 'full_due') {
                $purchase->paid_amount =  '0';
                $purchase->due_amount = $request->estimated_total;;

                // save data to accounts
                $account_details->paid_amount = '0';
                $account_details->due_amount = round($totalAmount -  $request->discount_amount);
            } elseif ($request->paid_status == 'partial_paid') {
                $purchase->paid_amount = $request->paid_amount;

                if ($bank->balance >= $request->paid_amount) {
                    // save data to accounts
                    $account_details->paid_amount = $request->paid_amount;
                    $account_details->due_amount = round((float) $totalAmount - (float) $request->paid_amount - $request->discount_amount);

                    // expense data
                    $expense->amount = round((float) $totalAmount -  (float) $request->discount_amount);

                    $bank_details->balance = round($request->paid_amount);
                    $bank->balance -= round($request->paid_amount);
                } else {
                    Purchase::findOrFail($purchase->id)->delete();
                    $notification = array(
                        'message' => 'Sorry, Paid amount is not avialabe in selected bank',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }
            }

            $purchase->save();
            $account_details->purchase_id = $purchase->id;
            $account_details->update();
            if ($bank_id != NULL) {
                $expense->update();
                $bank->update();
                $bank_details->update();
            }

            $notification = array(
                'message' => 'Purchase Updated Successfully',
                'alert_type' => 'success'
            );
            return redirect()->route('all.purchase')->with($notification);
        }
    }

    public function GetPurchase(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;


        if ($start_date == null && $end_date == null) {
            $allPurchase = Purchase::all();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allPurchase = Purchase::whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
                ->get();
        }

        return view('admin.purchase_page.search_purchase_result', compact('allPurchase', 'start_date', 'end_date'));
    }


    public function ViewPurchase($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('admin.purchase_page.purchase_view', compact('purchase'));
    }


    public function DeletePurchase($id)
    {
        $purchase = Purchase::findOrFail($id);

        foreach ($purchase->purchaseMeta as $item) {
            // product quantity update
            $product = Product::where('id', $item->product_id)->first();
            $purchase_qty = $product->quantity - $item->quantity;
            $product->quantity = $purchase_qty;
            $product->save();
            $item->delete();
        }
        SupplierAccount::where('purchase_id', $purchase->id)->delete();
        PurchaseStore::where('purchase_id', $purchase->id)->delete();
        Expense::where('description', $purchase->purchase_no)->delete();
        $purchase->delete();


        $notification = array(
            'message' => 'Purchase Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }


    public function UniqueNumber()
    {
        $purchase = Purchase::latest()->first();
        if ($purchase) {
            $name = $purchase->purchase_no;
            $number = explode('_', $name);
            $purchase_no = 'PS_' . str_pad((int)$number[1] + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $purchase_no = 'PS_000001';
        }
        return $purchase_no;
    }

    public function GetProduct(Request $request)
    {
        $id =  $request->id;
        // $supplier_id = $request->supplier_id;

        $products = Product::where('category_id', $id)->get();
        return response()->json($products);
    }


    // due payment
    public function PurchaseDuePayment($id)
    {
        $purchaseInfo = Purchase::findOrFail($id);
        $supplierInfo = Supplier::where('id', $purchaseInfo->supplier_id)->first();
        return view('admin.purchase_page.purchase_due_payment', compact('purchaseInfo', 'supplierInfo'));
    }


    public function PurchaseDuePaymentStore(Request $request)
    {
        $purchase_id = $request->id;
        // dd($request->all());

        $supplierInfo = SupplierAccount::where('supplier_id', $request->supplier_id)->get();
        $due_amount = $supplierInfo->sum('total_amount') - $supplierInfo->sum('paid_amount');

        if ($request->paid_amount > $request->due_amount) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the due amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

            if ($request->paid_source == 'bank') {
                $bank_name = $request->bank_name;
                $note = $request->check_number;
            } else if ($request->paid_source == 'mobile-banking') {
                $bank_name = $request->mobile_bank;
                $note = $request->transaction_number;
            } else if ($request->paid_source == 'online-banking') {
                $note = $request->note;
                $bank_name = NULL;
            } else {
                $bank_name = NULL;
                $note = NULL;
            }


            $account_details = new SupplierAccount();
            $purchase = Purchase::findOrfail($purchase_id);
            $current_paid_amount = $purchase->paid_amount;
            $current_due_amount = $purchase->due_amount;

            $account_details->supplier_id = $request->supplier_id;
            $account_details->purchase_id = $purchase_id;
            $account_details->paid_status = $request->paid_status;
            $account_details->paid_source = $request->paid_source;
            $account_details->bank_name = $bank_name;
            $account_details->status = '0';
            $account_details->note = $note;
            $account_details->date = date('Y-m-d', strtotime($request->date));




            if ($request->paid_status == 'full_paid') {
                $account_details->paid_amount = $request->due_amount;
                $account_details->due_amount = '0';
                $purchase->update([
                    'paid_amount' => $current_paid_amount + $request->due_amount,
                    'due_amount' => '0',
                ]);
            } elseif ($request->paid_status == 'partial_paid') {
                $account_details->paid_amount = $request->paid_amount;
                $new_paid_amount = $current_paid_amount + $request->paid_amount;
                $account_details->due_amount = $request->total_amount - $new_paid_amount;
                $purchase->update([
                    'paid_amount' => $new_paid_amount,
                    'due_amount' => $current_due_amount - $request->paid_amount,
                ]);
            }

            $account_details->save();

            $notification = array(
                'message' => 'Payment Updated Successfully!',
                'alert_type' => 'success',
            );
            return redirect()->back()->with($notification);
        }
    }

    public function PurchasePrint($id)
    {
        $purchase = Purchase::with('purchaseMeta')->findOrFail($id);
        $purchaseMeta = PurchaseMeta::where('purchase_id', $purchase->id)->get();
        return view('admin.pdf.purchase_pdf', compact('purchase', 'purchaseMeta'));
    }

    public function PurchaseHistory($id)
    {
        $purchase = PurchaseMeta::where('product_id', $id)->get();
        $product = Product::findOrFail($id);
        return view('admin.purchase_page.purchase_history', compact('purchase', 'product'));
    }
}
