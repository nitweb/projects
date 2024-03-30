<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetSubCategory;
use App\Models\Expense;
use App\Models\AssetDepreciation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssetController extends Controller
{
    public function AssetAll()
    {
        $AssetAll = Asset::get();
        return view('admin.assets.asset.asset_all', compact('AssetAll'));
    } //end method

    public function AssetAdd()
    {
        $categories = AssetCategory::get();
        $sub_cat = AssetSubCategory::get();
        return view('admin.assets.asset.asset_add', compact('categories', 'sub_cat'));
    } //end method

    

    public function AssetStore(Request $request)
    {
        $asset = new Asset();

        $asset->name = $request->name;
        $asset->cat_id = $request->cat_id;
        $asset->sub_cat_id = $request->sub_cat_id;
        $asset->price = $request->price;
        $asset->longevity = $request->longevity;
        $asset->type = $request->type;
        $asset->status = $request->status;
        $asset->purchase_date = $request->purchase_date;
        $asset->status = $request->status;
        $asset->created_at = Carbon::now();
        $asset->save();
        
        $assetInfo = Asset::findOrFail($asset->id);
        if ($assetInfo->longevity != NULL) {
            $purchase_month = date('m', strtotime($assetInfo->purchase_date));
            $longevity = $assetInfo->longevity;
            $count = $purchase_month;
            $dt = date(strtotime($assetInfo->purchase_date));
            for ($i = $count; $i <= $longevity; $i++) {

                $depreciation = new AssetDepreciation();
                $depreciation->asset_id = $assetInfo->id;
                $depreciation->name = $assetInfo->name;
                $depreciation->amount = round($assetInfo->price / $assetInfo->longevity, 2);
                $depreciation->date = date("Y-m-d", strtotime("+$i month", $dt));
                $depreciation->month = date('m', strtotime($depreciation->date));
                $depreciation->year = date('Y', strtotime($depreciation->date));
                $depreciation->created_at = date("Y-m-d", strtotime("+$i month", $dt));
                $depreciation->save();

            }
        }
        
        $notification = array(
            'message' => 'Asset Inserted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('asset.all')->with($notification);
    }

    public function AssetEdit($id)
    {
        $AssetInfo = Asset::findOrFail($id);
        $categories = AssetCategory::get();
        $subcategories = AssetSubCategory::get();
        return view('admin.assets.asset.Asset_edit', compact('AssetInfo', 'categories', 'subcategories'));
    }

    public function AssetUpdate(Request $request)
    {
        $AssetId  =  $request->id;

        $assets = Asset::findOrFail($AssetId);

        $assets->name = $request->name;
        $assets->cat_id = $request->cat_id;
        $assets->sub_cat_id = $request->sub_cat_id;
        $assets->price = $request->price;
        $assets->longevity = $request->longevity;
        $assets->type = $request->type;
        $assets->status = $request->status;
        $assets->purchase_date = $request->purchase_date;
        $assets->update();

        $notification = array(
            'message' => 'Asset Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('asset.all')->with($notification);
    } //end method

    public function AssetDelete($id)
    {
        Asset::findOrFail($id)->delete();
        // AssetSubAsset::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Asset Deleted with SubAsset Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('asset.all')->with($notification);
    }
    
    
    public function DepreciationList()
    {
        $depreciation = AssetDepreciation::whereDate('date', '<=', date('Y-m-d'))->get();
        return view('admin.assets.asset.depreciation_list', compact('depreciation'));
    }
    
     public function GetDepreciation(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;



        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allDepreciation = AssetDepreciation::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get(['name','amount','date']);
        }

        return view('admin.assets.asset.search_depreciation_result', compact('allDepreciation', 'start_date', 'end_date'));
    }
}
