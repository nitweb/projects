<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PackagingStore;
use App\Models\PurchaseStore;
use App\Models\Warehouse;
use App\Models\WarehouseMeta;
use App\Models\WarehouseStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Nette\Utils\Floats;

class WarehouseController extends Controller
{
    public function AllWarehouse()
    {
        $allWarehouse = Warehouse::OrderBy('name', 'asc')->get();
        $title = 'Warehouse';
        return view('admin.warehouse.all_warehouse', compact('allWarehouse', 'title'));
    }

    public function AddWarehouse()
    {
        $title = 'Add Warehouse';
        return view('admin.warehouse.add_warehouse', compact('title'));
    }

    public function StoreWarehouse(Request $request)
    {
        $warehouse = new Warehouse();
        $warehouse->name = $request->name;
        $warehouse->email = $request->email;
        $warehouse->phone = $request->phone;
        $warehouse->address = $request->address;
        $warehouse->created_at = Carbon::now();
        $warehouse->save();

        $notfication = array(
            'messaage' => 'Warehouse Added Successfully',
            'alert_type' => 'Success',
        );

        return redirect()->route('all.warehouse')->with($notfication);
    }

    public function EditWarehouse($id)
    {
        $warehouseInfo = Warehouse::findOrFail($id);
        $title = 'Update Warehouse';
        return view('admin.warehouse.edit_warehouse', compact('warehouseInfo', 'title'));
    }

    public function UpdateWarehouse(Request $request)
    {
        $warehouse = Warehouse::findOrFail($request->id);
        $warehouse->name = $request->name;
        $warehouse->email = $request->email;
        $warehouse->phone = $request->phone;
        $warehouse->address = $request->address;
        $warehouse->update();

        $notfication = array(
            'messaage' => 'Warehouse Updated Successfully',
            'alert_type' => 'Success',
        );

        return redirect()->route('all.warehouse')->with($notfication);
    }


    public function WarehouseStockHistory($id)
    {
        $warehouseInfo = Warehouse::findOrFail($id);
        $warehouseStockInfo = WarehouseStock::where('warehouse_id', $id)->latest()->get();
        return view('admin.warehouse.stock_history_warehouse', compact('warehouseStockInfo', 'warehouseInfo'));
    }

    public function DeleteWarehouse($id)
    {
        Warehouse::findOrFail($id)->delete();

        $notfication = array(
            'messaage' => 'Warehouse Deleted Successfully',
            'alert_type' => 'Success',
        );
        return redirect()->back()->with($notfication);
    }







     public function getPacketStock(Request $request)
    {
        // dd($request->all());
        $warehouse_id = $request->warehouse_id;
        $product_id = $request->product_id;

        if ($warehouse_id == '0') {
            $innerStore = PackagingStore::where('product_id', $product_id)
                ->where('quantity', '!=', 0)
                ->where('package_type', 'Inner')
                ->sum('quantity');
            $masterStore = PackagingStore::where('product_id', $product_id)
                ->where('quantity', '!=', 0)
                ->where('package_type', 'Master')
                ->sum('quantity');

            // dd($masterStore);
            return response()->json(
                array(
                    'inner' => $innerStore,
                    'master' => $masterStore,
                )
            );
        } else {
            $innerStore = WarehouseStock::where('product_id', $request->product_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->where('quantity', '!=', 0)
                ->where('package_type', 'Inner')
                ->sum('quantity');
            $masterStore = WarehouseStock::where('product_id', $request->product_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->where('quantity', '!=', 0)
                ->where('package_type', 'Master')
                ->sum('quantity');

            return response()->json(
                array(
                    'inner' => $innerStore,
                    'master' => $masterStore,
                )
            );
        }
    }

    public function getPacketPrice(Request $request)
    {
        $warehouse_id = $request->warehouse_id;
        $product_qty = $request->product_qty;
        $unit_price = $request->unit_pirce;


        if ($warehouse_id == '0') {
            $innerStore = PackagingStore::where('product_id', $request->product_id)
                ->where('quantity', '!=', 0)
                ->where('package_type', 'Inner')
                ->get();
            $masterStore = PackagingStore::where('product_id', $request->product_id)
                ->where('quantity', '!=', 0)
                ->where('package_type', 'Master')
                ->get();
            $innerStockRequest =  $request->inner_qty;
            $masterStockRequest =  $request->master_qty;
            $innerTotal = 0;
            $masterTotal = 0;
            foreach ($innerStore as $innerStock) {
                if ((int) $innerStockRequest > (int) $innerStock->quantity) {
                    $innerStockRequest = abs($innerStockRequest - (int) $innerStock->quantity);

                    $innerTotal += $innerStock->quantity * $innerStock->unit_price; //50
                } else {
                    $innerTotal +=  $innerStockRequest * $innerStock->unit_price;
                    break;
                }
            }
            foreach ($masterStore as $masterStock) {
                if ((int) $masterStockRequest > (int) $masterStock->quantity) {
                    $masterStockRequest = abs($masterStockRequest - (int) $masterStock->quantity);

                    $masterTotal += $masterStock->quantity * $masterStock->unit_price;
                } else {
                    $masterTotal +=  $masterStockRequest * $masterStock->unit_price;
                    break;
                }
            }

            $innerPrice = $innerTotal / $request->inner_qty;
            $masterPrice = $masterTotal / $request->master_qty;
            $per_master_cartoon = $masterPrice / $product_qty;

            $update_price = $innerPrice + $per_master_cartoon + $unit_price;
            // $allTotal =  round($update_price * $product_qty, 2);
            $allTotal =  round($update_price * $product_qty, 2);
            return response()->json($allTotal);
        } else {

            $innerStore = WarehouseStock::where('product_id', $request->product_id)
                ->where('quantity', '!=', 0)
                ->where('package_type', 'Inner')
                ->get();
            $masterStore = WarehouseStock::where('product_id', $request->product_id)
                ->where('quantity', '!=', 0)
                ->where('package_type', 'Master')
                ->get();
            $innerStockRequest =  $request->inner_qty;
            $masterStockRequest =  $request->master_qty;
            $innerTotal = 0;
            $masterTotal = 0;
            foreach ($innerStore as $innerStock) {
                if ((int) $innerStockRequest > (int) $innerStock->quantity) {
                    $innerStockRequest = abs($innerStockRequest - (int) $innerStock->quantity);

                    $innerTotal += $innerStock->quantity * $innerStock->unit_price; //50
                } else {
                    $innerTotal +=  $innerStockRequest * $innerStock->unit_price;
                    break;
                }
            }
            foreach ($masterStore as $masterStock) {
                if ((int) $masterStockRequest > (int) $masterStock->quantity) {
                    $masterStockRequest = abs($masterStockRequest - (int) $masterStock->quantity);

                    $masterTotal += $masterStock->quantity * $masterStock->unit_price;
                } else {
                    $masterTotal +=  $masterStockRequest * $masterStock->unit_price;
                    break;
                }
            }

            $innerPrice = $innerTotal / $request->inner_qty;
            $masterPrice = $masterTotal / $request->master_qty;
            $per_master_cartoon = $masterPrice / $product_qty;

            $update_price = $innerPrice + $per_master_cartoon + $unit_price;
            $allTotal = round($update_price * $product_qty, 2);
            return response()->json($allTotal);
        }
    }
}
