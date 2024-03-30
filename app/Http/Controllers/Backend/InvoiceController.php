<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\InvoicePdfMail;
use App\Models\AccountDetail;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Category;
use App\Models\commissionDetail;
use App\Models\commissionTarget;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\PurchaseStore;
use App\Models\salesComissionMeta;
use App\Models\SalesCommission;
use App\Models\SalesProfit;
use App\Models\Tax;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{

    public function InvoiceAll()
    {
        $allInvoice = Invoice::where('status', '1')->latest()->get();
        return view('admin.invoice.invoice_all', compact('allInvoice'));
    }

    public function InvoiceAdd()
    {
        $customers = Customer::OrderBy('name', 'asc')->get();
        $categories = Category::OrderBy('name', 'asc')->get();
        $employees = Employee::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $invoice_no = $this->UniqueNumber();
        $accounts = Bank::OrderBy('name', 'asc')->get();
        $taxes = Tax::where('status', '1')->OrderBy('name', 'asc')->get();
        return view('admin.invoice.invoice_add', compact('invoice_no', 'customers', 'categories', 'products', 'accounts', 'taxes', 'employees'));
    }

    public function InvoiceStore(Request $request)
    {

        // dd($request->all());
        $GLOBALS['invoicesStatus'] = '1';
        if ($request->paid_amount > $request->estimated_amount) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

            if ($request->customer_id == '0') {
                $customer = new Customer();
                $customer->name = $request->customer_name;
                $customer->name = $request->customer_email;
                $customer->phone = $request->customer_phone;
                $customer->employee_id = $request->employee_id;
                $customer->created_at = Carbon::now();
                $customer->save();
                $customer_id = $customer->id;
            } else {
                $customer_id = $request->customer_id;
            }


            $invoice = new Invoice();
            $invoice->invoice_no = $request->invoice_no;
            $invoice->date = $request->date;
            $invoice->customer_id = $customer_id;
            $invoice->created_by = Auth::user()->id;
            $invoice->created_at = Carbon::now();
            $invoice->status = '0';
            $invoice->save();


            DB::transaction(function () use ($request, $invoice) {
                if ($invoice->save()) {
                    $count_category = count($request->category_id);

                    for ($i = 0; $i < $count_category; $i++) {

                        $invoice_details = new InvoiceDetail();
                        $invoice_details->date = date('Y-m-d', strtotime($request->date));
                        $invoice_details->invoice_id = $invoice->id;
                        $invoice_details->category_id = $request->category_id[$i];
                        $invoice_details->product_id = $request->product_id[$i];
                        $invoice_details->selling_qty = $request->selling_qty[$i];
                        $invoice_details->unit_price = $request->unit_price[$i];
                        $invoice_details->selling_price = $request->selling_price[$i];
                        $invoice_details->save();



                        $employeeCommission = SalesCommission::where('employee_id', $request->employee_id)->first();
                        if ($employeeCommission == null) {
                            $employeeCommission = new SalesCommission();
                            $employeeCommission->employee_id = $request->employee_id;
                            $employeeCommission->total = 0;
                            $employeeCommission->status = '1';
                            $employeeCommission->created_at = Carbon::now();
                            $employeeCommission->save();

                            $salesComissionDetails = new commissionDetail();
                            $salesComissionDetails->employee_id = $request->employee_id;
                            $salesComissionDetails->category_id = $invoice_details->category_id;
                            $salesComissionDetails->invoice_id = $invoice->id;
                            $salesComissionDetails->amount = $invoice_details->selling_price;
                            $salesComissionDetails->date = $request->date;
                            $salesComissionDetails->created_at = Carbon::now();
                            $salesComissionDetails->save();

                            $salesComissionMeta = new salesComissionMeta();
                            $salesComissionMeta->employee_id = $request->employee_id;
                            $salesComissionMeta->category_id = $invoice_details->category_id;
                            $salesComissionMeta->invoice_id = $invoice->id;
                            $salesComissionMeta->date = $request->date;
                            $salesComissionMeta->amount = $invoice_details->selling_price;
                            $salesComissionMeta->created_at = Carbon::now();
                            $salesComissionMeta->save();
                        } else {

                            $salesComissionDetails = new commissionDetail();
                            $salesComissionDetails->employee_id = $request->employee_id;
                            $salesComissionDetails->category_id = $invoice_details->category_id;
                            $salesComissionDetails->invoice_id = $invoice->id;
                            $salesComissionDetails->amount = $invoice_details->selling_price;
                            $salesComissionDetails->date = $request->date;
                            $salesComissionDetails->created_at = Carbon::now();
                            $salesComissionDetails->save();

                            $salesComissionMeta = new salesComissionMeta();
                            $salesComissionMeta->employee_id = $request->employee_id;
                            $salesComissionMeta->category_id = $invoice_details->category_id;
                            $salesComissionMeta->invoice_id = $invoice->id;
                            $salesComissionMeta->amount = $invoice_details->selling_price;
                            $salesComissionMeta->date = $request->date;
                            $salesComissionMeta->created_at = Carbon::now();
                            $salesComissionMeta->save();

                            $salesTarget = commissionTarget::where('employee_id', $request->employee_id)
                                ->where('category_id', $invoice_details->category_id)
                                ->where('status', '1')
                                ->first();
                            $salesComissionTotal = salesComissionMeta::where('employee_id', $request->employee_id)
                                ->where('category_id', $invoice_details->category_id)
                                ->where('amount', '!=', '0')
                                ->sum('amount');

                            if ($salesTarget != null) {
                                if ($salesComissionTotal >= $salesTarget->sales_target) {
                                    $commissionAmount = ($salesComissionTotal * $salesTarget->sales_commission) / 100;
                                    $employeeCommission->total = round($commissionAmount);
                                    $employeeCommission->update();
                                }
                            }
                        }


                        // product stock updated
                        // $product = Product::where('id', $invoice_details->product_id)->first();
                        // if (((float) $request->selling_qty[$i]) > ((float) $product->quantity)) {
                        //     Invoice::findOrFail($invoice->id)->delete();
                        //     InvoiceDetail::where('invoice_id', $invoice->id)->delete();
                        //     $errorNotification = array(
                        //         'message' => 'Sorry, Request Stock is not available',
                        //         'alert-type' => 'error',
                        //     );
                        //     return redirect()->back()->with($errorNotification);
                        // } else {
                        //     $product->quantity = ((float) $product->quantity) - ((float) $request->selling_qty[$i]);
                        //     $product->save();
                        //     $invoice_details->save();
                        //     $invoice->status = '1';
                        //     $invoice->save();

                        //     // profit calculation

                        // }
                        $purchaseQty = PurchaseStore::where('product_id', $invoice_details->product_id)->where('quantity', '!=', 0)->get();

                        if ($purchaseQty->sum('quantity') >=  $invoice_details->selling_qty) {
                            $fifoStock = ((float) $request->selling_qty[$i]); //200 //165
                            foreach ($purchaseQty as  $purchaseInfo) {

                                $totalQty = InvoiceDetail::where('invoice_id', $invoice->id)->sum('selling_qty');
                                if ($request->discount_type != null) {
                                    $discount_per_qty = $request->discount_amount / $totalQty;
                                } else {
                                    $discount_per_qty = 0;
                                }

                                $salesProfit = new SalesProfit();
                                $salesProfit->invoice_id = $invoice->id;
                                $salesProfit->purchase_id = $purchaseInfo->purchase_id;
                                $salesProfit->product_id = $request->product_id[$i];
                                $salesProfit->unit_price_purchase = (float) $purchaseInfo->unit_price;
                                $salesProfit->unit_price_sales =  (float) $request->unit_price[$i];
                                $salesProfit->discount_per_unit = round($discount_per_qty, 2);
                                $salesProfit->date  = date('Y-m-d', strtotime($request->date));




                                if ((float) $request->selling_qty[$i] > (float)$purchaseInfo->quantity) {
                                    $fifoStock = abs($fifoStock - (float) $purchaseInfo->quantity);
                                    $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float) $purchaseInfo->unit_price - (float) round($discount_per_qty, 2);
                                    $salesProfit->selling_qty =  (float) $purchaseInfo->quantity;  //35
                                    // $salesProfit->selling_qty =  (float) $request->selling_qty[$i];
                                    $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                                    $salesProfit->created_at = Carbon::now();
                                    $salesProfit->save();

                                    $purchaseInfo->update([
                                        'quantity' => 0,
                                    ]);
                                } else {
                                    $purchaseInfo->update([
                                        'quantity' => (float) $purchaseInfo->quantity - $fifoStock,
                                    ]);

                                    $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float)$purchaseInfo->unit_price - (float) $discount_per_qty;
                                    $salesProfit->selling_qty =  $fifoStock;
                                    $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                                    $salesProfit->created_at = Carbon::now();
                                    $salesProfit->save();
                                    break;
                                }
                            }
                        } else {
                            $GLOBALS['invoicesStatus'] = '0';
                            Invoice::findOrFail($invoice->id)->delete();
                            InvoiceDetail::where('invoice_id', $invoice->id)->delete();
                            $allSales = SalesProfit::where('invoice_id', $invoice->id)->get();
                            foreach ($allSales as  $value) {
                                $purchaseStoreInfo = PurchaseStore::where('purchase_id', $value->purchase_id)
                                    ->where('product_id', $value->product_id)
                                    ->first();
                                $purchaseStoreInfo->quantity += $value->selling_qty;
                                $purchaseStoreInfo->update();
                            }

                            $notification = array(
                                'message' => 'Sorry, Request Stock is not available',
                                'alert-type' => 'error',
                            );
                            return redirect()->route('invoice.add')->with($notification);
                        }
                    }




                    $payment = new Payment();
                    $payment_details = new PaymentDetail();
                    $account_details = new AccountDetail();
                    $payment->invoice_id = $invoice->id;
                    $payment->tax_id = $request->tax_id;
                    $payment->customer_id = $invoice->customer_id;
                    $payment->paid_status = $request->paid_status;
                    $payment->delivery_note = $request->delivery_note;
                    $payment->delivery_charge = $request->delivery_charge;
                    if ($request->discount_type == 'percentage') {
                        $payment->discount_type = $request->discount_rate;
                    } else {
                        $payment->discount_type =  $request->discount_type;
                    }
                    $payment->discount_amount = $request->discount_amount;
                    $payment->total_amount = $request->estimated_amount;

                    $customerInfo = Customer::findOrFail($invoice->customer_id);


                    // save to bank data
                    if ($request->bank_id != null) {
                        $bank = Bank::findOrFail($request->bank_id);
                        $bank_details = new BankDetail();
                        $bank_details->bank_id = $request->bank_id;
                        $bank_details->trans_type = 'earning';
                        $bank_details->trans_head = 'Product Sales';
                        $bank_details->trans_id = $request->invoice_no;
                        $bank_details->date = date('Y-m-d', strtotime($request->date));
                        $bank_details->status = '1';
                    }

                    // account details
                    $account_details->invoice_id = $invoice->id;
                    $account_details->customer_id = $invoice->customer_id;
                    $account_details->total_amount = $request->estimated_amount;
                    $account_details->date = date('Y-m-d', strtotime($request->date));
                    $account_details->paid_status = $request->paid_status;
                    $account_details->bank_name = $request->bank_id;
                    $account_details->note = $request->note;
                    $account_details->created_at = Carbon::now();



                    if ($request->paid_status == 'full_paid') {
                        $payment->paid_amount = $request->estimated_amount;
                        $payment->due_amount = '0';
                        $payment_details->current_paid_amount = $request->estimated_amount;

                        // account details
                        $account_details->paid_amount = $request->estimated_amount;
                        $account_details->due_amount = '0';

                        $bank->balance += (float) $request->estimated_amount;
                        $bank_details->balance += (float) $request->estimated_amount;
                    } elseif ($request->paid_status == 'full_due') {
                        $payment->paid_amount = '0';
                        $payment->due_amount = $request->estimated_amount;

                        //account details
                        $account_details->paid_amount = '0';
                        $account_details->due_amount = $request->estimated_amount;

                        $customerInfo->total_due += $request->estimated_amount;
                        $customerInfo->update();
                    } elseif ($request->paid_status == 'partial_paid') {
                        $payment->paid_amount = $request->paid_amount;
                        $payment->due_amount = $request->estimated_amount - $request->paid_amount;
                        $payment_details->current_paid_amount = $request->paid_amount;

                        //account details
                        $account_details->paid_amount = $request->paid_amount;
                        $account_details->due_amount = $request->estimated_amount - $request->paid_amount;

                        $bank->balance += (float)$request->paid_amount;
                        $bank_details->balance += (float)$request->paid_amount;

                        $customerInfo->total_due += $account_details->due_amount;
                        $customerInfo->update();
                    }


                    if ($request->tax_id != '0') {
                        $tax = Tax::findOrFail($request->tax_id);
                        $payment->vat_tax =  $tax->rate;
                        $payment->vat_amount = $request->vat_tax;
                    }
                    $payment->save();
                    $invoice->status = '1';
                    $invoice->update();
                    $account_details->save();

                    if ($request->bank_id != null) {
                        $payment_details->invoice_id = $invoice->id;
                        $payment_details->date = date('Y-m-d', strtotime($request->date));
                        $payment_details->paid_status = $request->paid_status;
                        $payment_details->bank_name = $request->bank_id;
                        $payment_details->note = $request->note;
                        $payment_details->save();

                        $bank_details->save();
                        $bank->update();
                    }

                    if ($request->delivery_charge != null) {
                        $bankInfo = Bank::findOrFail(1);
                        if ($bankInfo->balance >= $request->delivery_charge) {
                            $expense = new Expense();
                            $expense->head = 'Labour Cost';
                            $expense->description = $request->delivery_note;
                            $expense->amount = $request->delivery_charge;
                            $expense->date = date('Y-m-d', strtotime($request->date));
                            $expense->bank_id = $bankInfo->id;
                            $expense->status = '0';
                            $expense->save();
                        } else {
                            $notification = array(
                                'message' => 'Insufficient Balance on selected Bank!',
                                'alert_type' => 'error',
                            );
                            return redirect()->route('invoice.add')->with($notification);
                        }
                    }
                }
            });
        } //end else


        if ($GLOBALS['invoicesStatus'] == '0') {
            $notification = array(
                'message' => 'Sorry, Request Stock is not available',
                'alert-type' => 'error',
            );
            return redirect()->route('invoice.add')->with($notification);
        } else {
            $notification = array(
                'message' => 'Invoice Added successfully!',
                'alert_type' => 'success',
            );
            return redirect()->route('invoice.all')->with($notification);
        }
    }



    public function InvoiceEdit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $customers = Customer::OrderBy('name', 'asc')->get();
        $categories = Category::all();
        $products = Product::all();
        $accounts = Bank::OrderBy('name', 'asc')->get();
        $taxes = Tax::where('status', '1')->OrderBy('name', 'asc')->get();
        return view('admin.invoice.invoice_edit', compact('invoice', 'customers', 'categories', 'products', 'accounts', 'taxes'));
    }


    public function UpdateInvoice(Request $request)
    {
        $invoice_id = $request->id;
        $invoice = Invoice::findOrFail($invoice_id);


        if ($request->paid_amount > $request->estimated_total) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

            $invoice = Invoice::findOrFail($invoice_id);

            $invoice->date = $request->date;
            $invoice->customer_id = $request->customer_id;
            $invoice->updated_by = Auth::user()->id;



            foreach ($invoice->invoice_details as $item) {
                $products = Product::where('id', $item->product_id)->first();
                $products->quantity = ((float) $products->quantity) + ((float) $item->selling_qty);
                $products->update([
                    'quantity' => $products->quantity,
                ]);
                $item->delete();
            }

            $count_category = count($request->category_id);

            for ($i = 0; $i < $count_category; $i++) {

                $invoice_details = new InvoiceDetail();
                $invoice_details->date = date('Y-m-d', strtotime($request->date));
                $invoice_details->invoice_id = $invoice->id;
                $invoice_details->category_id = $request->category_id[$i];
                $invoice_details->product_id = $request->product_id[$i];
                $invoice_details->selling_qty = $request->selling_qty[$i];
                $invoice_details->unit_price = $request->unit_price[$i];
                $invoice_details->selling_price = $request->selling_price[$i];

                $purchaseQty = PurchaseStore::where('product_id', $invoice_details->product_id)->where('quantity', '!=', 0)->get();

                if ($purchaseQty->sum('quantity') >=  $invoice_details->selling_qty) {
                    $fifoStock = ((float) $request->selling_qty[$i]); //200 //165
                    foreach ($purchaseQty as  $purchaseInfo) {
                        $salesProfit = new SalesProfit();
                        $salesProfit->invoice_id = $invoice->id;
                        $salesProfit->purchase_id = $purchaseInfo->purchase_id;
                        $salesProfit->product_id = $request->product_id[$i];
                        $salesProfit->unit_price_purchase = (float) $purchaseInfo->unit_price;
                        $salesProfit->unit_price_sales =  (float) $request->unit_price[$i];


                        if ((float) $request->selling_qty[$i] > (float)$purchaseInfo->quantity) {  //sellinh => 150  =>purchase=> 100
                            $fifoStock = abs($fifoStock - (float) $purchaseInfo->quantity); // 200-35 = 165
                            $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float)$purchaseInfo->unit_price;
                            $salesProfit->selling_qty =  (float) $purchaseInfo->quantity;  //35
                            // $salesProfit->selling_qty =  (float) $request->selling_qty[$i];
                            $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                            $salesProfit->created_at = Carbon::now();
                            $salesProfit->save();

                            $purchaseInfo->update([
                                'quantity' => 0,
                            ]);
                        } else {
                            $purchaseInfo->update([
                                'quantity' => (float) $purchaseInfo->quantity - $fifoStock,
                            ]);

                            $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float)$purchaseInfo->unit_price;
                            $salesProfit->selling_qty =  $fifoStock;
                            $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                            $salesProfit->created_at = Carbon::now();
                            $salesProfit->save();
                            break;
                        }
                    }
                } else {
                    $GLOBALS['invoicesStatus'] = '0';
                    Invoice::findOrFail($invoice->id)->delete();
                    InvoiceDetail::where('invoice_id', $invoice->id)->delete();
                    $allSales = SalesProfit::where('invoice_id', $invoice->id)->get();
                    foreach ($allSales as  $value) {
                        $purchaseStoreInfo = PurchaseStore::where('purchase_id', $value->purchase_id)
                            ->where('product_id', $value->product_id)
                            ->first();
                        $purchaseStoreInfo->quantity += $value->selling_qty;
                        $purchaseStoreInfo->update();
                    }

                    $notification = array(
                        'message' => 'Sorry, Request Stock is not available',
                        'alert-type' => 'error',
                    );
                    return redirect()->route('invoice.add')->with($notification);
                }
            }




            Payment::where('invoice_id', $invoice_id)->delete();
            $paymentDetails = PaymentDetail::where('invoice_id', $invoice_id)->get();
            $accountDetails = AccountDetail::where('invoice_id', $invoice_id)->get();
            foreach ($paymentDetails as $item) {
                $item->delete();
            }
            foreach ($accountDetails as $account) {
                $account->delete();
            }

            $payment = new Payment();
            $payment_details = new PaymentDetail();
            $account_details = new AccountDetail();
            $payment->invoice_id = $invoice->id;
            $payment->customer_id = $invoice->customer_id;
            $payment->paid_status = $request->paid_status;
            $payment->discount_amount = $request->discount_amount;
            $payment->total_amount = $request->estimated_total;



            $payment_details->paid_status = $request->paid_status;
            $payment_details->paid_source = $request->paid_source;
            $payment_details->bank_name = $request->bank_id;
            $payment_details->note = $request->note;



            // account details
            $account_details->invoice_id = $invoice->id;
            $account_details->customer_id = $invoice->customer_id;
            $account_details->total_amount = $request->estimated_total;
            $account_details->date = date('Y-m-d', strtotime($request->date));
            $account_details->paid_status = $request->paid_status;
            $account_details->paid_source = $request->paid_source;
            $account_details->bank_name = $request->bank_id;
            $account_details->note = $request->note;


            if ($request->paid_status == 'full_paid') {
                $payment->paid_amount = $request->estimated_total;
                $payment->due_amount = '0';
                $payment_details->current_paid_amount = $request->estimated_total;

                // account details
                $account_details->paid_amount = $request->estimated_total;
                $account_details->due_amount = '0';
            } elseif ($request->paid_status == 'full_due') {
                $payment->paid_amount = '0';
                $payment->due_amount = $request->estimated_total;
                $payment_details->current_paid_amount = '0';

                //account details
                $account_details->paid_amount = '0';
                $account_details->due_amount = $request->estimated_total;
            } elseif ($request->paid_status == 'partial_paid') {
                $payment->paid_amount = $request->paid_amount;
                $payment->due_amount = $request->estimated_total - $request->paid_amount;
                $payment_details->current_paid_amount = $request->paid_amount;

                //account details
                $account_details->paid_amount = $request->paid_amount;
                $account_details->due_amount = $request->estimated_total - $request->paid_amount;
            }


            $payment->save();

            $payment_details->invoice_id = $invoice->id;
            $payment_details->date = date('Y-m-d', strtotime($request->date));
            $payment_details->save();
            $account_details->save();
        }

        $notification = array(
            'message' => 'Invoice Updated Successfully',
            'alert_type' => 'success'
        );
        return redirect()->route('invoice.all')->with($notification);
    }



    public function ViewInoivce($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('admin.invoice.invoice_view', compact('invoice'));
    }

    public function InvoiceView($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('admin.invoice.invoice_view', compact('invoice'));
    }


    public function InvoicePrint($id)
    {
        $invoice = Invoice::with('invoice_details')->findOrFail($id);
        $invoiceDetails = InvoiceDetail::where('invoice_id', $invoice->id)->get();
        // dd($invoiceDetails);
        return view('admin.pdf.invoice_pdf', compact('invoice', 'invoiceDetails'));
    }


    public function DeletePurchase($id)
    {
        $invoice = Invoice::findOrFail($id);

        foreach ($invoice->InvoiceDetails as $item) {
            $item->delete();
        }

        $invoice->delete();


        $notification = array(
            'message' => 'Invoice Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }


    public function UniqueNumber()
    {
        $invoice = Invoice::latest()->first();
        if ($invoice) {
            $name = $invoice->invoice_no;
            $number = explode('_', $name);
            $invoice_no = 'INV_' . str_pad((int)$number[1] + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $invoice_no = 'INV_000001';
        }
        return $invoice_no;
    }

    public function GetProduct($id)
    {
        $products = Product::where('category_id', $id)->get();
        return response()->json($products);
    }




    public function InvoiceSendPdf($id)
    {

        $data['email'] = "faridnitbd@gmail.com";
        $data['title'] = "Nebula IT";
        $data['body'] = "this is test";
        $data['id'] = $id;

        // $invoice = Invoice::with('invoice_details')->findOrFail($id);
        // $invoiceDetails = InvoiceDetail::where('invoice_id', $invoice->id)->get();
        // dd($invoiceDetails);
        $pdf = PDF::loadview('emails.order_invoice_pdf', $data);
        $data['pdf'] = $pdf;
        Mail::to($data['email'])->send(new InvoicePdfMail($data));
        dd('mail send successfully');
    }
}
