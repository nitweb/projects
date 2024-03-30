<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use App\Models\AssetSubCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssetSubCategoryController extends Controller
{
    public function AllSubCategory()
    {
        $allSubCategory = AssetSubCategory::all();
        return view('admin.assets.asset_sub_category.all_sub_category', compact('allSubCategory'));
    } //end method


    public function AddSubCategory()
    {
        $categories = AssetCategory::OrderBy('name', 'asc')->get();
        return view('admin.assets.asset_sub_category.add_sub_category', compact('categories'));
    }

    public function StoreSubCategory(Request $request)
    {
        $assetSubCat = new AssetSubCategory();

        $assetSubCat->name = $request->name;
        $assetSubCat->cat_id = $request->cat_id;
        $assetSubCat->created_at = Carbon::now();
        $assetSubCat->save();

        $notification = array(
            'message' => 'Sub Category Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('asset.all.sub.category')->with($notification);
    } //end method


    public function EditSubCategory($id)
    {
        $subCatInfo = AssetSubCategory::findOrFail($id);
        $categories = AssetCategory::OrderBy('name', 'asc')->get();
        return view('admin.assets.asset_sub_category.edit_sub_category', compact('subCatInfo', 'categories'));
    } //end method

    public function UpdateSubCategory(Request $request)
    {
        $categoryId = $request->id;

        $assetsSubCat = AssetSubCategory::findOrFail($categoryId);

        $assetsSubCat->name = $request->name;
        $assetsSubCat->cat_id = $request->cat_id;
        $assetsSubCat->update();

        $notification = array(
            'message' => 'Sub Category Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('asset.all.sub.category')->with($notification);
    } //end method

    public function DeleteSubCategory($id)
    {
        AssetSubCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Sub Category Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('asset.all.sub.category')->with($notification);
    } //end method



    public function GetSubCategory(Request $request)
    {
        $category_id = $request->cat_id;
        $subCategory = AssetSubCategory::where('cat_id', $category_id)->get();
        return response()->json($subCategory);
    }
}
