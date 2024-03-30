<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Battery;
use App\Models\BatteryIngredient;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\PurchaseStore;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function ProductAll()
    {
        $productAll = Product::all();
        return view('admin.product.product_all', compact('productAll'));
    }


    public function ProductAdd()
    {
        $categories = Category::all();
        $units = Unit::orderBy('name', 'asc')->get();
        return view('admin.product.product_add', compact('categories', 'units'));
    }

    public function ProductStore(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|unique:products',
            ],
            [
                'name.unique' => 'Product nmae already exists!',
            ]

        );

        $product = new Product();
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->unit_id = $request->unit_id;
        $product->weight = $request->weight;
        $product->quantity = '0';
        $product->created_by = Auth::user()->id;
        $product->created_at = Carbon::now();
        $product->save();

        $notification = array([
            'message' => 'Product Added Successfully',
            'alert_type' => 'success',
        ]);
        return redirect()->route('product.all')->with($notification);
    }

    public function ProductEdit($id)
    {
        $category = Category::all();
        $product = Product::findOrFail($id);
        $units = Unit::all();
        return view('admin.product.product_edit', compact('category', 'product', 'units'));
    }

    public function ProductUpdate(Request $request)
    {
        $product_id = $request->id;
        Product::findOrFail($product_id)->update([
            'name' => $request->name,
            'unit_id' => $request->unit_id,
            'weight' => $request->weight*1000,
            'category_id' => $request->category_id,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array([
            'message' => 'Product Updated Successfully',
            'alert_type' => 'info',
        ]);
        return redirect()->route('product.all')->with($notification);
    }

    public function ProductDelete($id)
    {
        Product::findOrFail($id)->delete();
        BatteryIngredient::where('product_id', $id)->delete();
        $notification = array([
            'message' => 'Product Deleted Successfully',
            'alert_type' => 'success',
        ]);
        return redirect()->back()->with($notification);
    }

    public function GetSubCategory(Request $request)
    {
        $category_id = $request->category_id;
        $allSubCat = SubCategory::where('category_id', $category_id)->get();
        return response()->json($allSubCat);
    }

    public function GetProductStock($id)
    {
        // $productStock = PurchaseStore::where('product_id', $id)->get();
        $productStock = PurchaseStore::where('product_id', $id)->sum('quantity');
        return response()->json($productStock);
    }
    // public function GetProductStock($id)
    // {
    //     $productStock = Product::where('id', $id)->get();
    //     return response()->json($productStock);
    // }

    public function GetProductForSales(Request $request)
    {
        $id =  $request->id;
        $supplier_id = $request->supplier_id;

        $products = Product::where('category_id', $id)->get();
        return response()->json($products);
    }


    // product stock
    public function ProductStockAll()
    {
        $products = Product::OrderBy('name', 'asc')->get();
        return view('admin.stock.stock_all', compact('products'));
    }
}
