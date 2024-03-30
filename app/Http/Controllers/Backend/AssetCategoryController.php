<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use App\Models\AssetSubCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssetCategoryController extends Controller
{
    public function CategoryAll()
    {
        $categoryAll = AssetCategory::get();
        return view('admin.assets.asset_category.category_all', compact('categoryAll'));
    } //end method

    public function CategoryAdd()
    {
        return view('admin.assets.asset_category.category_add');
    } //end method

    public function CategoryStore(Request $request)
    {
        $assetCat = new AssetCategory();

        $assetCat->name = $request->name;
        $assetCat->created_at = Carbon::now();
        $assetCat->save();

        $notification = array(
            'message' => 'Category  Inserted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('asset.category.all')->with($notification);
    }

    public function CategoryEdit($id)
    {
        $categoryInfo = AssetCategory::findOrFail($id);
        return view('admin.assets.asset_category.category_edit', compact('categoryInfo'));
    }

    public function CategoryUpdate(Request $request)
    {
        $categoryId  =  $request->id;
        $AssetCat = AssetCategory::findOrFail($categoryId);

        $AssetCat->name = $request->name;
        $AssetCat->update();

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('asset.assets.category.all')->with($notification);
    } //end method

    public function CategoryDelete($id)
    {
        $assetCat = AssetCategory::findOrFail($id);
        $assetCat->delete();
        AssetSubCategory::where('cat_id', $id)->delete();

        $notification = array(
            'message' => 'Category Deleted with SubCategory Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('asset.category.all')->with($notification);
    }
}
