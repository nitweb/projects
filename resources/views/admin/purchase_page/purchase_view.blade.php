@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Purchase No</label>
                                    <input class="form-control" type="text" name="purchase_no"
                                        value="{{ $purchase->purchase_no }}" id="purchase_no" readonly disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Date</label>
                                    <input type="text" class="form-control" disabled name="date" id="date"
                                        value="{{ $purchase->date }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="supplier_id" class="col-sm-12 col-form-label">Supplier
                                        Name</label>
                                    <input type="text" class="form-control" name="supplier_id" id="supplier_id"
                                        value="{{ $purchase->supplier->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <table class="table table-responsive table-striped">
                                    <thead class="bg-body">
                                        <tr>
                                            <th class="text-center">Ingredient</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit Price</th>
                                            {{-- @if ($purchase->discount_amount != 0)
                                                <th class="text-center">Unit Price After Discount</th>
                                            @endif --}}

                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @php
                                            $purchaseDetails = App\Models\PurchaseMeta::where('purchase_id', $purchase->id)->get();
                                            $totalIncludingCartton = 0;
                                            $totalQty = $purchaseDetails->sum('quantity');
                                            if ($purchase->discount_amount != 0) {
                                                $after_discount = $purchase->discount_amount / $totalQty;
                                            }

                                        @endphp
                                        @foreach ($purchaseDetails as $item)
                                            <tr class="tr">
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="ingredient_id"
                                                        id="category_id" value="{{ $item->ingredient->name }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control quantity"
                                                        placeholder="Quantity" name="quantity[]" id="quantity"
                                                        value="{{ $item->quantity }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control unit_price"
                                                        placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                        value="{{ $item->unit_price }}" disabled>
                                                </td>
                                                @php
                                                    $purchaseDetails = App\Models\PurchaseStore::where('purchase_id', $item->purchase_id)
                                                        ->where('ingredient_id', $item->ingredient_id)
                                                        ->first();
                                                @endphp

                                                {{-- @if ($purchase->discount_amount != 0)
                                                    <td class="text-center">
                                                        <input type="text" class="form-control unit_price"
                                                            placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                            value="{{ $item->unit_price - $after_discount }}" disabled>
                                                    </td>
                                                @endif --}}
                                                {{-- <td class="text-center">
                                                    <input type="text" class="form-control unit_price"
                                                        placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                        value="{{ $purchaseDetails->unit_price }}" disabled>
                                                </td> --}}

                                                @php
                                                    $price = $item->quantity * $item->unit_price;
                                                    $totalIncludingCartton += $price;
                                                @endphp
                                                <td class="text-center">
                                                    <input type="text" class="form-control total_amount"
                                                        placeholder="Total" readonly value="{{ $price }}" disabled>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        @php
                                            $colspan = 3;
                                            // if ($purchase->discount_amount != 0) {
                                            //     $colspan = 6;
                                            // }
                                        @endphp

                                        <tr>
                                            <th colspan="{{ $colspan }}" class="text-end">
                                                <p class="mb-0">Product Total</p>
                                            </th>
                                            <th>
                                                <input type="text" readonly class="form-control" name="estimated_total"
                                                    id="estimated_total" placeholder="Grand Total"
                                                    value="{{ $totalIncludingCartton }}" disabled>
                                            </th>
                                        </tr>
                                        @if ($purchase->discount_amount != 0)
                                            <tr>
                                                <th colspan="{{ $colspan }}" class="text-end">
                                                    <p class="mb-0">Discount Amount</p>
                                                </th>
                                                <th>
                                                    <input type="text" readonly class="form-control"
                                                        name="estimated_total" id="estimated_total"
                                                        placeholder="Grand Total"
                                                        value="{{ $purchase->discount_amount }}" disabled>
                                                </th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th colspan="{{ $colspan }}" class="text-end">
                                                <p class="mb-0">Net Amount</p>
                                            </th>
                                            <th>
                                                <input type="text" readonly class="form-control"
                                                    name="estimated_total" id="estimated_total" placeholder="Grand Total"
                                                    value="{{ $purchase->total_amount }}" disabled>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="{{ $colspan }}" class="text-end">
                                                <p class="mb-0">Paid Amount</p>
                                            </th>
                                            <th>
                                                <input type="text" readonly class="form-control"
                                                    name="estimated_total" id="estimated_total" placeholder="Grand Total"
                                                    value="{{ $purchase->paid_amount }}" disabled>
                                            </th>
                                        </tr>

                                        @if ($purchase->due_amount != 0)
                                            <tr>
                                                <th colspan="{{ $colspan }}" class="text-end">
                                                    <p class="mb-0">Due Amount</p>
                                                </th>
                                                <th>
                                                    <input type="text" readonly class="form-control"
                                                        name="estimated_total" id="estimated_total"
                                                        placeholder="Grand Total" value="{{ $purchase->due_amount }}"
                                                        disabled>
                                                </th>
                                            </tr>
                                        @endif
                                        {{-- <tr>
                                            <th colspan="4">
                                                <label for="">Paid Amount</label>
                                                <input type="text" name="paid_amount" id="paid_amount"
                                                    placeholder="Enter Paid Amount" class="form-control"
                                                    value="{{ $purchase->paid_amount }}" disabled>
                                            </th>
                                            <th>
                                                <label for="">Total Amount</label>
                                                <input type="text" readonly class="form-control"
                                                    name="estimated_total" id="estimated_total" placeholder="Grand Total"
                                                    value="{{ $purchase->total_amount }}" disabled>
                                            </th>
                                        </tr> --}}
                                    </tfoot>
                                </table>

                                <div class="form-group mt-5">

                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- js --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>



    <script>
        $(document).ready(function() {

            $(document).on("keyup click", ".unit_price,.quantity", function() {
                let product_qty = $(this).closest('tr').find('input.quantity').val();
                let unit_price = $(this).closest('tr').find('input.unit_price').val();
                let total = unit_price * product_qty;
                console.log(total);
                $(this).closest('tr').find('input.total_amount').val(total);
                totalAmountOfPrice();
            });
        });
    </script>
    {{-- <script>
        function cloneRow() {
            const tr = `
            <tr class="tr">
                                            <td class="text-center">
                                                <select name="category_id[]" id="category_id"
                                                    class="form-control form-select select2">
                                                    <option selected value="">Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select name="product_id[]" id="product_id"
                                                    class="form-control form-select select2">
                                                    <option selected value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control quantity" placeholder="Quantity"
                                                    name="quantity[]" id="quantity">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control unit_price" placeholder="Unit Price"
                                                    name="unit_price[]" id="unit_price">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control total_amount" placeholder="Total"
                                                    name="total" id="total" readonly>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" onclick="removeRow(event)" class="btn btn-danger">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        `;
            $('.tbody').append(tr);
        }

        function removeRow(event) {
            if ($('.tr').length > 1) {
                $(event.target).closest('.tr').remove();
                totalAmountOfPrice();
            }
        }
    </script> --}}

    <script>
        // calculate sum of amount
        function totalAmountOfPrice() {
            let sum = 0;
            $('.total_amount').each(function() {
                let value = $(this).val();
                if (!isNaN(value) && value.length != 0) {
                    sum += parseFloat(value);
                }
            });
            $("#estimated_total").val(sum);
        }
    </script>
@endsection
