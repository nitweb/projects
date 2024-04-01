<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BatteryIngredient;
use App\Models\BatteryIngredientDetail;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\PurchaseMeta;
use App\Models\Tax;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class ProductionController extends Controller
{

    public function ProductionAdd(){
        $customers = Customer::OrderBy('name', 'asc')->get();
        $categories = Category::OrderBy('name', 'asc')->get();
        $employees = Employee::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $accounts = Bank::OrderBy('name', 'asc')->get();
        $taxes = Tax::where('status', '1')->OrderBy('name', 'asc')->get();

        $ingredients = Ingredient::OrderBy('name', 'asc')->get();

        return view('admin.production.production_add', compact('customers', 'categories', 'products', 'accounts', 'taxes', 'employees', 'ingredients'));
    }

    public function GetIngredientStock($id)
    {
        $ingredientStock = BatteryIngredientDetail::where('ingredient_id', $id)->sum('quantity');
        return response()->json($ingredientStock);
    }

    public function GetIngredientList(Request $request){

        $cat_id = $request->category_id;
        $product_id = $request->product_id;

        $ingredient_list = BatteryIngredient::where('category_id', $cat_id)->where('product_id', $product_id)->get();

        $upInfo = BatteryIngredient::where('category_id', $cat_id)->where('product_id', $product_id)->first();

        foreach ($ingredient_list as $item) {
            $ingredient_details = BatteryIngredientDetail::where('battery_id', $item->id)->get();

            $ingredient_unit_price = PurchaseMeta::where('ingredient_id', $item->id)->first();
        }

        $ingredients = Ingredient::orderBy('name', 'ASC')->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $products = Product::orderBy('name', 'ASC')->get();
        $taxes = Tax::get();

        if ($ingredient_unit_price) {
            return view('admin.production.ingredient_list', compact('ingredient_details', 'ingredients', 'upInfo', 'categories', 'products', 'taxes', 'ingredient_unit_price'));

        } else {

            $notification = array(
                'message' => 'Please Purchase Ingredient First!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

    }
}
