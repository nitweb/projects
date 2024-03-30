<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BatteryIngredient;
use App\Models\BatteryIngredientDetail;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BatteryController extends Controller
{
    public function BatteryAll()
    {
        $allBattery = BatteryIngredient::get();
        return view('admin.battery.battery_all', compact('allBattery'));
    }

    public function BatteryAdd()
    {
        $categories = Category::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $accounts = Bank::OrderBy('name', 'asc')->get();
        $ingredients = Ingredient::OrderBy('name', 'asc')->get();

        return view('admin.battery.battery_add', compact('categories', 'accounts', 'ingredients'));
    }

    public function BatteryStore(Request $request)
    {
        $battery = new BatteryIngredient();
        $battery->category_id = $request->category_id;
        $battery->product_id = $request->product_id;
        $battery->weight = $request->weight;
        $battery->created_at = Carbon::now();
        $battery->save();

        for ($i = 0; $i < count($request->ingredient_id); $i++) {
            $battery_details = new BatteryIngredientDetail();
            $battery_details->battery_id = $battery->id;
            $battery_details->ingredient_id = $request->ingredient_id[$i];
            $battery_details->quantity = $request->quantity[$i];
            $battery_details->wastage = $request->wastage[$i];
            $battery_details->created_at = Carbon::now();
            $battery_details->save();
        }

        $notification = array(
            'message' => 'Battery Added Successfully',
            'alert_type' => 'success',
        );
        return redirect()->route('battery.all')->with($notification);
    }

    public function BatteryView($id)
    {
        $batteryDetails = BatteryIngredientDetail::where('battery_id', $id)->get();
        $batteryInfo = BatteryIngredient::findOrFail($id);
        // dd($batterySingle);
        return view('admin.battery.battery_view', compact('batteryDetails', 'batteryInfo'));
    }

    public function GetProductWeight(Request $request)
    {
        $id =  $request->id;
        // dd($id);

        $products = Product::findOrFail($id);
        $weight = $products->weight;
        return response()->json($weight);
    }

    public function GetWastage(Request $request)
    {
        $id =  $request->id;
        // dd($id);
        $qty_with_wastage = $request->quantity;

        $products = Product::findOrFail($id);
        $weight = $products->weight;
        return response()->json($weight);
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
