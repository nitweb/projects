<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountDetail;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function CustomerAll()
    {
        $allData = Customer::where('status', '1')->get();
        $title = 'All Cusotmer';
        return view('admin.customer_page.all_customer', compact('allData', 'title'));
    }
    public function CustomerAdd()
    {
        $employees = Employee::OrderBy('name', 'asc')->get();
        return view('admin.customer_page.add_customer', compact('employees'));
    }

    public function CustomerStore(Request $request)
    {
        $customer = Customer::orderBy('id', 'desc')->first();
        if ($customer == null) {
            $firstReg = '0';
            $customerId = $firstReg + 1;
        } else {
            $customer = Customer::orderBy('id', 'desc')->first()->id;
            $customerId = $customer + 1;
        }

        if ($customerId < 10) {
            $id_no = '000' . $customerId; //0009
        } elseif ($customerId < 100) {
            $id_no = '00' . $customerId; //0099
        } elseif ($customerId < 1000) {
            $id_no = '0' . $customerId; //0999
            $id_no = '0' . $customerId; //0999
        }

        $check_year = date('Y');

        $name = $request->name;
        $words = explode(' ', $name);
        $acronym = '';
        foreach ($words as $w) {
            $acronym .= mb_substr($w, 0, 1);
        }

        $customer_id = $acronym . '-' . $check_year . '.' . $id_no;

        Customer::insert([
            'name' => $request->name,
            'customer_id' => $customer_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'telephone' => $request->telephone,
            'address' => $request->address,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Customer Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.customer')->with($notification);
    } //end method


    public function CustomerEdit($id)
    {
        $customerInfo = Customer::findOrFail($id);
        return view('admin.customer_page.edit_customer', compact('customerInfo'));
    }

    public function CustomerUpdate(Request $request)
    {
        $customerId = $request->id;
        Customer::findOrFail($customerId)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'telephone' => $request->telephone,
            'address' => $request->address,
        ]);

        $notification = array(
            'message' => 'Customer Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function CustomerDelete($id)
    {

        $invoiceInfo = Invoice::where('customer_id', $id)->get();

        foreach ($invoiceInfo as $invoice) {
            PaymentDetail::where('invoice_id', $invoice->id)->delete();
        }

        Customer::findOrFail($id)->delete();
        Invoice::where('customer_id', $id)->delete();
        InvoiceDetail::where('customer_id', $id)->delete();
        Payment::where('customer_id', $id)->delete();
        AccountDetail::where('customer_id', $id)->delete();

        $notification = array(
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.customer')->with($notification);
    }


    public function CustomerBillDelete($id)
    {
        $invoiceInfo = Invoice::where('customer_id', $id)->get();

        foreach ($invoiceInfo as $invoice) {
            PaymentDetail::where('invoice_id', $invoice->id)->delete();
        }

        Invoice::where('customer_id', $id)->delete();
        InvoiceDetail::where('customer_id', $id)->delete();
        Payment::where('customer_id', $id)->delete();
        AccountDetail::where('customer_id', $id)->delete();

        $notification = array(
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    // credit compnay method
    public function CreditCustomer()
    {
        $allData = Payment::whereIn('paid_status', ['partial_paid', 'full_due'])->where('due_amount', '!=', '0')->get();
        return view('admin.customer_page.credit_customer', compact('allData'));
    }


    public function EditCreditCustomerInvoice($invoice_id)
    {
        $payment = Payment::where('invoice_id', $invoice_id)->first();
        return view('admin.customer_page.edit_customer_invoice', compact('payment'));
    }

    public function UpdateCustomerInvoice(Request $request, $invoice_id)
    {
        if ($request->new_paid_amount < $request->paid_amount) {
            $notification = array(
                'message' => 'Sorry, You Paid maximum amount!',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {
            $payment = Payment::where('invoice_id', $invoice_id)->first();
            $payment_details = new PaymentDetail();
            $payment->paid_status = $request->paid_status;

            if ($request->paid_status == 'full_paid') {
                $payment->paid_amount = Payment::where('invoice_id', $invoice_id)->first()['paid_amount'] + $request->new_paid_amount;
                $payment->due_amount = '0';
                $payment->check_number = $request->check_number;
                $payment_details->current_paid_amount = $request->new_paid_amount;
            } elseif ($request->paid_status == 'partial_paid') {
                $payment->paid_amount = Payment::where('invoice_id', $invoice_id)->first()['paid_amount'] + $request->paid_amount;
                $payment->due_amount = Payment::where('invoice_id', $invoice_id)->first()['due_amount'] - $request->paid_amount;
                $payment->check_number = $request->check_number;
                $payment_details->current_paid_amount = $request->paid_amount;
            }

            $payment->save();
            $payment_details->invoice_id = $invoice_id;
            $payment_details->date = date('Y-m-d', strtotime($request->date));
            $payment_details->updated_by = Auth::user()->id;
            $payment_details->save();

            $notification = array(
                'message' => 'Payment Updated Successfully!',
                'alert_type' => 'success',
            );
            return redirect()->route('all.company')->with($notification);
        }
    }


    public function CustomerInvoiceDetails($invoice_id)
    {
        $payment = Payment::where('invoice_id', $invoice_id)->first();
        // dd($payment);
        return view('admin.pdf.invoice_details_pdf', compact('payment'));
    }


    public function CustomerBill($id)
    {
        $allData = Invoice::orderBy('date', 'desc')->orderBy('invoice_no', 'desc')->where('customer_id', $id)->where('status', '1')->get();
        // $allData = InvoiceDetail::orderBy('date', 'desc')->orderBy('invoice_no_gen', 'desc')->where('company_id', $id)->get();
        // // dd($allData);
        return view('admin.customer_page.customer_invoice', compact('allData', 'id'));
    }
}
