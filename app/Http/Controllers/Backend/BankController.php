<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankDetail;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BankController extends Controller
{
    public function AllAccount()
    {
        $allBank = Bank::OrderBy('name', 'asc')->get();
        $title = 'Account';
        return view('admin.accounts.bank.all_bank', compact('allBank', 'title'));
    }

    public function AddAccount()
    {
        $title = 'Add Account';
        return view('admin.accounts.bank.add_bank', compact('title'));
    }

    public function StoreAccount(Request $request)
    {
        $validated = $request->validate(
            [
                'account_number' => 'required|unique:banks|max:255',
            ],
            [
                'account_number' => 'Account number already exits!',
            ]
        );

        $bank = new Bank();
        $bank->name = $request->bank_name;
        $bank->account_number = $request->account_number;
        $bank->branch_name = $request->branch_name;
        $bank->balance = $request->opening_balance;
        $bank->status = '1';
        $bank->created_at = Carbon::now();
        $bank->save();

        $bankDetails = new BankDetail();
        $bankDetails->bank_id = $bank->id;
        $bankDetails->trans_type = 'earning';
        $bankDetails->trans_head = 'Opening Balance';
        $bankDetails->balance = $request->opening_balance;
        $bankDetails->status = '1';
        $bankDetails->date = Carbon::now();
        $bankDetails->created_at = Carbon::now();
        $bankDetails->save();

        $notification = array(
            'message' => 'Bank Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.account')->with($notification);
    }

    public function EditAccount($id)
    {
        $title = 'Update Account';
        $bankInfo = Bank::findOrFail($id);
        return view('admin.accounts.bank.edit_bank', compact('title', 'bankInfo'));
    }

    public function UpdateAccount(Request $request)
    {
        $bankId = $request->id;
        $bank =  Bank::findOrFail($bankId);
        $bank->name = $request->bank_name;
        $bank->account_number = $request->account_number;
        $bank->branch_name = $request->branch_name;
        $bank->balance = $request->opening_balance;
        $bank->status = '1';
        $bank->update();

        $notification = array(
            'message' => 'Bank Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.account')->with($notification);
    }


    public function DeleteAccount($id)
    {
        $bank = Bank::findOrFail($id);
        BankDetail::where('bank_id', $bank->id)->delete();
        $bank->delete();

        $notification = array(
            'message' => 'Account Deleted Successfully with all transaction',
            'alert-type' => 'success'
        );
        return redirect()->route('all.account')->with($notification);
    }



    // transder amount
    public function TransferAmount()
    {
        $title = 'Transfer Amount';
        $accounts = Bank::OrderBy('name', 'asc')->get();
        return view('admin.accounts.bank.transfer_amount', compact('accounts', 'title'));
    }

    public function StoreTransferAmount(Request $request)
    {
        // dd($request->all());

        $from_bank_id = $request->from_bank_id;
        $to_bank_id = $request->to_bank_id;
        $fromBank = Bank::findOrFail($from_bank_id);
        $toBank = Bank::findOrFail($to_bank_id);

        if ($from_bank_id == $to_bank_id) {
            $notification = array(
                'message' => "Sorry, You're selected same Bank'",
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        } elseif ($fromBank->balance >= $request->amount) {

            $fromBank->balance -= $request->amount;
            $fromBank->update();

            $bankDetails = new BankDetail();
            $bankDetails->bank_id = $request->to_bank_id;
            $bankDetails->trans_head = 'Transfer';
            $bankDetails->balance = $request->amount;
            $bankDetails->status = '1';
            $bankDetails->date = $request->date;
            $bankDetails->created_at = Carbon::now();
            $bankDetails->save();

            $toBank->balance += $request->amount;
            $toBank->update();

            $notification = array(
                'message' => 'New Amount Transfer Successfully',
                'alert_type' => 'success'
            );
            return redirect()->route('all.account')->with($notification);
        } else {
            $notification = array(
                'message' => 'Insufficient Balance on selected Account',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function TransferList()
    {
        $title = 'Transaction';
        $transactionList = BankDetail::latest()->get();
        return view('admin.accounts.bank.transaction_list', compact('transactionList', 'title'));
    }

    // deposit amount
    public function DepositAmount(Request $request)
    {
        // dd($request->all());
        if ($request->deposit_amount == null || $request->date == null) {
            return response()->json([
                'status' => 'error',
            ]);
        } else {
            $bank = Bank::findOrFail($request->account_id);
            $bank->balance += $request->deposit_amount;
            $bank->save();

            $bankDetails = new BankDetail();
            $bankDetails->bank_id = $request->account_id;
            $bankDetails->trans_type = 'earning';
            $bankDetails->trans_head = 'Deposit';
            $bankDetails->trans_id = $request->account_id;
            $bankDetails->note = $request->deposit_note;
            $bankDetails->balance = $request->deposit_amount;
            $bankDetails->date = $request->date;
            $bankDetails->status = '1';
            $bankDetails->save();

            return response()->json([
                'status' => 'success',
            ]);
        }
    }
}
