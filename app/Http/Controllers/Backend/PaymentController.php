<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Models\SupplierPaymentDetail;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function CustomerLedgerList()
    {
        $customers = Customer::latest()->get();
        $title = 'Customer Ledger';
        $banks = Bank::OrderBy('name', 'asc')->get();
        return view('admin.ledger.customer.customer_ledger', compact('customers', 'title', 'banks'));
    }
    public function CustomerDuePayment(Request $request)
    {
        // dd($request->all());
        $due_amount = $request->due_amount;
        if ($request->paid_amount == null || $request->date == null || $request->bank_id == null) {
            return response()->json([
                'status' => 'null_error',
            ]);
        }

        if ($request->paid_amount > $due_amount) {
            return response()->json([
                'status' => 'error',
            ]);
        } else {
            $customer_id = $request->customer_id;

            $customer = Customer::findOrFail($customer_id);
            // dd($customer);
            $customer->total_due -= $request->paid_amount;
            $customer->update();



            $bank = Bank::findOrFail($request->bank_id);
            $bank->balance += $request->paid_amount;
            $bank->save();

            $bankDetails = new BankDetail();
            $bankDetails->bank_id = $request->bank_id;
            $bankDetails->trans_type = 'earning';
            $bankDetails->trans_head = 'Due Payment';
            $bankDetails->trans_id = 'customer-' . $customer_id;
            $bankDetails->note = $request->payment_note;
            $bankDetails->balance = $request->paid_amount;
            $bankDetails->date = $request->date;
            $bankDetails->status = '1';
            $bankDetails->save();

            $totalInvoice = Payment::where('customer_id', $customer_id)
                ->where('due_amount', '!=', '0')
                ->get();
            $paid_amount = $request->paid_amount;
            foreach ($totalInvoice as $invoice) {

                if ($paid_amount >= $invoice->due_amount) {
                    $invoice->due_amount = 0;
                    $invoice->update();

                    $payment_details = new PaymentDetail();
                    $payment_details->invoice_id = $invoice->id;
                    $payment_details->current_paid_amount = $invoice->due_amount;
                    $payment_details->date = date('Y-m-d', strtotime($request->date));
                    $payment_details->paid_status = 'partial';
                    $payment_details->bank_name = $request->bank_id;
                    $payment_details->note = $request->payment_note;
                    $payment_details->save();

                    $paid_amount = $paid_amount - $invoice->due_amount;
                } else {
                    $invoice->due_amount -= $paid_amount;
                    $invoice->update();

                    $payment_details = new PaymentDetail();
                    $payment_details->invoice_id = $invoice->id;
                    $payment_details->current_paid_amount = $paid_amount;
                    $payment_details->date = date('Y-m-d', strtotime($request->date));
                    $payment_details->paid_status = 'partial';
                    $payment_details->bank_name = $request->bank_id;
                    $payment_details->note = $request->payment_note;
                    $payment_details->save();
                    break;
                }
            }
            // $payment_details = new PaymentDetail();
            // $payment_details->invoice_id = $invoice->id;
            // $payment_details->date = date('Y-m-d', strtotime($request->date));
            // $payment_details->paid_status = 'partial';
            // $payment_details->bank_name = $request->bank_id;
            // $payment_details->note = $request->payment_note;
            // $payment_details->save();

            return response()->json([
                'status' => 'success',
            ]);
        }
    }

    public function CustomerPaymentHistory($id)
    {
        $totalOrder = Payment::where('payments.customer_id', $id)
            ->join('payment_details', 'payments.invoice_id', '=', 'payment_details.invoice_id')
            ->get(['payment_details.*']);
        $customerInfo = Customer::findOrFail($id);
        return view('admin.ledger.customer.customer_payment_history', compact('totalOrder', 'customerInfo'));
    }

    public function SupplierLedgerList()
    {
        $suppliers = Supplier::latest()->get();
        $title = 'Supplier Ledger';
        $banks = Bank::OrderBy('name', 'asc')->get();
        return view('admin.ledger.supplier.supplier_ledger', compact('suppliers', 'title', 'banks'));
    }

    public function SupplierDuePayment(Request $request)
    {
        // dd($request->all());
        $due_amount = $request->due_amount;

        if ($request->paid_amount == null || $request->date == null || $request->bank_id == null) {
            return response()->json([
                'status' => 'null_error',
            ]);
        }

        if ($request->paid_amount > $due_amount) {
            return response()->json([
                'status' => 'error',
            ]);
        } else {

            $bank = Bank::findOrFail($request->bank_id);
            if ($request->paid_amount > $bank->balance) {
                return response()->json([
                    'status' => 'bank_error',
                ]);
            } else {
                $supplier_id = $request->supplier_id;
                $supplier = Supplier::findOrFail($supplier_id);
                $supplier->total_due -= $request->paid_amount;
                $supplier->update();



                $bank->balance -= $request->paid_amount;
                $bank->save();

                $bankDetails = new BankDetail();
                $bankDetails->bank_id = $request->bank_id;
                $bankDetails->trans_type = 'asset';
                $bankDetails->trans_head = 'Due Payment';
                $bankDetails->trans_id = 'supplier-' . $supplier_id;
                $bankDetails->note = $request->payment_note;
                $bankDetails->balance = $request->paid_amount;
                $bankDetails->date = $request->date;
                $bankDetails->status = '1';
                $bankDetails->save();

                $allSuppliers = SupplierAccount::where('supplier_id', $supplier_id)
                    ->where('due_amount', '!=', '0')
                    ->get();
                $paid_amount = $request->paid_amount;
                foreach ($allSuppliers as $purchase) {

                    if ($paid_amount >= $purchase->due_amount) {
                        $paid_amount = $paid_amount - $purchase->due_amount;

                        $purchase->due_amount = 0;
                        $purchase->update();
                    } else {
                        $purchase->due_amount -= $paid_amount;
                        $purchase->update();

                        break;
                    }
                }
                return response()->json([
                    'status' => 'success',
                ]);
            }
        }
    }

    public function SupplierPaymentHistory($id)
    {
        $paymentSummery = SupplierPaymentDetail::where('supplier_id', $id)
            ->get();
        $supplierInfo = Supplier::findOrFail($id);
        return view('admin.ledger.supplier.supplier_payment_history', compact('paymentSummery', 'supplierInfo'));
    }
}
