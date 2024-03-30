<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Expense;
use App\Models\Liability;
use Carbon\Carbon;

class LiabilityController extends Controller
{
    public function LiabilityAll()
    {
        $LiabilityAll = Liability::get();
        $accounts = Bank::OrderBy('name', 'ASC')->get();
        return view('admin.liability.liability_all', compact('LiabilityAll', 'accounts'));
    } //end method

    public function LiabilityAdd()
    {
        return view('admin.liability.liability_add');
    } //end method

    public function LiabilityStore(Request $request)
    {
        $liability = new Liability();

        $liability->head = $request->head;
        $liability->source = $request->source;
        $liability->condition = $request->condition;
        $liability->amount = $request->amount;
        $liability->date = $request->date;
        $liability->status = $request->status;
        $liability->liabilities = $request->liabilities;
        $liability->created_at = Carbon::now();
        $liability->save();

        $notification = array(
            'message' => 'Liability  Inserted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('liability.all')->with($notification);
    }

    public function LiabilityEdit($id)
    {
        $LiabilityInfo = Liability::findOrFail($id);
        return view('admin.liability.liability_edit', compact('LiabilityInfo'));
    }

    public function LiabilityUpdate(Request $request)
    {
        $LiabilityId  =  $request->id;

        $liabilities = Liability::findOrFail($LiabilityId);

        $liabilities->head = $request->head;
        $liabilities->source = $request->source;
        $liabilities->condition = $request->condition;
        $liabilities->amount = $request->amount;
        $liabilities->date = $request->date;
        $liabilities->status = $request->status;
        $liabilities->liabilities = $request->liabilities;
        $liabilities->update();


        $notification = array(
            'message' => 'Liability Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('liability.all')->with($notification);
    } //end method

    public function LiabilityDelete($id)
    {
        Liability::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Liability Deleted with SubLiability Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('liability.all')->with($notification);
    }


    public function LiabilityPayment(Request $request)
    {
        $id = $request->liability_id;
        $bank_id = $request->bank_id;
        $bank = Bank::findOrFail($bank_id);
        $liability_payment = Liability::findOrFail($id);

        $expense = new Expense();

        if ($bank->balance >= $request->amount) {

            if ($request->amount <= $liability_payment->amount) {
                $liability_payment->amount -= (float)$request->amount;
                $liability_payment->update();
                $bank->balance -= (float)$request->amount;
                $bank->update();

                $expense->head = 'Liability Payment';
                $expense->description = $liability_payment->name;
                $expense->amount = $request->amount;
                $expense->date = $request->date;
                $expense->bank_id = $bank->id;
                $expense->created_at = Carbon::now();
                $expense->save();

                return response()->json([
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'liability' => 'error-liability',
                ]);
            }
        } else {

            return response()->json([
                'status' => 'error',
            ]);
        }
    }
}
