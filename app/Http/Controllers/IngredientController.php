<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\IngredientStore;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseMeta;
use App\Models\PurchaseStore;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Models\SupplierPaymentDetail;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class IngredientController extends Controller
{
    public function IngredientAll()
    {
        $ingredientAll = Ingredient::all();
        return view('admin.ingredient.ingredient_all', compact('ingredientAll'));
    }


    public function IngredientAdd()
    {
        $units = Unit::orderBy('name', 'asc')->get();
        return view('admin.ingredient.ingredient_add', compact('units'));
    }

    public function IngredientStore(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|unique:ingredients',
            ],
            [
                'name.unique' => 'Ingredient name already exists!',
            ]

        );

        $ingredient = new Ingredient();
        $ingredient->name = $request->name;
        $ingredient->unit_id = $request->unit_id;
        $ingredient->created_at = Carbon::now();
        $ingredient->save();

        $notification = array([
            'message' => 'Ingredient Added Successfully',
            'alert_type' => 'success',
        ]);
        return redirect()->route('ingredient.all')->with($notification);
    }

    public function IngredientEdit($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $units = Unit::all();
        return view('admin.ingredient.ingredient_edit', compact('ingredient', 'units'));
    }

    public function IngredientUpdate(Request $request)
    {
        $ingredient_id = $request->id;
        Ingredient::findOrFail($ingredient_id)->update([
            'name' => $request->name,
            'unit_id' => $request->unit_id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array([
            'message' => 'ingredient Updated Successfully',
            'alert_type' => 'info',
        ]);
        return redirect()->route('ingredient.all')->with($notification);
    }

    public function IngredientDelete($id)
    {
        Ingredient::findOrFail($id)->delete();
        $notification = array([
            'message' => 'ingredient Deleted Successfully',
            'alert_type' => 'success',
        ]);
        return redirect()->back()->with($notification);
    }

    public function IngredientPurchase()
    {

        $suppliers = Supplier::where('status', '1')->OrderBy('name', 'asc')->get();
        $ingredients = Ingredient::OrderBy('name', 'asc')->get();
        $accounts = Bank::OrderBy('name', 'asc')->get();
        $purchase_no = $this->UniqueNumber();

        return view('admin.ingredient.ingredient_purchase', compact('suppliers', 'ingredients', 'accounts', 'purchase_no'));
    }

    public function StorePurchase(Request $request)
    {
        // dd($request->all());
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
                $account_details->bank_id = $bank_id;
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




                for ($i = 0; $i < count($request->ingredient_id); $i++) {
                    $purchaseMeta = new PurchaseMeta();
                    $purchaseMeta->purchase_id = $purchase->id;
                    $purchaseMeta->ingredient_id = $request->ingredient_id[$i];
                    $purchaseMeta->quantity = $request->quantity[$i];
                    $purchaseMeta->unit_price = $request->unit_price[$i];
                    $purchaseMeta->created_at = Carbon::now();
                    $purchaseMeta->save();


                    $ingredientStore = new IngredientStore();
                    $ingredientStore->purchase_id =  $purchase->id;
                    $ingredientStore->ingredient_id =  $request->ingredient_id[$i];
                    $ingredientStore->quantity  = $purchaseMeta->quantity;
                    $ingredientStore->unit_price = $request->unit_price[$i];
                    $ingredientStore->created_at = Carbon::now();
                    $ingredientStore->save();
                }

                $bank_details = new BankDetail();
                if ($bank_id != null) {
                    $bank_details->bank_id = $bank_id;
                }
                $bank_details->trans_type = 'asset';
                $bank_details->trans_head = 'Ingredient Purchase';
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
}
