@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('get.ingredient.list') }}" method="POST" class="custom-validation"
                            novalidate="" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label class="col-sm-12 col-form-label">Category</label>
                                        <select name="category_id[]" id="category_1"
                                            class="form-control form-select category cat" required=""
                                            data-parsley-required-message="Category Id is required">
                                            <option selected value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id == $upInfo->category_id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label class="col-sm-12 col-form-label">Product</label>
                                        <select name="product_id[]" id="product_1" class="form-control form-select product"
                                            required="" data-parsley-required-message="Product Id is required">
                                            <option selected value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $product->id == $upInfo->product_id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="md-3">
                                        <label class="col-sm-12 col-form-label opacity-0">Submission</label>
                                        <button type="submit" class="btn btn-info" id="storeBtn">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- <div class="row mt-3" id="customer-info" style="display: none;">
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="customer_name" class="col-sm-12 col-form-label">Customer Name</label>
                                        <input type="text" class="form-control" name="customer_name" id="customer_name"
                                            placeholder="Customer Name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="customer_email" class="col-sm-12 col-form-label">Customer Email</label>
                                        <input type="email" class="form-control" name="customer_email" id="customer_email"
                                            placeholder="Customer Email">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="phone" class="col-sm-12 col-form-label">Customer Phone</label>
                                        <input type="tel" class="form-control" name="phone" id="phone"
                                            placeholder="Customer Phone">
                                    </div>
                                </div>
                            </div> --}}
                        <div class="row">
                            <div class="col-md-12 mt-5 table-responsive">
                                <table class="table border table-responsive table-striped">
                                    <thead class="bg-body">
                                        <tr>
                                            <span id="ingredient_id4"></span>
                                            <th class="text-center">Ingredient</th>
                                            {{-- <th class="text-center">Current Stock</th> --}}
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total</th>
                                            {{-- <th class="text-center">
                                                    <button class="btn btn-success" type="button" onclick="cloneRow()">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach ($ingredient_details as $value)
                                            <tr class="tr">
                                                <td class="text-center">
                                                    <select name="ingredient_id[]" id="ingredient_1"
                                                        class="form-control form-select  ingredient" required=""
                                                        data-parsley-required-message="Ingredient Id is required">
                                                        {{-- <option selected value="">Select Ingredient</option> --}}
                                                        @foreach ($ingredients as $ingredient)
                                                            <option value="{{ $ingredient->id }}"
                                                                {{ $ingredient->id == $value->ingredient_id ? 'selected' : '' }}>
                                                                {{ $ingredient->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control stock"
                                                        placeholder="Stock Quantity" name="stock_qty[]"
                                                        value="{{ $value->quantity }}" id="stock_qty_1" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control quantity"
                                                        placeholder="Quantity" name="selling_qty[]" id="selling_qty"
                                                        required=""
                                                        data-parsley-required-message="Quantity Id is required">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control unit_price"
                                                        placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                        required=""
                                                        data-parsley-required-message="Unit Price is required">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control selling_price"
                                                        placeholder="Total" readonly name="selling_price[]">
                                                </td>
                                                {{-- <td class="text-center">
                                                    <button type="button" onclick="removeRow(event)"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td> --}}
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end"> Sub Total</th>
                                            <th>
                                                <input type="text" readonly class="form-control" name="sub_total"
                                                    id="sub_total" placeholder="Grand Total" value="0">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="salary" id="salary" class="form-control"
                                                    placeholder="Salary">
                                            </th>
                                            <th>
                                                <input type="number" name="salary_amount" value="0" id="salary_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="interest" id="interest"
                                                    class="form-control" placeholder="Interest ">
                                            </th>
                                            <th>
                                                <input type="number" name="interest_amount" id="interest_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="rent" id="rent" class="form-control"
                                                    placeholder="Rent ">
                                            </th>
                                            <th>
                                                <input type="number" name="rent_amount" id="rent_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="transport" id="transport"
                                                    class="form-control" placeholder="Transport">
                                            </th>
                                            <th>
                                                <input type="number" name="transport_amount" id="transport_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="travelling" id="travelling"
                                                    class="form-control" placeholder="Travelling">
                                            </th>
                                            <th>
                                                <input type="number" name="travelling_amount" id="travelling_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="entertainment" id="entertainment"
                                                    class="form-control" placeholder="Entertainment">
                                            </th>
                                            <th>
                                                <input type="number" name="entertainment_amount"
                                                    id="entertainment_amount" class="form-control"
                                                    placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="printing" id="printing"
                                                    class="form-control" placeholder="Printing">
                                            </th>
                                            <th>
                                                <input type="number" name="printing_amount" id="printing_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="license" id="license" class="form-control"
                                                    placeholder="License">
                                            </th>
                                            <th>
                                                <input type="number" name="license_amount" id="license_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="mobile_bill" id="mobile_bill"
                                                    class="form-control" placeholder="Mobile Bill">
                                            </th>
                                            <th>
                                                <input type="number" name="mobile_bill_amount" id="mobile_bill_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="selling" id="selling" class="form-control"
                                                    placeholder="Selling">
                                            </th>
                                            <th>
                                                <input type="number" name="selling_amount" id="selling_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="internet" id="internet"
                                                    class="form-control" placeholder="Internet">
                                            </th>
                                            <th>
                                                <input type="number" name="internet_amount" id="internet_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="equipment" id="equipment"
                                                    class="form-control" placeholder="Equipment">
                                            </th>
                                            <th>
                                                <input type="number" name="equipment_amount" id="equipment_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="misc" id="misc" class="form-control"
                                                    placeholder="Misc">
                                            </th>
                                            <th>
                                                <input type="number" name="misc_amount" id="misc_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end" width="50%">
                                                <input type="text" name="profit" id="profit" class="form-control"
                                                    placeholder="Profit">
                                            </th>
                                            <th>
                                                <input type="number" name="profit_amount" id="profit_amount"
                                                    class="form-control" placeholder="Enter Amount">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"> </th>
                                            <th class="text-end"> Grand Total</th>
                                            <th>
                                                <input type="text" readonly class="form-control"
                                                    name="estimated_amount" id="estimated_amount"
                                                    placeholder="Grand Total" value="0">
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            {{-- <div class="row mb-3 mt-4">
                                    <div class="col-md-3">
                                        <select class="form-control" name="paid_status" id="paid_status" required>
                                            <option value="" selected disabled>Select Paid Status</option>
                                            <option value="full_paid">Full Paid</option>
                                            <option value="full_due">Full Due</option>
                                            <option value="partial_paid">Partial Paid</option>
                                        </select>
                                        <input type="text" placeholder="Enter Paid Amount" class="form-control"
                                            name="paid_amount" id="paid_amount" style="display:none;">
                                    </div>
                                    <div class="col-md-3" id="paid_source_col">
                                        <select class="form-control select2" name="bank_id" id="bank_id">
                                            <option value="">Select Payment Status</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>

                                        <div class="row" id="transaction_note" style="display: none;">
                                            <div class="col-12">
                                                <input type="text" placeholder="Note" class="form-control"
                                                    name="note" id="note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-info" id="storeButton">Invoice
                                            Store</button>
                                    </div>

                                </div> --}}
                        </div>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    {{-- js --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>


    <script>
        function taxUpdate(tax_id) {
            let taxId = tax_id
            console.log(taxId);
            if (taxId != '0') {
                let withoutVat = $('#totalPaidAmount').val();

                $.ajax({
                    type: 'GET',
                    url: "{{ route('get.tax.percentage', '') }}" + "/" + taxId,
                    success: function(data) {
                        console.log('data', data);
                        let sub_total = $('#sub_total').val();



                        let discount_type = $("#discount_type").val();
                        let discount_rate = parseFloat($('#discount_rate').val());
                        let amount = 0;
                        console.log(discount_rate);
                        if (!isNaN(discount_rate) && discount_rate.length != 0) {
                            if (discount_type == 'flat') {
                                amount = parseFloat(discount_rate);
                            } else if (discount_type == 'percentage') {
                                let percentageAmount = Math.round((sub_total * discount_rate) / 100);
                                amount = parseFloat(percentageAmount);
                            }
                        }

                        let delivery_charge = $("#delivery_charge").val();
                        let delivery_amount = 0;
                        if (!isNaN(delivery_charge) && delivery_charge.length != 0) {
                            delivery_amount += $("#delivery_charge").val();
                        }

                        let after_discount = parseFloat(sub_total) - amount;
                        let tax_amount = Math.round((after_discount * data) / 100);
                        let grandTotal = after_discount + parseFloat(tax_amount) + parseFloat(delivery_amount);
                        $('#estimated_amount').val(grandTotal);
                        $('#vat_tax').val(tax_amount);
                    }
                });
            } else {
                let sub_total = $('#sub_total').val();
                let discount_type = $("#discount_type").val();
                let discount_rate = parseFloat($('#discount_rate').val());
                let discount_amount = 0;
                if (!isNaN(discount_rate) && discount_rate.length != 0) {
                    if (discount_type == 'flat') {
                        discount_amount = parseFloat(discount_rate);
                    } else if (discount_type == 'percentage') {
                        let percentageAmount = Math.round((sub_total * discount_rate) / 100);
                        discount_amount = parseFloat(percentageAmount);
                    }
                }

                let delivery_charge = $("#delivery_charge").val();
                let delivery_amount = 0;
                if (!isNaN(delivery_charge) && delivery_charge.length != 0) {
                    delivery_amount += $("#delivery_charge").val();
                }


                let amount = 0;
                $('#vat_tax').val(amount);
                let newTotal = parseFloat(sub_total) + amount + parseFloat(delivery_amount) - parseFloat(discount_amount);
                $('#estimated_amount').val(newTotal);

            }
        }
    </script>
    <script>
        $(document).ready(function() {

            $(document).on("keyup click", ".unit_price,.quantity", function() {
                let product_qty = $(this).closest('tr').find('input.quantity').val();
                let unit_price = $(this).closest('tr').find('input.unit_price').val();
                let total = unit_price * product_qty;
                console.log(total);
                $(this).closest('tr').find('input.selling_price').val(total);
                totalAmountOfPrice();

                let taxId = $('#tax_id').val();
                taxUpdate(taxId);

            });
        });
    </script>
    <script>
        let count = 2;

        function cloneRow() {
            const tr = `
            <tr class="tr">

               <td class="text-center">
                    <select name="ingredient_id[]" id="ingredient_${count}" class="form-control form-select  category" required="" data-parsley-required-message="Ingredient Id is required">
                                <option selected value="">Select Ingredient</option>
                                        @foreach ($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">
                                                {{ $ingredient->name }}
                                            </option>
                                        @endforeach
                    </select>
                </td>
                <td class="text-center">
                        <input type="text" class="form-control stock"
                            placeholder="Stock Quantity" name="stock_qty[]" id="stock_qty_${count}" readonly>
                </td>
                <td class="text-center">
                    <input type="text" class="form-control quantity"
                        placeholder="Quantity" name="selling_qty[]" id="selling_qty"
                        required=""
                        data-parsley-required-message="Quantity Id is required">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control unit_price"
                        placeholder="Unit Price" name="unit_price[]" id="unit_price"
                        required=""
                        data-parsley-required-message="Unit Price is required">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control selling_price" name="selling_price[]"
                        placeholder="Total" readonly>
                </td>
                <td class="text-center">
                    <button type="button" onclick="removeRow(event)"
                        class="btn btn-danger">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>

            `;
            $('.tbody').append(tr);
            count++;
        }



        function removeRow(event) {
            if ($('.tr').length > 1) {
                $(event.target).closest('.tr').remove();
                totalAmountOfPrice();
            }
        }


        $(document).on("change", ".category", function() {
            const id = $(this).find('option:selected').val();


            let dataId = $(this).attr('id');
            let num = dataId.split('_');


            $.ajax({
                type: 'GET',
                url: "{{ route('get.product.sales', '') }}" + "/" + id,
                success: function(data) {
                    console.log(data);

                    let html = '<option value="">Select Product </option>';
                    $.each(data, function(key, product) {
                        html +=
                            `<option value="${product.id}">${product.name} </option>`;
                    });
                    $("#product_" + num[1]).html(html);
                }
            });
        });
    </script>
    <script>
        $(document).on("change", ".ingredient", function() {
            // const id = $(this).closest('tr').find('option:selected').val();
            const id = $(this).val();
            let dataId = $(this).attr('id');
            let num = dataId.split('_');

            $.ajax({
                type: 'GET',
                url: "{{ route('get.ingredient.stock', '') }}" + "/" + id,
                success: function(data) {
                    console.log('date', data);

                    let stock = '0';
                    console.log('stock', stock);
                    $("#stock_qty_" + num[1]).val(data);
                }
            });
        });
    </script>

    <script>
        $(document).on("submit", "#storeBtn", function() {

            let cat_id = $(".cat").val();
            let product_id = $(".product").val();

            console.log("Cat", cat_id);
            console.log("Product", product_id);

            $.ajax({
                type: 'POST',
                url: "{{ route('get.ingredient.list') }}",
                data: {
                    category_id: cat_id,
                    product_id: product_id,
                },
                success: function(ingredients) {
                    console.log("ingredients", ingredients);
                }
            });
        });
    </script>


    <script>
        // calculate sum of amount
        function totalAmountOfPrice() {
            let sum = 0;
            let subTotal = 0;
            let total = 0;
            $('.selling_price').each(function() {
                let value = $(this).val();
                if (!isNaN(value) && value.length != 0) {
                    sum += parseFloat(value);
                    subTotal += parseFloat(value);
                }
            });
            $("#sub_total").val(subTotal);

            let salary_amount = $("#salary_amount").val();
            // alert(salary_amount);
            // let interest_amount = $("#interest_amount").val();
            // let rent_amount = $("#rent_amount").val();
            // let transport_amount = $("#transport_amount").val();
            // let travelling_amount = $("#travelling_amountt").val();
            // let entertainment_amount = $("#entertainment_amount").val();
            // let printing_amount = $("#printing_amount").val();
            // let license_amount = $("#license_amount").val();
            // let mobile_bill_amount = $("#mobile_bill_amount").val();
            // let equipment_amount = $("#equipment_amount").val();
            // let misc_amount = $("#misc_amount").val();


            // let delivery_charge = $("#delivery_charge").val();
            // let delivery_amount = 0;
            // if (!isNaN(delivery_charge) && delivery_charge.length != 0) {
            //     delivery_amount += $("#delivery_charge").val();
            // }

            // if (!isNaN(salary_amount) && salary_amount.length != 0) {
            //     salary_amount = $("#salary_amount").val();
            // }


            console.log("salary", salary_amount);
            let total_amount = parseFloat(salary_amount) + parseFloat(subTotal);

            $("#estimated_amount").val(total_amount);
            console.log("total", subTotal);
        }
    </script>

    <script>
        $(document).on('keyup click change', '#estimated_amount', function() {
            totalAmountOfPrice();
        });

        // $(document).on('keyup', '#discount_rate', function() {
        //     totalAmountOfPrice();
        // });
        // $(document).on('keyup click change', '#delivery_charge', function() {
        //     totalAmountOfPrice();
        // });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#customer_id').on('change', function() {
                let customerId = $(this).val();
                console.log(customerId);
                if (customerId == '0') {
                    $('#customer-info').show();
                } else {
                    $('#customer-info').hide();
                }
            });
        });
    </script>

    {{--  dropdown menu select  --}}
    <script type="text/javascript">
        $(document).ready(function() {

            $('#paid_status').on('change', function() {
                let paidStatus = $(this).val();
                console.log('paidSource', paidStatus);
                if (paidStatus) {
                    $('#paid_source_col').show();
                    $('#vat_tax_col').show();
                }

                if (paidStatus == 'full_paid') {
                    $paidAmount = $('#estimated_amount').val();
                    $('#totalPaidAmount').val($paidAmount);
                }
                if (paidStatus == 'partial_paid') {
                    // $paidAmount = $('#paid_amount').val();
                    // $('#totalPaidAmount').val($paidAmount);
                    paidAmount();
                    $('#paid_amount').show();
                } else {
                    $('#paid_amount').hide();
                }

                if (paidStatus == 'full_due') {
                    $('#paid_source_col').hide();
                    $paidAmount = 0;
                    $('#totalPaidAmount').val($paidAmount);
                }

            });


            $('#bank_id').on('change', function() {
                let bank_id = $(this).val();
                if (bank_id != '') {
                    $('#transaction_note').show();
                } else {
                    $('#transaction_note').hide();
                }
            });

            $('#vat_tax_field').on('change', function() {
                let vatTaxField = $(this).val();
                console.log('vat_tax_field', vatTaxField);
                if (vatTaxField == 'with-vat-tax') {
                    $('.vat').show();
                    $('.tax').show();
                } else {
                    $('.vat').hide();
                    $('.tax').hide();
                }
            });


            // new customer
            $('#company_id').on('change', function() {
                let compnayId = $(this).val();
                console.log(compnayId);
                if (compnayId == '0') {
                    $('#new_company').show();
                    $('#default_addBtn').hide();
                } else {
                    $('#new_company').hide();
                    $('#default_addBtn').show();
                }
            });


            function paidAmount() {
                $('#paid_amount').on('keyup click', function() {
                    let amount = $(this).val();
                    $('#totalPaidAmount').val(amount);
                    console.log('paidclciked');
                    console.log('amount', amount);
                });
            }

            $('#tax_id').on('change', function() {
                let taxId = $(this).val();
                taxUpdate(taxId);
            });
        });
    </script>
@endsection
